<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BotTemplateController extends Controller
{
    public function index()
    {
        $templates = BotTemplate::latest()->paginate(15);
        $stats = [
            'total' => BotTemplate::count(),
            'active' => BotTemplate::where('is_active', true)->count(),
            'inactive' => BotTemplate::where('is_active', false)->count(),
        ];

        return view('admin.bot-templates.index', compact('templates', 'stats'));
    }

    public function create()
    {
        $formData = $this->formData();

        return view('admin.bot-templates.create', $formData);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $payload = $this->preparePayload($request, $validated);
        $payload['created_by'] = Auth::id();

        BotTemplate::create($payload);

        return redirect()
            ->route('admin.bot-templates.index')
            ->with('success', 'Bot template created successfully.');
    }

    public function show(BotTemplate $botTemplate)
    {
        return redirect()->route('admin.bot-templates.edit', $botTemplate);
    }

    public function edit(BotTemplate $botTemplate)
    {
        $formData = $this->formData($botTemplate);

        return view('admin.bot-templates.edit', $formData);
    }

    public function update(Request $request, BotTemplate $botTemplate)
    {
        $validated = $this->validateData($request);
        $payload = $this->preparePayload($request, $validated);

        $botTemplate->update($payload);

        return redirect()
            ->route('admin.bot-templates.index')
            ->with('success', 'Bot template updated.');
    }

    public function destroy(BotTemplate $botTemplate)
    {
        $botTemplate->delete();

        return redirect()
            ->route('admin.bot-templates.index')
            ->with('success', 'Template removed.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trading_type' => 'required|string|in:crypto,forex',
            'base_asset' => 'required|string|max:10',
            'quote_asset' => 'required|string|max:10',
            'strategy' => 'required|string|in:grid,dca,scalping,trend_following',
            'leverage' => 'required|numeric|min:1|max:100',
            'trade_duration' => 'nullable|string|max:10',
            'target_yield_percentage' => 'nullable|numeric|min:0|max:1000',
            'max_investment' => 'required|numeric|min:10|max:1000000',
            'daily_loss_limit' => 'nullable|numeric|min:0',
            'stop_loss_percentage' => 'nullable|numeric|min:0|max:100',
            'take_profit_percentage' => 'nullable|numeric|min:0|max:1000',
            'min_trade_amount' => 'required|numeric|min:1',
            'max_trade_amount' => 'required|numeric|min:1|gte:min_trade_amount',
            'max_open_trades' => 'required|integer|min:1|max:50',
            'strategy_config' => 'nullable|json',
            'metadata' => 'nullable|json',
        ]);
    }

    protected function preparePayload(Request $request, array $validated): array
    {
        $payload = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'trading_type' => $validated['trading_type'],
            'base_asset' => strtoupper($validated['base_asset']),
            'quote_asset' => strtoupper($validated['quote_asset']),
            'strategy' => $validated['strategy'],
            'leverage' => $validated['leverage'],
            'trade_duration' => $validated['trade_duration'] ?? null,
            'target_yield_percentage' => $validated['target_yield_percentage'] ?? null,
            'auto_close' => $request->boolean('auto_close', true),
            'strategy_config' => isset($validated['strategy_config'])
                ? json_decode($validated['strategy_config'], true)
                : null,
            'max_investment' => $validated['max_investment'],
            'daily_loss_limit' => $validated['daily_loss_limit'] ?? null,
            'stop_loss_percentage' => $validated['stop_loss_percentage'] ?? null,
            'take_profit_percentage' => $validated['take_profit_percentage'] ?? null,
            'min_trade_amount' => $validated['min_trade_amount'],
            'max_trade_amount' => $validated['max_trade_amount'],
            'max_open_trades' => $validated['max_open_trades'],
            'trading_24_7' => $request->boolean('trading_24_7', true),
            'trading_start_time' => $request->input('trading_start_time'),
            'trading_end_time' => $request->input('trading_end_time'),
            'trading_days' => $request->filled('trading_days')
                ? array_filter($request->input('trading_days', []))
                : null,
            'auto_restart' => $request->boolean('auto_restart', false),
            'is_active' => $request->boolean('is_active', true),
            'metadata' => isset($validated['metadata'])
                ? json_decode($validated['metadata'], true)
                : null,
        ];

        return $payload;
    }

    protected function formData(?BotTemplate $template = null): array
    {
        return [
            'template' => $template,
            'tradingPairs' => $this->getAvailableTradingPairs(),
            'strategies' => $this->getAvailableStrategies(),
        ];
    }

    private function getAvailableTradingPairs(): array
    {
        return [
            'BTC/USDT' => ['base' => 'BTC', 'quote' => 'USDT'],
            'ETH/USDT' => ['base' => 'ETH', 'quote' => 'USDT'],
            'SOL/USDT' => ['base' => 'SOL', 'quote' => 'USDT'],
            'BNB/USDT' => ['base' => 'BNB', 'quote' => 'USDT'],
            'ADA/USDT' => ['base' => 'ADA', 'quote' => 'USDT'],
            'DOT/USDT' => ['base' => 'DOT', 'quote' => 'USDT'],
            'LINK/USDT' => ['base' => 'LINK', 'quote' => 'USDT'],
            'UNI/USDT' => ['base' => 'UNI', 'quote' => 'USDT'],
        ];
    }

    private function getAvailableStrategies(): array
    {
        return [
            'grid' => [
                'name' => 'Grid Trading',
                'description' => 'Buy low, sell high in predefined price ranges',
            ],
            'dca' => [
                'name' => 'Dollar Cost Averaging',
                'description' => 'Regular purchases at fixed intervals',
            ],
            'scalping' => [
                'name' => 'Scalping',
                'description' => 'Quick small profits from price swings',
            ],
            'trend_following' => [
                'name' => 'Trend Following',
                'description' => 'Ride market trends with confirmation signals',
            ],
        ];
    }
}
