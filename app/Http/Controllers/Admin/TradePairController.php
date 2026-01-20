<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradePair;
use Illuminate\Http\Request;

class TradePairController extends Controller
{
    public function index()
    {
       $pairs = TradePair::latest()->get();
       return view('admin.trade.pairs', compact('pairs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'pair' => 'required',
        ]);

        $pair = new TradePair();
        $pair->type = $validated['type'];
        $pair->pair = $validated['pair'];
        $pair->save();
        return redirect()->back()->with('success', "Pair Created Successfully");
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required',
            'pair' => 'required',
        ]);

        $pair = TradePair::findOrFail($id);
        $pair->type = $validated['type'];
        $pair->pair = $validated['pair'];
        $pair->save();
        return redirect()->back()->with('success', "Pair Updated Successfully");
    }

    public function destroy($id)
    {
        $pair = TradePair::findOrFail($id);
        $pair->delete();
        return redirect()->back()->with('success', "Pair Deleted Successfully");
    }

    public function getByMarketType($marketType)
    {
        $pairs = TradePair::where('type', $marketType)->get(['id', 'pair']);
        return response()->json($pairs);
    }
}
