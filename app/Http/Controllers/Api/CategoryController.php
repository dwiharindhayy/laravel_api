<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat',
            'data' => $category
        ]);
    }

}