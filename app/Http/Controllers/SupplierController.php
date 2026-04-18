<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan semua supplier.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('admin.supplier.index', compact('suppliers'));
    }

    /**
     * Menampilkan form tambah supplier.
     */
    public function create()
    {
        return view('admin.supplier.create');
    }

    /**
     * Menyimpan data supplier baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|unique:suppliers',
            'nama' => 'required',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    /**
     * Update data supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Hapus data supplier.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
