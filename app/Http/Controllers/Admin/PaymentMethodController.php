<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
   public function index()
   {
        $payment_method = PaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.payment-method', compact('payment_method'));
   }

   public function store(Request $request)
   {
       $validated = $request->validate([
           'crypto_type' => 'required|in:' . implode(',', array_keys(PaymentMethod::CRYPTO_TYPES)),
           'address' => 'required|string|max:255',
       ]);

       $paymentMethod = new PaymentMethod();
       $paymentMethod->crypto_type = $validated['crypto_type'];
       $paymentMethod->address = $validated['address'];
       $paymentMethod->save();

       return redirect()->back()->with('success', 'Payment Method Added Successfully');
   }

   public function update(Request $request, $id)
   {
       $validated = $request->validate([
           'crypto_type' => 'required|in:' . implode(',', array_keys(PaymentMethod::CRYPTO_TYPES)),
           'address' => 'required|string|max:255',
       ]);

       $paymentMethod = PaymentMethod::findOrFail($id);
       $paymentMethod->crypto_type = $validated['crypto_type'];
       $paymentMethod->address = $validated['address'];
       $paymentMethod->save();

       return redirect()->back()->with('success', 'Payment Method Updated Successfully');
   }

   public function destroy($id)
   {
       $paymentMethod = PaymentMethod::findOrFail($id);
       $paymentMethod->delete();
       return redirect()->back()->with('success', 'Payment Method Deleted Successfully');
   }
}
