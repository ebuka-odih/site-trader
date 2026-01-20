@extends('dashboard.new-layout')

@section('content')
<div class="space-y-8 text-white">
    <div class="flex flex-col gap-1">
        <p class="text-[11px] uppercase tracking-[0.3em] text-[#08f58d]">Funding</p>
        <h1 class="text-2xl font-semibold">Withdraw funds</h1>
        <p class="text-sm text-gray-400">Move money out or shift balances internally with a streamlined experience.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-[#111] bg-[#050505] p-5">
            <p class="text-xs uppercase tracking-wide text-gray-500">Main balance</p>
            <p class="text-2xl font-semibold">{{ $user->formatAmount($user->balance) }}</p>
            <p class="text-xs text-gray-500">Available for instant withdrawal.</p>
        </div>
        <div class="rounded-3xl border border-[#111] bg-[#050505] p-5">
            <p class="text-xs uppercase tracking-wide text-gray-500">Trading balance</p>
            <p class="text-2xl font-semibold">{{ $user->formatAmount($user->trading_balance ?? 0) }}</p>
            <p class="text-xs text-gray-500">Used for open positions.</p>
        </div>
        <div class="rounded-3xl border border-[#111] bg-[#050505] p-5">
            <p class="text-xs uppercase tracking-wide text-gray-500">Mining balance</p>
            <p class="text-2xl font-semibold">{{ $user->formatAmount($user->mining_balance ?? 0) }}</p>
            <p class="text-xs text-gray-500">Rewards ready to move.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="rounded-2xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-200">{{ session('success') }}</div>
    @endif

    @if(auth()->user()->isSuspended())
        <div class="rounded-[32px] bg-gradient-to-r from-red-900/20 to-red-800/20 border border-red-500/40 p-6 mb-4">
            <div class="flex items-center space-x-3">
                <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-400">Account Suspended</p>
                    <p class="text-xs text-gray-400 mt-1">Your account has been suspended. Withdrawals are disabled. Please contact support for assistance.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-5 rounded-[32px] border border-[#101010] bg-[#040404] p-6 {{ auth()->user()->isSuspended() ? 'opacity-50 pointer-events-none' : '' }}">
            <div class="space-y-1">
                <p class="text-[11px] uppercase tracking-[0.3em] text-gray-500">Regular withdrawal</p>
                <h2 class="text-xl font-semibold">Send funds to your preferred destination</h2>
                <p class="text-sm text-gray-500">Choose the account, method, and payout details. We'll review every request for safety.</p>
            </div>

            <form id="withdrawForm" action="{{ route('user.withdrawalStore') }}" method="POST" class="grid gap-4 md:grid-cols-2" {{ auth()->user()->isSuspended() ? 'onsubmit="return false;"' : '' }}>
                @csrf
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wide text-gray-400">From account</label>
                    <select id="withdrawFromAccount" name="from_account" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" required>
                        <option value="">Select account</option>
                        <option value="balance" data-balance="{{ $user->balance }}">Main balance ({{ $user->formatAmount($user->balance) }})</option>
                        <option value="trading_balance" data-balance="{{ $user->trading_balance ?? 0 }}">Trading ({{ $user->formatAmount($user->trading_balance ?? 0) }})</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Payment method</label>
                    <select id="withdrawalMethod" name="payment_method" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" required>
                        <option value="">Select method</option>
                        <option value="crypto">Cryptocurrency</option>
                        <option value="bank">Bank transfer</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <div class="md:col-span-2 grid gap-4" id="cryptoFields" style="display:none">
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">Cryptocurrency</label>
                        <select name="wallet" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]">
                            <option value="BTC">Bitcoin (BTC)</option>
                            <option value="ETH">Ethereum (ETH)</option>
                            <option value="USDT">Tether (USDT)</option>
                            <option value="SOL">Solana (SOL)</option>
                            <option value="BNB">Binance Coin (BNB)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">Wallet address</label>
                        <input type="text" name="address" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" placeholder="Enter destination address">
                    </div>
                </div>

                <div class="md:col-span-2 grid gap-4" id="bankFields" style="display:none">
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">Bank name</label>
                        <input type="text" name="bank_name" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" placeholder="e.g., Chase Bank">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">Account holder</label>
                        <input type="text" name="acct_name" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" placeholder="Account name">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">Account number / IBAN</label>
                        <input type="text" name="acct_number" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" placeholder="Enter number">
                    </div>
                </div>

                <div class="md:col-span-2" id="paypalFields" style="display:none">
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide text-gray-400">PayPal email</label>
                        <input type="email" name="paypal" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" placeholder="name@email.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Amount</label>
                    <input type="number" step="0.01" min="0.01" id="withdrawAmount" name="amount" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" placeholder="0.00" required>
                    <p class="text-xs text-gray-500">Available: $<span id="withdrawAvailableAmount">0.00</span></p>
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Notes (optional)</label>
                    <textarea name="description" rows="3" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-sm text-white focus:border-[#1fff9c]" placeholder="Add any instructions for compliance"></textarea>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" id="withdrawSubmitBtn" class="w-full rounded-2xl bg-gradient-to-r from-[#ff4d4d] to-[#f97316] px-6 py-3 text-sm font-semibold text-white transition hover:brightness-110">
                        <span id="withdrawBtnText">Submit withdrawal</span>
                        <span id="withdrawBtnSpinner" class="hidden">
                            <svg class="mr-3 inline h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-5 rounded-[32px] border border-[#101010] bg-[#040404] p-6">
            <div class="space-y-1">
                <p class="text-[11px] uppercase tracking-[0.3em] text-gray-500">Internal transfer</p>
                <h2 class="text-xl font-semibold">Move balances between wallets</h2>
                <p class="text-sm text-gray-500">Holding balances reflect asset value and can’t be moved until positions are closed.</p>
            </div>
            <form id="transferForm" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wide text-gray-400">From account</label>
                    <select id="fromAccount" name="from_account" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" required>
                        <option value="">Select account</option>
                        <option value="balance" data-balance="{{ $user->balance }}">Main ({{ $user->formatAmount($user->balance) }})</option>
                        <option value="trading_balance" data-balance="{{ $user->trading_balance ?? 0 }}">Trading ({{ $user->formatAmount($user->trading_balance ?? 0) }})</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wide text-gray-400">To account</label>
                    <select id="toAccount" name="to_account" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" required>
                        <option value="">Select account</option>
                        <option value="balance">Main</option>
                        <option value="trading_balance">Trading</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Amount</label>
                    <input type="number" id="transferAmount" name="amount" step="0.01" min="0.01" class="w-full rounded-2xl border border-[#191919] bg-[#030303] px-4 py-3 text-white focus:border-[#1fff9c]" placeholder="0.00" required>
                    <p class="text-xs text-gray-500">Available: $<span id="availableAmount">0.00</span></p>
                </div>
                <button type="submit" id="transferSubmitBtn" class="w-full rounded-2xl border border-[#1fff9c]/40 px-6 py-3 text-sm font-semibold text-[#1fff9c] hover:border-[#1fff9c]">
                    <span id="transferBtnText">Transfer funds</span>
                    <span id="transferBtnSpinner" class="hidden">
                        <svg class="mr-3 inline h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <div class="rounded-[32px] border border-[#101010] bg-[#040404] p-6 flex flex-col" style="min-height: 500px;">
        <!-- Tab Navigation -->
        <div class="flex items-center gap-4 mb-6 border-b border-[#131313]">
            <button id="withdrawalsTab" class="tab-button active px-4 py-3 text-sm font-semibold text-white border-b-2 border-[#1fff9c] transition-colors">
                Withdrawals
            </button>
            <button id="transfersTab" class="tab-button px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-300 transition-colors">
                Transfers
            </button>
        </div>

        <!-- Withdrawals Tab Content -->
        <div id="withdrawalsContent" class="tab-content flex-1 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-gray-500">History</p>
                    <h3 class="text-lg font-semibold">Recent withdrawals</h3>
                </div>
            </div>
            <div class="flex-1 overflow-x-auto overflow-y-auto">
                <table class="min-w-full divide-y divide-[#131313] text-sm">
                    <thead class="text-left text-gray-500 text-xs uppercase sticky top-0 bg-[#040404]">
                        <tr>
                            <th class="py-3 pr-6">Amount</th>
                            <th class="py-3 pr-6">Method</th>
                            <th class="py-3 pr-6">Status</th>
                            <th class="py-3 pr-6">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0f0f0f] text-gray-300">
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td class="py-3 pr-6 font-semibold">{{ $user->formatAmount($withdrawal->amount) }}</td>
                                <td class="py-3 pr-6 text-xs">
                                    {{ ucfirst($withdrawal->payment_method) }}<br>
                                    <span class="text-gray-500">{{ str_replace('_',' ', ucfirst($withdrawal->from_account)) }}</span>
                                </td>
                                <td class="py-3 pr-6">{!! $withdrawal->status_badge !!}</td>
                                <td class="py-3 pr-6 text-xs text-gray-500">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 text-xs">No withdrawals yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transfers Tab Content -->
        <div id="transfersContent" class="tab-content hidden flex-1 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-gray-500">Internal history</p>
                    <h3 class="text-lg font-semibold">Recent transfers</h3>
                </div>
            </div>
            <div class="flex-1 overflow-x-auto overflow-y-auto">
                <table class="min-w-full divide-y divide-[#131313] text-sm">
                    <thead class="text-left text-gray-500 text-xs uppercase sticky top-0 bg-[#040404]">
                        <tr>
                            <th class="py-3 pr-6">Amount</th>
                            <th class="py-3 pr-6">Route</th>
                            <th class="py-3 pr-6">Status</th>
                            <th class="py-3 pr-6">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0f0f0f] text-gray-300">
                        @forelse($transfers as $transfer)
                            <tr>
                                <td class="py-3 pr-6 font-semibold">{{ $user->formatAmount($transfer->amount) }}</td>
                                <td class="py-3 pr-6 text-xs">{{ str_replace('_',' ', ucfirst($transfer->from_account)) }} → {{ str_replace('_',' ', ucfirst($transfer->to_account)) }}</td>
                                <td class="py-3 pr-6"><span class="rounded-full bg-[#071c11] px-3 py-1 text-xs text-[#1fff9c]">{{ ucfirst($transfer->status) }}</span></td>
                                <td class="py-3 pr-6 text-xs text-gray-500">{{ $transfer->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 text-xs">No transfers recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tax Regulations Modal -->
<div id="taxRegulationsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 px-4 py-4">
    <div class="w-full max-w-lg rounded-2xl border border-[#171717] bg-[#050505] p-6 max-h-[90vh] overflow-y-auto sm:max-h-none sm:overflow-visible">
        <h3 class="text-center text-xl font-bold text-white mb-4">Tax Regulations Notice</h3>
        
        <p class="text-sm text-white mb-4 leading-relaxed">
            Cryptocurrency withdrawals may have tax implications depending on your jurisdiction. We recommend:
        </p>

        <div class="mb-4">
            <p class="text-sm font-bold text-white mb-2">Before proceeding:</p>
            <ul class="space-y-1.5 text-sm text-white list-disc list-inside ml-3">
                <li>Review your local tax laws and regulations</li>
                <li>Consult with a tax professional if needed</li>
                <li>Keep detailed records of all transactions</li>
            </ul>
        </div>

        <div class="rounded-xl border border-[#1a1a1a] bg-[#0a0a0a] p-4 mb-4">
            <p class="text-sm text-white">
                Questions? Our live support team is here to help with personalized guidance.
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <button id="taxModalProceedBtn" class="w-full rounded-2xl bg-gradient-to-r from-[#08f58d] to-[#1fff9c] px-6 py-3 text-sm font-semibold text-black transition hover:brightness-110">
                I Understand, Proceed
            </button>
            <button id="taxModalCancelBtn" class="w-full rounded-2xl border border-[#2a2a2a] bg-[#1a1a1a] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#252525]">
                Cancel Withdrawal
            </button>
        </div>
    </div>
</div>

<div id="customModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-4">
    <div class="w-full max-w-md rounded-2xl border border-[#171717] bg-[#050505] p-6">
        <div class="flex items-center justify-between">
            <h3 id="modalTitle" class="text-lg font-semibold">Notice</h3>
            <button id="closeModal" class="text-gray-500 hover:text-white">&times;</button>
        </div>
        <p id="modalMessage" class="mt-4 text-sm text-gray-300"></p>
        <div class="mt-6 flex justify-end gap-3 text-sm">
            <button id="modalCancelBtn" class="rounded-full border border-gray-600 px-4 py-2 text-gray-400">Cancel</button>
            <button id="modalConfirmBtn" class="rounded-full bg-[#1fff9c] px-4 py-2 text-black font-semibold">OK</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const modal = document.getElementById('customModal');
const modalTitle = document.getElementById('modalTitle');
const modalMessage = document.getElementById('modalMessage');
const taxModal = document.getElementById('taxRegulationsModal');

function showModal(title, message, showCancel = false) {
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    document.getElementById('modalCancelBtn').style.display = showCancel ? 'inline-flex' : 'none';
    modal.classList.remove('hidden');
}
function hideModal() { modal.classList.add('hidden'); }
['closeModal','modalCancelBtn','modalConfirmBtn'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', hideModal);
});
if (modal) {
    modal.addEventListener('click', (e) => { if (e.target === modal) hideModal(); });
}

// Tax Regulations Modal functions
function showTaxModal() {
    if (taxModal) {
        taxModal.classList.remove('hidden');
    }
}

function hideTaxModal() {
    if (taxModal) {
        taxModal.classList.add('hidden');
    }
}

// Store the form submission handler
let pendingWithdrawalSubmission = null;

// Tax modal event listeners
const taxModalProceedBtn = document.getElementById('taxModalProceedBtn');
const taxModalCancelBtn = document.getElementById('taxModalCancelBtn');

if (taxModalProceedBtn) {
    taxModalProceedBtn.addEventListener('click', function() {
        hideTaxModal();
        // Proceed with the withdrawal
        if (pendingWithdrawalSubmission) {
            pendingWithdrawalSubmission();
            pendingWithdrawalSubmission = null;
        }
    });
}

if (taxModalCancelBtn) {
    taxModalCancelBtn.addEventListener('click', function() {
        hideTaxModal();
        setButtonProcessing(false, 'withdraw');
        pendingWithdrawalSubmission = null;
    });
}

// Close tax modal when clicking outside
if (taxModal) {
    taxModal.addEventListener('click', (e) => {
        if (e.target === taxModal) {
            hideTaxModal();
            setButtonProcessing(false, 'withdraw');
            pendingWithdrawalSubmission = null;
        }
    });
}

const withdrawMethod = document.getElementById('withdrawalMethod');
const cryptoFields = document.getElementById('cryptoFields');
const bankFields = document.getElementById('bankFields');
const paypalFields = document.getElementById('paypalFields');
const withdrawAccountSelect = document.getElementById('withdrawFromAccount');
const transferFromSelect = document.getElementById('fromAccount');
const transferForm = document.getElementById('transferForm');
const withdrawForm = document.getElementById('withdrawForm');

const toggleMethodFields = () => {
    if (!withdrawMethod) return;
    const value = withdrawMethod.value;
    cryptoFields.style.display = value === 'crypto' ? 'grid' : 'none';
    bankFields.style.display = value === 'bank' ? 'grid' : 'none';
    paypalFields.style.display = value === 'paypal' ? 'block' : 'none';
};
if (withdrawMethod) {
    withdrawMethod.addEventListener('change', toggleMethodFields);
}

function updateAvailableAmount() {
    if (!transferFromSelect) return;
    const selected = transferFromSelect.options[transferFromSelect.selectedIndex];
    document.getElementById('availableAmount').textContent = parseFloat(selected?.dataset.balance || 0).toFixed(2);
}

function updateToAccountOptions() {
    const toAccountSelect = document.getElementById('toAccount');
    if (!toAccountSelect || !transferFromSelect) return;
    
    const fromAccount = transferFromSelect.value;
    const currentToValue = toAccountSelect.value;
    
    // Clear existing options except the first one
    toAccountSelect.innerHTML = '<option value="">Select account</option>';
    
    // For all accounts, show both Main and Trading options
    const mainOption = document.createElement('option');
    mainOption.value = 'balance';
    mainOption.textContent = 'Main';
    toAccountSelect.appendChild(mainOption);
    
    const tradingOption = document.createElement('option');
    tradingOption.value = 'trading_balance';
    tradingOption.textContent = 'Trading';
    toAccountSelect.appendChild(tradingOption);
    
    // Restore previous selection if it's still valid
    if (currentToValue && (currentToValue === 'balance' || currentToValue === 'trading_balance')) {
        toAccountSelect.value = currentToValue;
    }
}

function updateWithdrawAvailableAmount() {
    if (!withdrawAccountSelect) return;
    const selected = withdrawAccountSelect.options[withdrawAccountSelect.selectedIndex];
    document.getElementById('withdrawAvailableAmount').textContent = parseFloat(selected?.dataset.balance || 0).toFixed(2);
}
if (transferFromSelect) {
    transferFromSelect.addEventListener('change', function() {
        updateAvailableAmount();
        updateToAccountOptions();
    });
}
if (withdrawAccountSelect) withdrawAccountSelect.addEventListener('change', updateWithdrawAvailableAmount);

document.addEventListener('DOMContentLoaded', () => {
    // Tab switching functionality
    const withdrawalsTab = document.getElementById('withdrawalsTab');
    const transfersTab = document.getElementById('transfersTab');
    const withdrawalsContent = document.getElementById('withdrawalsContent');
    const transfersContent = document.getElementById('transfersContent');

    function switchTab(activeTab, activeContent, inactiveTab, inactiveContent) {
        // Update tab buttons
        activeTab.classList.add('active', 'text-white', 'border-[#1fff9c]');
        activeTab.classList.remove('text-gray-500', 'border-transparent');
        inactiveTab.classList.remove('active', 'text-white', 'border-[#1fff9c]');
        inactiveTab.classList.add('text-gray-500', 'border-transparent');
        
        // Update content visibility
        activeContent.classList.remove('hidden');
        activeContent.classList.add('flex');
        inactiveContent.classList.add('hidden');
        inactiveContent.classList.remove('flex');
    }

    if (withdrawalsTab && transfersTab) {
        withdrawalsTab.addEventListener('click', () => {
            switchTab(withdrawalsTab, withdrawalsContent, transfersTab, transfersContent);
        });

        transfersTab.addEventListener('click', () => {
            switchTab(transfersTab, transfersContent, withdrawalsTab, withdrawalsContent);
        });
    }

    // Initialize on page load
    updateAvailableAmount();
    updateWithdrawAvailableAmount();
    updateToAccountOptions();
    const validator = (form) => {
        const fromAccount = form.querySelector('[name="from_account"]').value;
        const method = form.querySelector('[name="payment_method"]').value;
        const amount = parseFloat(form.querySelector('[name="amount"]').value || 0);
        if (!fromAccount) throw new Error('Select an account to withdraw from.');
        if (!method) throw new Error('Select a payment method.');
        if (!amount || amount <= 0) throw new Error('Enter a valid amount.');
        if (method === 'crypto') {
            const wallet = form.querySelector('[name="wallet"]').value;
            const address = form.querySelector('[name="address"]').value;
            if (!wallet || !address) throw new Error('Fill in cryptocurrency details.');
        }
        if (method === 'bank') {
            const bankName = form.querySelector('[name="bank_name"]').value;
            const acctName = form.querySelector('[name="acct_name"]').value;
            const acctNumber = form.querySelector('[name="acct_number"]').value;
            if (!bankName || !acctName || !acctNumber) throw new Error('Provide complete bank information.');
        }
        if (method === 'paypal') {
            const paypal = form.querySelector('[name="paypal"]').value;
            if (!paypal || !paypal.includes('@')) throw new Error('Enter a valid PayPal email.');
        }
    };

    if (withdrawForm) {
        withdrawForm.addEventListener('submit', function(e) {
            e.preventDefault();
            try {
                validator(this);
            } catch (err) {
                showModal('Validation error', err.message);
                return;
            }
            
            // Store the withdrawal processing function
            pendingWithdrawalSubmission = function() {
                setButtonProcessing(true, 'withdraw');
                checkAuthentication()
                    .then(isAuth => {
                        if (!isAuth) throw new Error('Session expired. Please login again.');
                        return processWithdrawal();
                    })
                    .catch(error => {
                        setButtonProcessing(false, 'withdraw');
                        showModal('Error', error.message || 'Unable to submit request.');
                        pendingWithdrawalSubmission = null;
                    });
            };
            
            // Show tax regulations modal first
            showTaxModal();
        });
    }

    if (transferForm) {
        transferForm.addEventListener('submit', function(e) {
            e.preventDefault();
            setButtonProcessing(true, 'transfer');
            const formData = new FormData(this);
            fetch('{{ route('user.transfer.funds') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                setButtonProcessing(false, 'transfer');
                if (data.success) {
                    showModal('Success', 'Transfer completed successfully!');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showModal('Error', data.message || 'Transfer failed.');
                }
            })
            .catch(() => {
                setButtonProcessing(false, 'transfer');
                showModal('Error', 'Unable to process transfer right now.');
            });
        });
    }
});

function setButtonProcessing(isProcessing, type) {
    const button = document.getElementById(type + 'SubmitBtn');
    const text = document.getElementById(type + 'BtnText');
    const spinner = document.getElementById(type + 'BtnSpinner');
    if (!button) return;
    button.disabled = isProcessing;
    button.classList.toggle('opacity-60', isProcessing);
    if (text) text.classList.toggle('hidden', isProcessing);
    if (spinner) spinner.classList.toggle('hidden', !isProcessing);
}

function checkAuthentication() {
    return fetch('{{ route('user.debug.simple') }}', { headers: { 'Accept': 'application/json' }})
        .then(response => {
            if (response.redirected || response.url.includes('login')) throw new Error('Session expired. Please refresh.');
            return response.ok;
        });
}

function processWithdrawal() {
    const form = document.getElementById('withdrawForm');
    const formData = new FormData(form);
    return fetch('{{ route('user.withdrawalStore') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        if (!response.ok) throw new Error(`Request failed (${response.status})`);
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) throw new Error('Server returned an invalid response.');
        return response.json();
    })
    .then(data => {
        setButtonProcessing(false, 'withdraw');
        if (data.success) {
            showModal('Success', 'Withdrawal request submitted successfully!');
            setTimeout(() => location.reload(), 1500);
        } else {
            showModal('Error', data.message || 'Unable to process withdrawal.');
        }
    })
    .catch(error => {
        setButtonProcessing(false, 'withdraw');
        showModal('Error', error.message || 'Unexpected error occurred.');
    });
}
</script>
@endpush
