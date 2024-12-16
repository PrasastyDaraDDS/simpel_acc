<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::paginate(10);
        return view('pages.products.index-product',compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::all();
        return view('pages.products.create-product',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'buying_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        // Create the product
        $product = Product::create($request->only('name', 'buying_price', 'sell_price'));

        // Handle the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Store the image in the public disk
            $product->image()->create(['url' => $imagePath]); // Create the image record
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
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
    public function edit(Product $product)
    {
        return view('pages.products.edit-product', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'buying_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $product->update($request->except('_token','image'));

        if ($request->hasFile('image')) {
            Storage::delete('storage/' . $product->image->url);
            $product->image->delete();
            $imagePath = $request->file('image')->store('images', 'public'); // Store the image in the public disk
            $product->image()->create(['url' => $imagePath]); // Create the image record
        }
        return redirect()->route('products.index')->with('success', 'Product edited '. $product->name . ' successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        //
        $product = Product::with('image')->findOrFail($id);

        // Check if the product has an associated image
        if ($product->image) {
            // Delete the image file from storage
            Storage::disk('public')->delete($product->image->url); // Adjust the disk if necessary
            // Delete the image record from the database
            $product->image()->delete();
        }

        // Delete the product
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
