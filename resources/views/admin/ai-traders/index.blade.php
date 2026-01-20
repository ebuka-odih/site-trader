@extends('admin.layouts.app')

@section('title', 'AI Traders')

@section('content')
<div class="p-4">
    <div class="max-w-7xl mx-auto p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Stock Traders</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage AI-powered stock trading bots with advanced machine learning</p>
            </div>
            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create AI Trader
            </button>
        </div>

        <!-- Traders Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-auto">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">AI Trader</th>
                            <th scope="col" class="px-6 py-3">AI Model</th>
                            <th scope="col" class="px-6 py-3">Strategy</th>
                            <th scope="col" class="px-6 py-3">Stocks</th>
                            <th scope="col" class="px-6 py-3">Performance</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($traders as $trader)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $trader->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $trader->aiTraderPlan ? $trader->aiTraderPlan->name : 'No Plan' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">
                                    {{ $trader->ai_model }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    {{ ucfirst($trader->trading_strategy) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">{{ $trader->stocks_to_trade_list }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium {{ $trader->current_performance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $trader->formatted_performance }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button onclick="toggleStatus({{ $trader->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trader->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $trader->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="viewTrader({{ $trader->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="editTrader({{ $trader->id }})" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteTrader({{ $trader->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No AI Traders found. <button onclick="openCreateModal()" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Create your first AI trader</button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($traders->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $traders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create/Edit Trader Modal -->
<div id="traderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800 max-h-screen overflow-y-auto max-w-4xl">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-white">Create AI Stock Trader</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="traderForm" class="space-y-6">
                @csrf
                <input type="hidden" id="traderId" name="id">
                
                <!-- Basic Information -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">🤖 AI Trader Configuration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="ai_trader_plan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Plan</label>
                            <select id="ai_trader_plan_id" name="ai_trader_plan_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select a plan</option>
                                @foreach(\App\Models\AiTraderPlan::where('is_active', true)->get() as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AI Trader Name</label>
                            <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
                
                <!-- AI Configuration -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">🤖 AI Configuration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="ai_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AI Model</label>
                            <select id="ai_model" name="ai_model" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="GPT-4o-Trader">GPT-4o Trader</option>
                                <option value="GPT-4-Trader">GPT-4 Trader</option>
                                <option value="GPT-4-Turbo-Trader">GPT-4 Turbo Trader</option>
                                <option value="Claude-3.5-Sonnet-Trader">Claude 3.5 Sonnet Trader</option>
                                <option value="Claude-3-Opus-Trader">Claude 3 Opus Trader</option>
                                <option value="Gemini-Pro-Trader">Gemini Pro Trader</option>
                                <option value="Gemini-Ultra-Trader">Gemini Ultra Trader</option>
                                <option value="Llama-3.1-405B-Trader">Llama 3.1 405B Trader</option>
                                <option value="Mixtral-8x22B-Trader">Mixtral 8x22B Trader</option>
                                <option value="Alpha-Trader-X1">Alpha Trader X1</option>
                                <option value="Quantum-Trader-Pro">Quantum Trader Pro</option>
                                <option value="Neural-Trader-Elite">Neural Trader Elite</option>
                                <option value="Cyber-Trader-Max">Cyber Trader Max</option>
                                <option value="Phoenix-Trader-AI">Phoenix Trader AI</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="ai_confidence" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AI Confidence Level</label>
                            <select id="ai_confidence" name="ai_confidence" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="low">Low (0.3-0.5)</option>
                                <option value="medium" selected>Medium (0.5-0.7)</option>
                                <option value="high">High (0.7-0.9)</option>
                                <option value="maximum">Maximum (0.9-1.0)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="ai_learning_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AI Learning Mode</label>
                            <select id="ai_learning_mode" name="ai_learning_mode" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="conservative">Conservative</option>
                                <option value="adaptive" selected>Adaptive</option>
                                <option value="aggressive">Aggressive</option>
                                <option value="experimental">Experimental</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Trading Configuration -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">📈 Trading Configuration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="trading_strategy" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trading Strategy</label>
                            <select id="trading_strategy" name="trading_strategy" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="conservative">Conservative</option>
                                <option value="moderate">Moderate</option>
                                <option value="aggressive">Aggressive</option>
                                <option value="scalping">Scalping</option>
                                <option value="swing">Swing</option>
                                <option value="day_trading">Day Trading</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="stocks_to_trade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stocks to Trade (comma-separated)</label>
                            <input type="text" id="stocks_to_trade" name="stocks_to_trade" placeholder="AAPL, GOOGL, MSFT, TSLA" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
                
                
                
                <!-- Trading Settings -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">⚙️ Trading Settings</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="max_positions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Positions</label>
                            <input type="number" id="max_positions" name="max_positions" min="1" max="50" value="5" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="position_size_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Position Size (%)</label>
                            <input type="number" id="position_size_percentage" name="position_size_percentage" step="0.1" min="1" max="100" value="20.0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
                
                <!-- Risk Management -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">🛡️ Risk Management</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="risk_tolerance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Risk Tolerance (0.0 - 1.0)</label>
                            <input type="number" id="risk_tolerance" name="risk_tolerance" step="0.1" min="0" max="1" value="0.5" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="stop_loss_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stop Loss (%)</label>
                            <input type="number" id="stop_loss_percentage" name="stop_loss_percentage" step="0.1" min="0" max="100" value="5.0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="take_profit_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Take Profit (%)</label>
                            <input type="number" id="take_profit_percentage" name="take_profit_percentage" step="0.1" min="0" max="100" value="10.0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        <span id="submitText">Create AI Trader</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Trader Modal -->
<div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-2xl">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">AI Trader Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="traderDetails" class="space-y-4">
                <!-- Trader details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let isEditMode = false;

function openCreateModal() {
    isEditMode = false;
    document.getElementById('modalTitle').textContent = 'Create AI Stock Trader';
    document.getElementById('submitText').textContent = 'Create AI Trader';
    document.getElementById('traderForm').reset();
    document.getElementById('traderId').value = '';
    document.getElementById('traderModal').classList.remove('hidden');
}

function editTrader(id) {
    isEditMode = true;
    document.getElementById('modalTitle').textContent = 'Edit AI Stock Trader';
    document.getElementById('submitText').textContent = 'Update AI Trader';
    
    // Fetch trader data and populate form
    fetch(`/admin/ai-traders/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const trader = data.trader;
                document.getElementById('traderId').value = trader.id;
                document.getElementById('ai_trader_plan_id').value = trader.ai_trader_plan_id;
                document.getElementById('name').value = trader.name;
                document.getElementById('ai_model').value = trader.ai_model;
                document.getElementById('ai_confidence').value = trader.ai_confidence;
                document.getElementById('ai_learning_mode').value = trader.ai_learning_mode;
                document.getElementById('trading_strategy').value = trader.trading_strategy;
                document.getElementById('stocks_to_trade').value = trader.stocks_to_trade ? trader.stocks_to_trade.join(', ') : '';
                document.getElementById('risk_tolerance').value = trader.risk_tolerance;
                document.getElementById('stop_loss_percentage').value = trader.stop_loss_percentage;
                document.getElementById('take_profit_percentage').value = trader.take_profit_percentage;
                document.getElementById('max_positions').value = trader.max_positions;
                document.getElementById('position_size_percentage').value = trader.position_size_percentage;
                
                
                document.getElementById('traderModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading trader data');
        });
}

function viewTrader(id) {
    fetch(`/admin/ai-traders/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const trader = data.trader;
                const detailsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <p class="text-gray-900 dark:text-white">${trader.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan</label>
                            <p class="text-gray-900 dark:text-white">${trader.ai_trader_plan ? trader.ai_trader_plan.name : 'N/A'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">AI Model</label>
                            <p class="text-gray-900 dark:text-white">${trader.ai_model}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">AI Confidence</label>
                            <p class="text-gray-900 dark:text-white">${trader.ai_confidence}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">AI Learning Mode</label>
                            <p class="text-gray-900 dark:text-white">${trader.ai_learning_mode}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trading Strategy</label>
                            <p class="text-gray-900 dark:text-white">${trader.trading_strategy}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stocks to Trade</label>
                            <p class="text-gray-900 dark:text-white">${trader.stocks_to_trade ? trader.stocks_to_trade.join(', ') : 'Not specified'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Positions</label>
                            <p class="text-gray-900 dark:text-white">${trader.max_positions}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position Size</label>
                            <p class="text-gray-900 dark:text-white">${trader.position_size_percentage}%</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Performance</label>
                            <p class="text-gray-900 dark:text-white">${trader.formatted_performance}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <p class="text-gray-900 dark:text-white">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${trader.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'}">
                                    ${trader.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Risk Management</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                            <div>
                                <span class="text-sm text-gray-500">Risk Tolerance:</span>
                                <span class="text-sm font-medium">${trader.risk_tolerance}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Stop Loss:</span>
                                <span class="text-sm font-medium">${trader.stop_loss_percentage}%</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Take Profit:</span>
                                <span class="text-sm font-medium">${trader.take_profit_percentage}%</span>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('traderDetails').innerHTML = detailsHtml;
                document.getElementById('viewModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading trader data');
        });
}

function closeModal() {
    document.getElementById('traderModal').classList.add('hidden');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function toggleStatus(id) {
    if (confirm('Are you sure you want to toggle the status of this AI trader?')) {
        fetch(`/admin/ai-traders/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating trader status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating trader status');
        });
    }
}

function deleteTrader(id) {
    if (confirm('Are you sure you want to delete this AI trader? This action cannot be undone.')) {
        fetch(`/admin/ai-traders/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting trader');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting trader');
        });
    }
}

// Form submission
document.getElementById('traderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Convert stocks to trade to array
    if (data.stocks_to_trade) {
        data.stocks_to_trade = data.stocks_to_trade.split(',').map(item => item.trim()).filter(item => item);
    }
    
    const url = isEditMode ? `/admin/ai-traders/${data.id}` : '/admin/ai-traders';
    const method = isEditMode ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            location.reload();
        } else {
            alert(data.message || 'Error saving trader');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving trader');
    });
});
</script>
@endpush