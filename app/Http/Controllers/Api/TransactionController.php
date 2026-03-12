<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // MENAMPILKAN SEMUA TRANSAKSI
    public function index()
    {
        $transactions = Transaction::with(['user','product'])->latest()->get();

        return response()->json([
            'data' => $transactions
        ], 200);
    }

    // MENAMBAHKAN TRANSAKSI
    public function store(Request $request)
    {
        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date
        ]);

        return response()->json([
            'message' => 'Transaksi berhasil dibuat',
            'data' => $transaction
        ], 201);
    }
}