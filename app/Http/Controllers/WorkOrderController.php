<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkOrderRequest;
use App\Models\Product;
use App\Models\VehicleType;
use App\Models\Warehouse;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Services\StockService;
use App\Services\WoNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WorkOrderController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request): View
    {
        $orders = WorkOrder::with(['branch', 'vehicleType.brand'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where('customer_name', 'like', "%{$request->search}%")->orWhere('vehicle_plate', 'like', "%{$request->search}%"))
            ->latest()->paginate(20)->withQueryString();

        return view('work-orders.index', compact('orders'));
    }

    public function create(): View
    {
        $vehicleTypes = VehicleType::with('brand')->where('is_active', true)->get()->groupBy('brand.name');
        $products = Product::where('is_active', true)->get();

        return view('work-orders.create', compact('vehicleTypes', 'products'));
    }

    public function store(WorkOrderRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $items = collect($request->items ?? []);
                $partsTot = $items->sum(fn ($i) => $i['price'] * $i['quantity']);
                $warehouse = Warehouse::where('branch_id', auth()->user()->branch_id)->firstOrFail();

                // Validate stock for product items before creating WO
                $products = [];
                foreach ($items->filter(fn ($i) => ! empty($i['product_id'])) as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $products[$item['product_id']] = $product;
                    if (! $this->stockService->hasSufficientStock($product, $warehouse, $item['quantity'])) {
                        $available = $this->stockService->getStockLevel($product, $warehouse);
                        throw new \Exception("Stok {$product->name} tidak cukup. Tersedia: {$available}, diminta: {$item['quantity']}");
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

                    // Deduct stock for product items
                    if (! empty($item['product_id'])) {
                        $product = $products[$item['product_id']] ?? Product::findOrFail($item['product_id']);
                        $this->stockService->createMutation(
                            $product, $warehouse, 'out', -$item['quantity'], $wo,
                            ['notes' => "Work Order {$wo->wo_number}"]
                        );
                    }
                }
            });

            return redirect()->route('work-orders.index')->with('success', 'Work Order berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(WorkOrder $workOrder): View
    {
        $workOrder->load(['items.product', 'vehicleType.brand', 'branch']);

        return view('work-orders.show', compact('workOrder'));
    }

    public function updateStatus(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $request->validate(['status' => 'required|in:pending,in_progress,done,delivered,cancelled']);

        if ($workOrder->status === 'cancelled') {
            return back()->withErrors(['error' => 'WO yang sudah dibatalkan tidak bisa diubah lagi.']);
        }

        try {
            DB::transaction(function () use ($request, $workOrder) {
                $data = ['status' => $request->status];

                if ($request->status === 'cancelled') {
                    $warehouse = Warehouse::where('branch_id', $workOrder->branch_id)->firstOrFail();
                    foreach ($workOrder->items as $item) {
                        if ($item->product_id) {
                            $this->stockService->createMutation(
                                $item->product,
                                $warehouse,
                                'in',
                                $item->quantity,
                                $workOrder,
                                ['notes' => "Pembatalan Work Order {$workOrder->wo_number}"]
                            );
                        }
                    }
                }

                if ($request->status === 'in_progress' && ! $workOrder->started_at) {
                    $data['started_at'] = now();
                }
                if ($request->status === 'done' && ! $workOrder->finished_at) {
                    $data['finished_at'] = now();
                }

                $workOrder->update($data);
            });

            return back()->with('success', 'Status WO diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui status: '.$e->getMessage()]);
        }
    }
}
