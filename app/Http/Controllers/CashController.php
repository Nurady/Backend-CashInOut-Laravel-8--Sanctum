<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    public function index()
    {
        $debit = Auth::user()->cashes()
                        ->whereBetween('when', [ now()->firstOfMonth(), now() ])
                        ->where('amount', '>=', 0)
                        ->get('amount')
                        ->sum('amount');

        $credit = Auth::user()->cashes()
                        ->whereBetween('when', [ now()->firstOfMonth(), now() ])
                        ->where('amount', '<', 0)
                        ->get('amount')
                        ->sum('amount');

        // $balances = $debit + $credit;
        $balances = Auth::user()->cashes()->get('amount')->sum('amount');
        $transactions = Auth::user()->cashes()
                            ->whereBetween('when', [ now()->firstOfMonth(), now() ])
                            ->latest()->get();

        return response()->json([
            'debit' => formatPrice($debit),
            'credit' => formatPrice($credit),
            'balances' => formatPrice($balances),
            'transactions' => CashResource::collection($transactions)
        ]);
    }
    
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'amount' => 'required|numeric',
        ]);

        $slug = Str::slug(request('name') . '-' . Str::random(6));
        $when = request('when') ?? now();

        Auth::user()->cashes()->create([
            'name' => request('name'),
            'slug' => $slug,
            'when' => $when,
            'amount' => request('amount'),
            'description' => request('description'),
        ]);

        return response()->json([
            'message' => 'The Transaction Has Been saved',
        ]);
    }
}
