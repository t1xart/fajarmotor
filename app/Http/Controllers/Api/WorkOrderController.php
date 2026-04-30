<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrderRequest;
use App\Http\Resources\WorkOrderResource;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Services\StockService;
use App\Services\WoNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request)
    {
        $orders = WorkOrder::with(['branch', 'vehicleType.brand'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where('customer_name', 'like', "%{$request->search}%")->orWhere('vehicle_plate', 'like', "%{$request->search}%"))
            ->latest()->paginate($request->per_page ?? 20);

        return WorkOrderResource::collection($orders);
    }

    public function store(WorkOrderRequest $request)
    {
        $wo = DB::transaction(function () use ($request) {
            $items = collect($request->items ?? []);
            $partsTot = $items->sum(fn ($i) => $i['price'] * $i['quantity']);
            $warehouse = Warehouse::where('branch_id', auth()->user()->branch_id)->first();

            // Validate stock for product items before creating WO
            $products = [];
            if ($warehouse) {
                foreach ($items->filter(fn ($i) => ! empty($i['product_id'])) as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $products[$item['product_id']] = $product;
                    if (! $this->stockService->hasSufficientStock($product, $warehouse, $item['quantity'])) {
                        $available = $this->stockService->getStockLevel($product, $warehouse);
                        throw new \Exception("Stok {$product->name} tidak cukup. Tersedia: {$available}, diminta: {$item['quantity']}");
                    }
                }
            }

            $wo = WorkOrder::create([
                'wo_number' => WoNumberService::generate(),
                'branch_id' => auth()->user()->branch_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'vehicle_plate' => $request->vehicle_plate,
                'vehicle_type_id' => $request->vehicle_type_id,
                'vehicle_year' => $request->vehicle_year,
                'complaint' => $request->complaint,
                'service_fee' => $request->service_fee ?? 0,
                'parts_total' => $partsTot,
                'total' => ($request->service_fee ?? 0) + $partsTot,
                'user_id' => auth()->id(),
            ]);

            foreach ($items as $item) {
                WorkOrderItem::create([
                    'work_order_id' => $wo->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                if (! empty($item['product_id']) && $warehouse) {
                    $product = $products[$item['product_id']] ?? Product::findOrFail($item['product_id']);
                    $this->stockService->createMutation(
                        $product, $warehouse, 'out', -$item['quantity'], $wo,
                        ['notes' => "Work Order {$wo->wo_number}"]
                    );
                }
            }

            return $wo->load(['items.product', 'vehicleType.brand']);
        });

        return new WorkOrderResource($wo);
    }

    public function show(WorkOrder $workOrder)
    {
        return new WorkOrderResource($workOrder->load(['items.product', 'vehicleType.brand', 'branch']));
    }

    public function updateStatus(Request $request, WorkOrder $workOrder)
    {
        $request->validate(['status' => 'required|in:pending,in_progress,done,delivered,cancelled']);
        $data = ['status' => $request->status];
        if ($request->status === 'in_progress' && ! $workOrder->started_at) {
            $data['started_at'] = now();
        }
        if ($request->status === 'done' && ! $workOrder->finished_at) {
            $data['finished_at'] = now();
        }
        $workOrder->update($data);

        return new WorkOrderResource($workOrder->fresh());
    }
}
