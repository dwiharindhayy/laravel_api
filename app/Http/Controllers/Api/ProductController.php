<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // MENAMPILKAN SEMUA PRODUK + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = Product::with('category');

        // fitur search berdasarkan nama
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // pagination (5 data per halaman)
        $products = $query->latest()->paginate(5);

        return response()->json($products, 200);
    }

    // MENAMBAHKAN PRODUK
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // upload gambar
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        // simpan database
        $product = Product::create([
            'image' => $image->hashName(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    // MENAMPILKAN 1 PRODUK
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'data' => $product
        ], 200);
    }

    // UPDATE PRODUK (SUDAH DIPERBAIKI)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        // VALIDASI DATA
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // UPDATE DATA
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Produk berhasil diupdate',
            'data' => $product
        ], 200);
    }

    // DELETE PRODUK
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ], 200);
    }
}