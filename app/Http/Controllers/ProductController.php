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
        $ownsTeam = $user->id == $team->user_id;

        if ($ownsTeam) {
            return view('products.create', ['team' => $team]);
        }
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $user = Auth::user();
        $team = $user->currentTeam;
        $ownsTeam = $user->id == $team->user_id;
        $new = $request->all();
        if ($ownsTeam) {
            Product::create($new);
            return redirect()->route('products.index')->with('message', 'new product added successfully');
        }
        return redirect()->route('products.index')->with('err-message', 'you need permission to add');
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

        // Gate::define('edit-product', function (User $user, Product $product) {
        //     return $product->team->owner->is($user);
        // });
        // Gate::authorize('edit-product', $product);
        return view('products.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
