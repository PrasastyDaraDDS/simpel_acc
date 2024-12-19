<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\ProductCategoryType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $category_types = ProductCategoryType::paginate(10);
        return view('pages.category_types.index',compact('category_types'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('pages.category_types.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_category_id' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        // Create the product
        $product = ProductCategoryType::create($request->only('name','product_category_id'));

        return redirect()->route('product_category_types.index')->with('success', 'Product created successfully.');
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
        $type = ProductCategoryType::find($id);
        $categories = ProductCategory::all();
        return view('pages.category_types.edit', compact('type','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = ProductCategoryType::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|string'
        ]);

        $category->update($request->except('_token'));

        return redirect()->route('product_category_types.index')->with('success', 'Product edited '. $category->name . ' successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        //
        $category = ProductCategoryType::findOrFail($id);

        $category->delete();

        return redirect()->route('product_category_types.index')->with('success', 'Product deleted successfully.');
    }
    public function showType(ProductCategory $category){
        $product_types = ProductCategoryType::where('product_category_id',$category->id)->get();
        return response()->json($product_types);
    }
}
