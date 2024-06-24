<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Phone;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Rules\CpfValidationRule;

class ClientController extends Controller
{
    public function index()
    {
        $clients =  Client::orderBy('id')->get();
        return response()->json($clients, 200);
    }

    public function show(Request $request, $id)
    {
        try {
            $client = Client::with(['addresses', 'phones'])->findOrFail($id);

            $query = $client->sales()->orderBy('created_at', 'desc');

            if ($request->has('month') && $request->has('year')) {
                $month = $request->input('month');
                $year = $request->input('year');
                $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            }

            $sales = $query->get();

            if ($sales->isEmpty()) {
                return response()->json(['message' => 'No sales found for the specified month and year'], 404);
            }

            $client->sales = $sales;

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
                'name' => 'required|string|min:3',
                'cpf' => [
                    'required',
                    'string',
                    'unique:clients',
                    new CpfValidationRule(),
                ],
                'phones.*.number' => 'sometimes|required|string',
                'addresses.*.street' => 'sometimes|required|string',
                'addresses.*.city' => 'sometimes|required|string',
                'addresses.*.state' => 'sometimes|required|string',
                'addresses.*.zip' => 'sometimes|required|string',
            ]);

            $client = Client::create($validated);

            if (isset($validated['phones'])) {
                foreach ($validated['phones'] as $phoneData) {
                    $phone = new Phone($phoneData);
                    $client->phones()->save($phone);
                }
            }

            if (isset($validated['addresses'])) {
                foreach ($validated['addresses'] as $addressData) {
                    $address = new Address($addressData);
                    $client->addresses()->save($address);
                }
            }

            $client->load('addresses', 'phones', 'sales.product');

            return response()->json($client, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required',
                'cpf' => 'sometimes|required|unique:clients,cpf,' . $client->id,
                'phones.*.number' => 'sometimes|required|string',
                'addresses.*.street' => 'sometimes|required|string',
                'addresses.*.city' => 'sometimes|required|string',
                'addresses.*.state' => 'sometimes|required|string',
                'addresses.*.zip' => 'sometimes|required|string',
            ]);

            $client->update($validated);

            if (isset($validated['phones'])) {
                $client->phones()->delete();

                foreach ($validated['phones'] as $phoneData) {
                    $phone = new Phone($phoneData);
                    $client->phones()->save($phone);
                }
            }

            if (isset($validated['addresses'])) {
                $client->addresses()->delete();

                foreach ($validated['addresses'] as $addressData) {
                    $address = new Address($addressData);
                    $client->addresses()->save($address);
                }
            }

            $client->load('addresses', 'phones', 'sales.product');

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
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Client not found'], 404);
        }
    }
}
