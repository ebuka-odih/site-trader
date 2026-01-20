<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function index()
   {
       $users = User::latest()->get();
       return view('admin.user.list', compact('users'));
   }

   public function show($id)
   {
       $user = User::findOrFail($id);
       
       // Get KYC status for the user (using the same structure as KycController)
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
       
       return view('admin.user.show', compact('user', 'kycStatus'));
   }

    public function updateBalance(Request $request, $id)
    {
        $request->validate([
            'wallet_type' => 'required|in:balance,trading_balance,mining_balance,referral_balance,holding_balance,staking_balance,profit',
            'amount' => 'required|numeric|min:0',
            'action_type' => 'required|in:add,remove,reset',
        ]);

        $user = User::findOrFail($id);
        $walletType = $request->wallet_type;
        $amount = $request->amount;
        $actionType = $request->action_type;

        // Get current balance
        $currentBalance = $user->$walletType;

        // Validate that we don't go below zero when removing funds
        if ($actionType === 'remove' && $currentBalance < $amount) {
            return redirect()->back()->with('error', 'Insufficient balance. Cannot remove more than the current balance.');
        }

        // Update the balance
        if ($actionType === 'add') {
            $user->$walletType += $amount;
            $message = "Successfully added $" . number_format($amount, 2) . " to " . ucwords(str_replace('_', ' ', $walletType));
        } elseif ($actionType === 'remove') {
            $user->$walletType -= $amount;
            $message = "Successfully removed $" . number_format($amount, 2) . " from " . ucwords(str_replace('_', ' ', $walletType));
        } elseif ($actionType === 'reset') {
            $user->$walletType = $amount;
            $message = "Successfully reset " . ucwords(str_replace('_', ' ', $walletType)) . " to $" . number_format($amount, 2);
        }

        $user->save();
        
        // Debug: Log the update
        \Log::info('Admin Profit Balance Update', [
            'user_id' => $user->id,
            'wallet_type' => $walletType,
            'action_type' => $actionType,
            'amount' => $amount,
            'new_profit_balance' => $user->fresh()->profit,
            'updated_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', $message);
    }

   public function deleteUser($id)
   {
       $user = User::findOrFail($id);
       $user->delete();
       return redirect()->back()->with('success', 'User has been deleted');
   }

   public function updateStatus(Request $request, $id)
   {
       $request->validate([
           'status' => 'required|in:active,inactive,suspended',
       ]);

       $user = User::findOrFail($id);
       $user->status = $request->status;
       $user->save();

       $statusText = match($request->status) {
           'active' => 'activated',
           'inactive' => 'deactivated',
           'suspended' => 'suspended',
           default => 'updated'
       };
       
       // Send notification to user if suspended
       if ($request->status === 'suspended') {
           $user->createNotification(
               'account_suspended',
               'Account Suspended',
               'Your account has been suspended by an administrator. Please contact support for more information.',
               ['status' => 'suspended']
           );
       } elseif ($user->getOriginal('status') === 'suspended' && $request->status === 'active') {
           $user->createNotification(
               'account_reactivated',
               'Account Reactivated',
               'Your account has been reactivated. You can now access all features.',
               ['status' => 'active']
           );
       }
       
       return redirect()->back()->with('success', "User account has been {$statusText} successfully");
   }

   public function suspend($id)
   {
       $user = User::findOrFail($id);
       
       if ($user->status === 'suspended') {
           return redirect()->back()->with('error', 'User account is already suspended.');
       }
       
       $user->status = 'suspended';
       $user->save();
       
       // Send notification to user
       $user->createNotification(
           'account_suspended',
           'Account Suspended',
           'Your account has been suspended by an administrator. Please contact support for more information.',
           ['status' => 'suspended']
       );
       
       return redirect()->back()->with('success', 'User account has been suspended successfully.');
   }

   public function unsuspend($id)
   {
       $user = User::findOrFail($id);
       
       if ($user->status !== 'suspended') {
           return redirect()->back()->with('error', 'User account is not suspended.');
       }
       
       $user->status = 'active';
       $user->save();
       
       // Send notification to user
       $user->createNotification(
           'account_reactivated',
           'Account Reactivated',
           'Your account has been reactivated. You can now access all features.',
           ['status' => 'active']
       );
       
       return redirect()->back()->with('success', 'User account has been unsuspended successfully.');
   }

   public function verifyEmail($id)
   {
       $user = User::findOrFail($id);
       
       // Mark email as verified
       $user->update([
           'email_verified_at' => now(),
           'verification_code' => null,
           'verification_code_expires_at' => null,
       ]);
       
       return redirect()->back()->with('success', 'User email has been manually verified successfully.');
   }

   public function unverifyEmail($id)
   {
       $user = User::findOrFail($id);
       
       // Remove email verification
       $user->update([
           'email_verified_at' => null,
       ]);
       
       return redirect()->back()->with('success', 'User email verification has been removed.');
   }

}
