<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Transaction;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $user;
    public $team_id;
    public function __construct()
    {
        $this->user = Auth::user();
        $this->team_id = $this->user->currentTeam->id;
    }
    public function index()
    {

        return view('sales.index');
    }
    public function receipt(Request $request)
    {

        $id = $request['receipt'];

        $transaction = Transaction::where('team_id', $this->team_id)->where('id', $id)->first();
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at);
        $formattedDate = $date->format('d-M-y gA');
        $formattedDate = str_replace('AM', 'am', str_replace('PM', 'pm', $formattedDate));

        $sales = Sale::where('team_id', $this->team_id)
            ->where('transaction_id', $transaction->id)->get();
        return view('sales.receipt', ['transaction' => $transaction, 'sales' => $sales, 'formattedDate' => $formattedDate]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
