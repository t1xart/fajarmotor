<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $suppliers = Supplier::when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('code', 'like', "%{$request->search}%"))
            ->when($request->is_active !== null, fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->paginate($request->per_page ?? 20);

        return SupplierResource::collection($suppliers);
    }

    public function show(Supplier $supplier): SupplierResource
    {
        return new SupplierResource($supplier);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255', 'contact_person' => 'nullable|string',
                'phone' => 'nullable|string', 'email' => 'nullable|email',
                'address' => 'nullable|string', 'city' => 'nullable|string', 'province' => 'nullable|string',
                'bank_name' => 'nullable|string', 'bank_account' => 'nullable|string',
            ]);
            $data['code'] = Supplier::generateCode($request->name);

            $supplier = Supplier::create($data);

            return $this->successResponse(new SupplierResource($supplier), 'Supplier berhasil ditambahkan.', 201);
        } catch (QueryException $e) {
            Log::error('Supplier create failed', ['error' => $e->getMessage()]);

            return $this->errorResponse('Gagal menambahkan supplier.', null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            Log::error('Unexpected supplier create error', ['error' => $e->getMessage()]);

            return $this->errorResponse('Terjadi kesalahan server.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Supplier $supplier): JsonResponse
    {
        try {
            $supplier->update($request->validate([
                'name' => 'sometimes|string|max:255', 'contact_person' => 'nullable|string',
                'phone' => 'nullable|string', 'email' => 'nullable|email',
                'rating' => 'nullable|integer|min:0|max:5', 'is_active' => 'boolean',
                'address' => 'nullable|string', 'city' => 'nullable|string', 'province' => 'nullable|string',
                'bank_name' => 'nullable|string', 'bank_account' => 'nullable|string',
            ]));

            return $this->successResponse(new SupplierResource($supplier->fresh()), 'Supplier berhasil diperbarui.');
        } catch (QueryException $e) {
            Log::error('Supplier update failed', ['supplier_id' => $supplier->id, 'error' => $e->getMessage()]);

            return $this->errorResponse('Gagal memperbarui supplier.', null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            Log::error('Unexpected supplier update error', ['supplier_id' => $supplier->id, 'error' => $e->getMessage()]);

            return $this->errorResponse('Terjadi kesalahan server.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
