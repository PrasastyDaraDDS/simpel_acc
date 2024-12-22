<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $stores = Participant::where('participant_role_id',1)->get();

        return view('pages.stores.index',compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.stores.create');
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
        $product = Participant::create($request->only('name', 'address', 'link') + ['participant_role_id' => 1]);

        return redirect()->route('stores.index')->with('success', 'Toko created successfully.');
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
        $store = Participant::find($id);

        return view('pages.stores.edit',compact('store'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $store = Participant::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'string',
            'link' => 'string',
            'participant_role_id' => 'numeric', // Validate image
        ]);

        // Create the product
        $store->update($request->except('_token'));

        return redirect()->route('stores.index')->with('success', 'Toko edited '. $store->name . ' successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $store = Participant::findOrFail($id);

        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Toko deleted successfully.');
    }
}
