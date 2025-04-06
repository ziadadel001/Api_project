<?php

namespace App\Http\Controllers\dashboard;


use App\Http\Controllers\Controller;
use App\Http\Requests\products\StoreProductRequest;
use App\Http\Requests\products\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class productsController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view product', only: ['index']),
            new Middleware('permission:edit product', only: ['edit', 'update']),
            new Middleware('permission:create product', only: ['create']),
            new Middleware('permission:delete product', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Start with a base query
        $query = Product::query();

        // Modify the query based on the user's role
        if (auth()->user()->hasRole('vendor')) {
            $query = auth()->user()->products();
        } elseif (auth()->user()->hasRole('admin', 'super admin')) {
            $query->with('vendor'); // Eager load vendor for admins
        }

        // Always paginate the final query
        $products = $query->latest()->paginate(5);
        return view('products.list', compact('products'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('product.index')->with('success', 'Product Added successfully.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $Product = Product::findOrFail($id);
        $Product->update($request->validated());
        return redirect()->route('product.index')->with('success', 'product Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);

        if ($product == null) {
            session()->flash('error', 'product not found');
            return response()->json([
                'status' => false
            ]);
        }

        $product->delete();
        session()->flash('success', 'product deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
