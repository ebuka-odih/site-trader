<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.package.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'min_amount' => 'required|numeric',
            'max_amount' => 'required|numeric',
            'trade_limit_per_day' => 'required',
        ]);

        Package::create($validated);
        return redirect()->back()->with('success', 'Package created successfully');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'min_amount' => 'nullable|numeric',
            'max_amount' => 'nullable|numeric',
            'trade_limit_per_day' => 'nullable',
        ]);
        $package = Package::findOrFail($id);
        $package->update($validated);
        return redirect()->back()->with('success', 'Package updated successfully');
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();
        return redirect()->back()->with('success', 'Package deleted successfully');
    }
}
