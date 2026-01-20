@extends('admin.layouts.app')
@section('content')


 <div class="px-4 pt-5">

     <div class="grid gap-4 grid-cols-3 sm:grid-cols-2">
        <!-- Main widget - 8 columns -->
        <div class="col-span-2 sm:grid-cols-2 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container">
                    <div class="tradingview-widget-container__widget"></div>
                    <div class="tradingview-widget-copyright">
                        <a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank">
                            <span class="blue-text">Track all markets on TradingView</span>
                        </a>
                    </div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                        {
                            "width": "100%",
                            "height": "500",
                            "symbol": "BINANCE:BTCUSDT",
                            "interval": "D",
                            "timezone": "Etc/UTC",
                            "theme": "dark",
                            "style": "1",
                            "locale": "en",
                            "allow_symbol_change": true,
                            "calendar": false,
                            "support_host": "https://www.tradingview.com"
                        }
                    </script>
                </div>
                <!-- TradingView Widget END -->
            </div>
        </div>

        <!-- Tabs widget - 4 columns -->
        <div class=" p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">Place A Trade</h3>

            <form class="max-w-[24rem] mx-auto" action="{{ route('admin.trade.store') }}" method="POST">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">

                <div class="col-span-2" >
                    <label for="user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User</label>
                    <select id="user" name="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <!-- Crypto options -->
                        <option selected disabled>Select User</option>
                        @foreach($users as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                   <div class="col-span-2">
                    <label for="market" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Market</label>
                    <select id="market" name="market" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled>Choose Market</option>
                        <option value="crypto">Crypto</option>
                        <option value="forex">Forex</option>
                        <option value="stock">Stock</option>
                    </select>
                </div>

                <!-- Crypto Pairs -->

                <div class="col-span-2 market-pair" id="crypto-pairs" style="display: none;">
                    <label for="crypto-pair" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Crypto Pairs</label>
                    <select id="crypto-pair" name="trade_pair_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <!-- Crypto options -->
                        @foreach($pairs as $item)
                            @if($item->type == 'crypto')
                                <option value="{{ $item->id }}">{{ $item->pair }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Forex Pairs -->
                <div class="col-span-2 market-pair" id="forex-pairs" style="display: none;">
                    <label for="forex-pair" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Forex Pairs</label>
                    <select id="forex-pair" name="trade_pair_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <!-- Forex options -->
                        @foreach($pairs as $item)
                            @if($item->type == 'forex')
                                <option value="{{ $item->id }}">{{ $item->pair }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Stock Pairs -->
                <div class="col-span-2 market-pair" id="stock-pairs" style="display: none;">
                    <label for="stock-pair" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock Pairs</label>
                    <select id="stock-pair" name="trade_pair_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <!-- Stock options -->
                        @foreach($pairs as $item)
                            @if($item->type == 'stock')
                                <option value="{{ $item->id }}">{{ $item->pair }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>


                </div>
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                        <input type="number" name="amount" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="100" required="">
                    </div>
                </div>

                <div>
                    <h5 class="font-semibold text-gray-900 dark:text-white">Leverage</h5>
                    <div class="flex mb-4">

                    <label for="currency-input" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your Email</label>
                    <div class="relative w-full">
                        <input type="number" name="leverage" readonly id="currency-input" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" placeholder="Enter Leverage" value="10" required />
                    </div>
                     <button id="dropdown-currency-button" data-dropdown-toggle="dropdown-currency" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600" type="button">
                       X
                    </button>
                </div>
                <div class="relative">
                    <label for="price-range-input" class="sr-only">Default range</label>
                    <input id="price-range-input" type="range" value="10" min="5" max="100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">

                </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 absolute start-0 -bottom-6">5x - 100X</span>
                </div>
                <div class="grid gap-4 mb-4 grid-cols-2 mt-8">
                    <div class="col-span-2">
                         <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Duration</label>
                        <select id="countries" name="duration" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                             <option value="1">1 Minute</option>
                            <option value="2">2 Minutes</option>
                            <option value="3">3 Minutes</option>
                            <option value="4">4 Minutes</option>
                            <option value="5">5 Minutes</option>
                            <option value="10">10 Minutes</option>
                            <option value="15">15 Minutes</option>
                            <option value="30">30 Minutes</option>
                            <option value="60">1 Hour</option>
                            <option value="120">2 Hours</option>
                            <option value="180">4 Hours</option>
                            <option value="360">6 Hours</option>
                            <option value="720">12 Hours</option>
                            <option value="1440">1 Day</option>
                            <option value="2880">2 Days</option>
                            <option value="5320">3 Days</option>
                            <option value="7200">5 Days</option>
                            <option value="10080">7 Days</option>
                        </select>

                    </div>
                </div>
                <div>
                    <button type="submit" name="action_type" value="buy" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Buy</button>

                    <button type="submit" name="action_type" value="sell" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Sell</button>

                </div>
            </form>

        </div>
    </div>



 </div>
<script>
    document.getElementById('market').addEventListener('change', function() {
        // Hide all market pair divs
        document.querySelectorAll('.market-pair').forEach(function(pairDiv) {
            pairDiv.style.display = 'none';
        });

        // Show the selected market pair div
        const selectedMarket = this.value;
        if (selectedMarket) {
            document.getElementById(selectedMarket + '-pairs').style.display = 'block';
        }
    });
</script>
 <script>

    // Get the elements
    var rangeInput = document.getElementById('price-range-input');
    var currencyInput = document.getElementById('currency-input');

    // Function to update the currency input
    function updateCurrencyInput() {
    currencyInput.value = rangeInput.value;
    }

    // Add event listener to the range input
    rangeInput.addEventListener('input', updateCurrencyInput);

 </script>

@endsection
