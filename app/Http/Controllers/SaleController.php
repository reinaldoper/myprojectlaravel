<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'produto_id' => 'required|exists:produtos,id',
                'quantidade' => 'required|integer|min:1',
                'preco_unitario' => 'required|numeric',
            ]);

            $validated['preco_total'] = $validated['quantidade'] * $validated['preco_unitario'];
            $validated['data_venda'] = now();
            $sale = Venda::create($validated);
            return response()->json($sale, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        }
    }
}
