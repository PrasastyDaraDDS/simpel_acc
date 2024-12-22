<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $customers = Participant::where('participant_role_id',2)->get();

        return view('pages.customers.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'string|nullable',
            'link' => 'string|nullable',
            // Remove participant_role_id from validation since we will set it manually
        ]);

        // Create the product with participant_role_id set to 1
        $product = Participant::create($request->only('name', 'address', 'link') + ['participant_role_id' => 2]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Participant::find($id);

        return view('pages.customers.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Participant::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'string',
            'link' => 'string',
            'participant_role_id' => 'numeric', // Validate image
        ]);

        // Create the product
        $customer->update($request->except('_token'));

        return redirect()->route('customers.index')->with('success', 'Customer edited '. $customer->name . ' successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $customer = Participant::findOrFail($id);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
