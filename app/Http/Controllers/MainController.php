<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }
    public function instocks()
    {
        $teamId = Auth::user()->currentTeam->id;
        $products = Product::select('name', 'quantity')->where('team_id', $teamId)->paginate(5);

        return view('instocks', ['products' => $products]);
    }
}
