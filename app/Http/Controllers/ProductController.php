<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products =  Produto::where('is_deleted', false)->orderBy('nome')->get();
        return response()->json($products);
    }

    public function show($id)
    {
        try {
            $product = Produto::findOrFail($id);
            return response()->json($product);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|min:1',
                'preco' => 'required|numeric|min:1',
            ]);
            $product = Produto::create($validated);
            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Produto::findOrFail($id);

            $validated = $request->validate([
                'nome' => 'sometimes|required',
                'preco' => 'sometimes|required|numeric',
            ]);
            $product->update($validated);
            return response()->json($product);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        }
    }

    public function delete($id)
    {
        try {
            $product = Produto::findOrFail($id);
            $product->is_deleted = true;
            $product->save();
            return response()->json(['message' => 'Product deleted success'], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
