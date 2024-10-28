<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Http\Requests\ProductRequest\StoreProductRequest;
use App\Http\Requests\ProductRequest\UpdateProductRequest;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $team = Auth::user()->currentTeam;

        return view('products.index', ['team' => $team]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $team = $user->currentTeam;
        // $ownsTeam = $user->id == $team->user_id;
        if (Gate::denies('create-product', $team)) {
            abort(403);
        };

        return view('products.create', ['team' => $team]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $new = $request->all();
        $user = Auth::user();
        $team = $user->currentTeam;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $new['photo'] = $photoPath;
        };
        Gate::authorize('create-product', $team);

        Product::create($new);
        return redirect()->route('products.index')->with('message', 'new product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

        Gate::authorize('edit-product', $product);
        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('edit-product', $product);

        $new = $request->all();
        $product->update($new);
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('message', 'Delete Succcess');
    }
}
