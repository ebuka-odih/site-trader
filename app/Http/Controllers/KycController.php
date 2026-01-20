<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get actual KYC status from user data
        $kycStatus = [
            'personal_info' => [
                'full_name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
                'date_of_birth' => $user->date_of_birth ?? '',
                'nationality' => $user->nationality ?? '',
                'status' => $user->date_of_birth && $user->nationality ? 'completed' : 'pending'
            ],
            'address_info' => [
                'street_address' => $user->street_address ?? '',
                'city' => $user->city ?? '',
                'state' => $user->state ?? '',
                'postal_code' => $user->postal_code ?? '',
                'country' => $user->country ?? '',
                'status' => $user->street_address && $user->city && $user->state && $user->postal_code ? 'completed' : 'pending'
            ],
            'id_info' => [
                'id_type' => $user->id_type ?? '',
                'id_number' => $user->id_number ?? '',
                'id_front' => $user->id_front ?? '',
                'id_back' => $user->id_back ?? '',
                'selfie' => $user->selfie ?? '',
                'status' => $user->id_type && $user->id_front && $user->id_back ? 'completed' : 'pending'
            ],
            'overall_status' => 'pending'
        ];
        
        // Determine overall status
        $completedSections = 0;
        if ($kycStatus['personal_info']['status'] === 'completed') $completedSections++;
        if ($kycStatus['address_info']['status'] === 'completed') $completedSections++;
        if ($kycStatus['id_info']['status'] === 'completed') $completedSections++;
        
        if ($completedSections === 3) {
            $kycStatus['overall_status'] = 'completed';
        } elseif ($completedSections > 0) {
            $kycStatus['overall_status'] = 'in_progress';
        }
        
        return view('dashboard.kyc.index', compact('user', 'kycStatus'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_of_birth' => 'required|date',
            'nationality' => 'required|string|max:255',
            'street_address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'id_type' => 'required|string|in:passport,national_id,drivers_license',
            'id_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'id_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'selfie' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Handle file uploads
        if ($request->hasFile('id_front')) {
            $validated['id_front'] = $request->file('id_front')->store('kyc', 'public');
        }
        if ($request->hasFile('id_back')) {
            $validated['id_back'] = $request->file('id_back')->store('kyc', 'public');
        }
        if ($request->hasFile('selfie')) {
            $validated['selfie'] = $request->file('selfie')->store('kyc', 'public');
        }
        
        // Save KYC information to user model
        $user->update([
            'date_of_birth' => $validated['date_of_birth'],
            'nationality' => $validated['nationality'],
            'street_address' => $validated['street_address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
            'id_type' => $validated['id_type'],
            'id_front' => $validated['id_front'] ?? null,
            'id_back' => $validated['id_back'] ?? null,
            'selfie' => $validated['selfie'] ?? null,
        ]);
        
        return redirect()->route('user.kyc.index')->with('success', 'KYC information submitted successfully! Your application is under review.');
    }
}
