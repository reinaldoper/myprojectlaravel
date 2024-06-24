<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Telefone;
use App\Models\Endereco;
use Illuminate\Http\Request;
use App\Rules\CpfValidationRule;

class ClientController extends Controller
{
    public function index()
    {
        $clients =  Cliente::orderBy('id')->get();
        return response()->json($clients, 200);
    }

    public function show(Request $request, $id)
    {
        try {
            $client = Cliente::with(['enderecos', 'telefones'])->findOrFail($id);

            $query = $client->vendas()->orderBy('created_at', 'desc');

            if ($request->has('mes') && $request->has('ano')) {
                $month = $request->input('mes');
                $year = $request->input('ano');
                $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            }

            $sales = $query->get();

            if ($sales->isEmpty()) {
                return response()->json(['message' => 'No sales found for the specified month and year'], 404);
            }

            $client->vendas = $sales;

            return response()->json($client, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Client not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        }
    }




    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|min:3',
                'cpf' => [
                    'required',
                    'string',
                    'unique:clientes',
                    new CpfValidationRule(),
                ],
                'telefones.*.numero_telefone' => 'sometimes|required|string',
                'enderecos.*.rua' => 'sometimes|required|string',
                'enderecos.*.cidade' => 'sometimes|required|string',
                'enderecos.*.estado' => 'sometimes|required|string',
                'enderecos.*.cep' => 'sometimes|required|string',
            ]);

            $client = Cliente::create($validated);

            if (isset($validated['telefones'])) {
                foreach ($validated['telefones'] as $phoneData) {
                    $phone = new Telefone($phoneData);
                    $client->telefones()->save($phone);
                }
            }

            if (isset($validated['enderecos'])) {
                foreach ($validated['enderecos'] as $addressData) {
                    $address = new Endereco($addressData);
                    $client->enderecos()->save($address);
                }
            }

            $client->load('enderecos', 'telefones', 'vendas.product');

            return response()->json($client, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $client = Cliente::findOrFail($id);

            $validated = $request->validate([
                'nome' => 'sometimes|required',
                'cpf' => [
                    'sometimes|required',
                    'unique:clientes,cpf,' . $client->id,
                    new CpfValidationRule()
                ],
                'telefones.*.numero_telefone' => 'sometimes|required|string',
                'enderecos.*.rua' => 'sometimes|required|string',
                'enderecos.*.cidade' => 'sometimes|required|string',
                'enderecos.*.estado' => 'sometimes|required|string',
                'enderecos.*.cep' => 'sometimes|required|string',
            ]);

            $client->update($validated);

            if (isset($validated['telefones'])) {
                $client->telefones()->delete();

                foreach ($validated['telefones'] as $phoneData) {
                    $phone = new Telefone($phoneData);
                    $client->telefones()->save($phone);
                }
            }

            if (isset($validated['enderecos'])) {
                $client->enderecos()->delete();

                foreach ($validated['enderecos'] as $addressData) {
                    $address = new Endereco($addressData);
                    $client->enderecos()->save($address);
                }
            }

            $client->load('enderecos', 'telefones', 'vendas.product');

            return response()->json($client);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Client not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        }
    }


    public function delete($id)
    {
        try {
            $client = Cliente::findOrFail($id);
            $client->delete();

            return response()->json(['message' => 'Client deleted success'], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Client not found'], 404);
        }
    }
}
