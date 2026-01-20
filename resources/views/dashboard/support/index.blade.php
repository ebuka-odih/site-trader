@extends('dashboard.new-layout')

@section('content')
<div class="space-y-8 text-white">
    <div>
        <p class="text-xs uppercase tracking-wide text-[#08f58d]">Support</p>
        <h1 class="text-3xl font-semibold tracking-tight">Get Help & Support</h1>
        <p class="text-gray-400">Contact our support team for assistance with your account.</p>
    </div>

    <!-- Support Content -->
    <div class="rounded-[32px] bg-[#050505] p-6 border border-[#0f0f0f]">
        <div class="space-y-6">
            <!-- Contact Information -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3 p-4 rounded-2xl bg-[#040404] border border-[#151515]">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-medium">Email Support</h3>
                            <p class="text-gray-400 text-sm">support@elitealgox.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-4 rounded-2xl bg-[#040404] border border-[#151515]">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-medium">Response Time</h3>
                            <p class="text-gray-400 text-sm">Within 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-[#040404] border border-[#151515]">
                        <h3 class="text-white font-medium mb-2">How do I make a deposit?</h3>
                        <p class="text-gray-400 text-sm">Go to the Transactions section and select "Deposit". Choose your preferred payment method and follow the instructions.</p>
                    </div>
                    
                    <div class="p-4 rounded-2xl bg-[#040404] border border-[#151515]">
                        <h3 class="text-white font-medium mb-2">How long does withdrawal take?</h3>
                        <p class="text-gray-400 text-sm">Withdrawals are typically processed within 24-48 hours after approval by our team.</p>
                    </div>
                    
                    <div class="p-4 rounded-2xl bg-[#040404] border border-[#151515]">
                        <h3 class="text-white font-medium mb-2">What are the trading fees?</h3>
                        <p class="text-gray-400 text-sm">Trading fees vary by plan. Check your subscription details in the dashboard for specific rates.</p>
                    </div>
                    
                    <div class="p-4 rounded-2xl bg-[#040404] border border-[#151515]">
                        <h3 class="text-white font-medium mb-2">How do I change my password?</h3>
                        <p class="text-gray-400 text-sm">Go to your profile settings and select "Change Password" to update your account security.</p>
                    </div>
                </div>
            </div>

            <!-- Live Chat Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Live Chat Support</h2>
                <p class="text-gray-400 text-sm mb-4">Chat with our support team in real-time for immediate assistance. Use the chat widget below or look for the chat button on the page.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JivoChat Live Chat Widget -->
<script src="//code.jivosite.com/widget/s6woksiOEb" async></script>
@endpush
