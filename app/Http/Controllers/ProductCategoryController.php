<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = ProductCategory::paginate(10);
        return view('pages.categories.index',compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create the product
        $product = ProductCategory::create($request->only('name'));

        return redirect()->route('product_categories.index')->with('success', 'Product created successfully.');
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
        $category = ProductCategory::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = ProductCategory::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->except('_token'));

        return redirect()->route('product_categories.index')->with('success', 'Product edited '. $category->name . ' successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        //
        $category = ProductCategory::findOrFail($id);

        $category->delete();

        return redirect()->route('product_categories.index')->with('success', 'Product deleted successfully.');
    }
}
