<?php

namespace App\Http\Controllers;

use App\Events\DepositSubmitted;
use App\Mail\AdminDepositNotificationMail;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DepositController extends Controller
{
    /**
     * Display the deposit page with user's deposit history
     */
     public function deposit()
    {
        $user = Auth::user();
        $wallets = PaymentMethod::where('is_active', true)->get();
        $deposits = Deposit::whereUserId(auth()->id())
                          ->with('payment_method')
                          ->latest()
                          ->get();
        
        return view('dashboard.transactions.deposit', compact('user', 'wallets', 'deposits'));
    }

    /**
     * Process a new deposit submission
     */
    public function payment(Request $request)
    {
        // Check if user account is suspended
        $user = Auth::user();
        if ($user->isSuspended()) {
            return redirect()->route('user.deposit')
                           ->with('error', 'Your account has been suspended. Please contact support for assistance.');
        }
        
        \Log::info('Deposit payment request started', [
            'user_id' => Auth::id(),
            'amount' => $request->input('amount'),
            'wallet_type' => $request->input('wallet_type'),
            'payment_method_id' => $request->input('payment_method_id'),
            'has_proof_file' => $request->hasFile('proof')
        ]);
        
        try {
        $validated = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'wallet_type' => 'required|in:balance,trading,holding,staking',
                'proof' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:10240',
            ], [
                'amount.required' => 'Please enter an amount.',
                'amount.numeric' => 'Amount must be a valid number.',
                'amount.min' => 'Amount must be at least $0.01.',
                'payment_method_id.required' => 'Please select a payment method.',
                'payment_method_id.exists' => 'Selected payment method is invalid.',
                'wallet_type.required' => 'Please select a wallet type.',
                'wallet_type.in' => 'Selected wallet type is invalid.',
                'proof.required' => 'Please upload payment proof.',
                'proof.file' => 'Payment proof must be a file.',
                'proof.mimes' => 'Payment proof must be an image (JPEG, PNG, JPG, GIF, SVG) or PDF.',
                'proof.max' => 'Payment proof file size must be less than 10MB.',
            ]);

            // Handle file upload
            $proofPath = null;
            if ($request->hasFile('proof')) {
                try {
                    $file = $request->file('proof');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $proofPath = $file->storeAs('deposits', $fileName, 'public');
                    
                    if (!$proofPath) {
                        throw new \Exception('Failed to store the uploaded file. Please try again.');
                    }
                } catch (\Exception $e) {
                    \Log::error('File upload failed: ' . $e->getMessage());
                    throw new \Exception('File upload failed: ' . $e->getMessage());
                }
            }

            // Create deposit record
            try {
                $deposit = Deposit::create([
                    'user_id' => Auth::id(),
                    'amount' => $validated['amount'],
                    'payment_method_id' => $validated['payment_method_id'],
                    'wallet_type' => $validated['wallet_type'],
                    'proof' => $proofPath,
                    'status' => 0, // Pending by default
                ]);
                
                if (!$deposit) {
                    throw new \Exception('Failed to create deposit record in database.');
                }
            } catch (\Exception $e) {
                \Log::error('Deposit creation failed: ' . $e->getMessage());
                throw new \Exception('Database error: Failed to save deposit. Please try again.');
            }

            // Create notification directly
            auth()->user()->createNotification(
                'deposit_submitted',
                'Deposit Submitted',
                "Your deposit of " . auth()->user()->formatAmount($validated['amount']) . " to your " . ucfirst($validated['wallet_type']) . " wallet has been submitted and is pending approval.",
                [
                    'amount' => $validated['amount'],
                    'wallet_type' => $validated['wallet_type'],
                    'deposit_id' => $deposit->id,
                    'status' => 'pending'
                ]
            );

            // Send email notification to admin
            try {
                $admin = User::where('role', 'admin')->first();
                if ($admin) {
                    Mail::to($admin->email)->send(new AdminDepositNotificationMail($deposit));
                }
            } catch (\Exception $e) {
                // Log email error but don't fail the deposit
                \Log::error('Failed to send admin deposit notification email: ' . $e->getMessage());
            }

            return redirect()->route('user.deposit')->with('success', 'Deposit submitted successfully! Awaiting approval.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('user.deposit')
                           ->withErrors($e->validator)
                           ->withInput()
                           ->with('error', 'Please correct the errors below.');
        } catch (\Exception $e) {
            \Log::error('Deposit creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'amount' => $request->input('amount'),
                'wallet_type' => $request->input('wallet_type'),
                'payment_method_id' => $request->input('payment_method_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'An error occurred while processing your deposit. ';
            
            // Provide more specific error messages based on the exception
            if (str_contains($e->getMessage(), 'file')) {
                $errorMessage .= 'There was an issue with the file upload. Please check your file and try again.';
            } elseif (str_contains($e->getMessage(), 'database') || str_contains($e->getMessage(), 'SQL')) {
                $errorMessage .= 'Database error occurred. Please try again or contact support.';
            } elseif (str_contains($e->getMessage(), 'mail') || str_contains($e->getMessage(), 'email')) {
                $errorMessage .= 'Deposit was created but notification email failed. Your deposit is still pending approval.';
            } else {
                // In development, show the actual error message
                if (config('app.debug')) {
                    $errorMessage .= 'Error details: ' . $e->getMessage();
                } else {
                    $errorMessage .= 'Please try again or contact support if the problem persists.';
                }
            }
            
            return redirect()->route('user.deposit')
                           ->with('error', $errorMessage)
                           ->withInput();
        }
    }

    /**
     * Cancel a pending deposit
     */
    public function cancelDeposit($id)
    {
        try {
            $deposit = Deposit::where('user_id', Auth::id())
                             ->where('id', $id)
                             ->where('status', 0) // Only pending deposits can be cancelled
                             ->firstOrFail();

            // Delete the proof file if it exists
            if ($deposit->proof && Storage::disk('public')->exists($deposit->proof)) {
                Storage::disk('public')->delete($deposit->proof);
            }

            $deposit->delete();

            return redirect()->back()->with('success', 'Deposit cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to cancel deposit. Please try again.');
        }
    }

    /**
     * View deposit proof
     */
    public function viewProof($id)
    {
        try {
            $deposit = Deposit::where('user_id', Auth::id())
                             ->where('id', $id)
                             ->firstOrFail();

            if (!$deposit->proof || !Storage::disk('public')->exists($deposit->proof)) {
                return redirect()->back()->with('error', 'Proof file not found.');
            }

            return Storage::disk('public')->response($deposit->proof);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to view proof. Please try again.');
        }
    }

    /**
     * Generate QR code for wallet address
     */
    public function generateQRCode(Request $request)
    {
                    try {
                $request->validate([
                    'address' => 'required|string|max:255'
                ]);

                $address = $request->input('address');
            
            // Generate QR code as SVG
            $qrCode = QrCode::format('svg')
                           ->size(200)
                           ->margin(1)
                           ->errorCorrection('H')
                           ->generate($address);

            // Convert QR code object to string
            $qrCodeString = (string) $qrCode;

            // Return SVG directly without storing file
            \Log::info('QR Code generated successfully', [
                'address' => $address,
                'format' => 'svg',
                'qr_code_length' => strlen($qrCodeString)
            ]);

            return response()->json([
                'success' => true,
                'qr_code_svg' => $qrCodeString
            ]);

        } catch (\Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug method to test deposit creation (temporary)
     */
    public function debugDeposit(Request $request)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        try {
            $user = Auth::user();
            $wallets = PaymentMethod::where('is_active', true)->get();
            
            return response()->json([
                'user_id' => $user->id,
                'user_balance' => $user->balance,
                'available_wallets' => $wallets->pluck('crypto_display_name', 'id'),
                'storage_disk' => config('filesystems.default'),
                'storage_path' => storage_path('app/public'),
                'deposits_table_exists' => \Schema::hasTable('deposits'),
                'deposits_columns' => \Schema::getColumnListing('deposits')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
