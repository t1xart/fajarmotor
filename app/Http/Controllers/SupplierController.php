<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('code', 'like', "%{$request->search}%"))
            ->paginate(20)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255', 'contact_person' => 'nullable|string', 'phone' => 'nullable|string',
            'email' => 'nullable|email', 'address' => 'nullable|string', 'city' => 'nullable|string',
            'province' => 'nullable|string', 'bank_name' => 'nullable|string', 'bank_account' => 'nullable|string',
        ]);
        $data['code'] = Supplier::generateCode($request->name);
        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['purchaseOrders' => fn ($q) => $q->latest()->limit(10)]);

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($request->validate([
            'name' => 'required|string|max:255', 'contact_person' => 'nullable|string', 'phone' => 'nullable|string',
            'email' => 'nullable|email', 'address' => 'nullable|string', 'city' => 'nullable|string',
            'province' => 'nullable|string', 'bank_name' => 'nullable|string', 'bank_account' => 'nullable|string',
            'rating' => 'nullable|integer|min:0|max:5', 'is_active' => 'boolean',
        ]));

        return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier diperbarui.');
    }
}
