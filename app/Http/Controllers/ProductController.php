<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function getProducts(Request $request)
    {
        // Fetch paginated products with suppliers
        $products = Product::with(['image'])->paginate(10);
        // $products->getCollection()->transform(function ($product) {
        //     return [
        //         'id' => $product->id,
        //         'product' => [
        //             'img' => $product->getImageUrlAttribute(), // Use the accessor to get the image URL
        //             'title' => $product->name,
        //             'category' => $product->categories->pluck('name')->implode(', '), // Assuming categories are related
        //         ],
        //         'stock' => $product->stock, // Assuming you have a stock attribute
        //         'price' => $product->sell_price,
        //         'orders' => $product->orders()->count(), // Count of orders for this product
        //         'rating' => $product->rating, // Assuming you have a rating attribute
        //         'published' => [
        //             'publishDate' => $product->created_at->format('d M, Y'),
        //             'publishTime' => $product->created_at->format('h:i A'),
        //         ],
        //     ];
        // });

        // Return JSON response
        return response()->json($products);
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
            // 'supplier_id' => 'required|exists:participants,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        // Create the product
        $product = Product::create($request->only('name', 'buying_price', 'sell_price'));

        // Handle the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Store the image in the public disk
            $product->image()->create(['url' => $imagePath]); // Create the image record
        }

        return redirect()->route('products')->with('success', 'Product created successfully.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
