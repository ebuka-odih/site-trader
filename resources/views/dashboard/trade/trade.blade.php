@extends('dashboard.layout.app')
@section('content')

    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-md-3">
                <div class="market-pairs">
                     <div class="m-2">
                    <div class="row align-items-center">
                        <!-- {{ auth()->user()->currency ?? 'USD' }} Balance -->
                        <div class="col text-left">
                            <h6>
                                <span class="text-left">Balance: </span>
                                <strong>{{ $user->formatAmount($user->balance) }}</strong>
                            </h6>
                        </div>
                        <!-- BTC Balance -->
                        <div class="col text-right">
                            <h6>
                                <span>BTC</span>
                                <strong id="btc-balance">{{ $user->balance }}</strong>
                            </h6>
                        </div>
                    </div>
                </div>
                    <ul class="nav nav-pills" role="tablist">

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="BTC" role="tabpanel">
                            <table class="table price-change-table">
                                <thead>
                                <tr>
                                    <th>Pairs</th>
                                    <th>Quantity</th>
                                    <th>Change</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pairs as $item)
                                    @if($item->type == 'crypto')
                                        <tr>
                                            <td><a href="{{ route('user.trade', $item->id) }}">{{ $item->pair }}</a>
                                            </td>
                                            <td>0.00020255</td>
                                            <td class="red">-2.58%</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="ETH" role="tabpanel">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Pairs</th>
                                    <th>Last Price</th>
                                    <th>Change</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> BTC/ETH</td>
                                    <td>0.00020255</td>
                                    <td class="green">+1.58%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> KCS/ETH</td>
                                    <td>0.00013192</td>
                                    <td class="red">-0.6%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XRP/ETH</td>
                                    <td>0.00002996</td>
                                    <td class="red">-0.55%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> VET/ETH</td>
                                    <td>0.00000103</td>
                                    <td class="green">+1.8%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> EOS/ETH</td>
                                    <td>0.00000103</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> BTT/ETH</td>
                                    <td>0.00002303</td>
                                    <td class="red">-1.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> LTC/ETH</td>
                                    <td>0.03520103</td>
                                    <td class="green">+1.5%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> TRX/ETH</td>
                                    <td>0.00330103</td>
                                    <td class="red">-3.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> BSV/ETH</td>
                                    <td>0.00300103</td>
                                    <td class="green">+2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> COTI/ETH</td>
                                    <td>0.003500103</td>
                                    <td class="green">+2.85%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XYT/ETH</td>
                                    <td>0.00003103</td>
                                    <td class="green">+3.55%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> BNB/ETH</td>
                                    <td>0.003500103</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XMR/ETH</td>
                                    <td>0.003500103</td>
                                    <td class="red">-1.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> TRY/ETH</td>
                                    <td>0.00000123</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> ADA/ETH</td>
                                    <td>0.00050103</td>
                                    <td class="green">+5.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> NEO/ETH</td>
                                    <td>0.00340103</td>
                                    <td class="red">-1.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XLM/ETH</td>
                                    <td>0.00035103</td>
                                    <td class="green">+5.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> ENQ/ETH</td>
                                    <td>0.00354103</td>
                                    <td class="green">+2.02%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> AVA/ETH</td>
                                    <td>0.02535103</td>
                                    <td class="green">+3.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> AMB/ETH</td>
                                    <td>0.05335103</td>
                                    <td class="green">+1.0%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> MAP/ETH</td>
                                    <td>0.00234103</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> GO/ETH</td>
                                    <td>0.00354103</td>
                                    <td class="red">-6.50%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> KICK/ETH</td>
                                    <td>0.02053103</td>
                                    <td class="red">-6.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> DBC/ETH</td>
                                    <td>0.02535103</td>
                                    <td class="green">+7.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> GGC/ETH</td>
                                    <td>0.00353103</td>
                                    <td class="red">-4.05%</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="NEO" role="tabpanel">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Pairs</th>
                                    <th>Last Price</th>
                                    <th>Change</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> ETH/NEO</td>
                                    <td>0.00350255</td>
                                    <td class="red">-6.58%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> KCS/NEO</td>
                                    <td>0.00013192</td>
                                    <td class="green">+0.6%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XRP/NEO</td>
                                    <td>0.00002996</td>
                                    <td class="red">-0.55%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> VET/NEO</td>
                                    <td>0.00000103</td>
                                    <td class="green">+1.8%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> EOS/NEO</td>
                                    <td>0.00000103</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> BTT/NEO</td>
                                    <td>0.00002303</td>
                                    <td class="red">-1.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> LTC/NEO</td>
                                    <td>0.03520103</td>
                                    <td class="green">+1.5%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> TRX/NEO</td>
                                    <td>0.00330103</td>
                                    <td class="red">-3.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> BSV/NEO</td>
                                    <td>0.00300103</td>
                                    <td class="green">+2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> COTI/NEO</td>
                                    <td>0.003500103</td>
                                    <td class="green">+2.85%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XYT/NEO</td>
                                    <td>0.00003103</td>
                                    <td class="green">+3.55%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> BNB/NEO</td>
                                    <td>0.003500103</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XMR/NEO</td>
                                    <td>0.003500103</td>
                                    <td class="red">-1.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> TRY/NEO</td>
                                    <td>0.00000123</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> ADA/NEO</td>
                                    <td>0.00050103</td>
                                    <td class="green">+5.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> NEO/NEO</td>
                                    <td>0.00340103</td>
                                    <td class="red">-1.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> XLM/NEO</td>
                                    <td>0.00035103</td>
                                    <td class="green">+5.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> ENQ/NEO</td>
                                    <td>0.00354103</td>
                                    <td class="green">+2.02%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> AVA/NEO</td>
                                    <td>0.02535103</td>
                                    <td class="green">+3.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> AMB/NEO</td>
                                    <td>0.05335103</td>
                                    <td class="green">+1.0%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> MAP/NEO</td>
                                    <td>0.00234103</td>
                                    <td class="red">-2.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> GO/NEO</td>
                                    <td>0.00354103</td>
                                    <td class="red">-6.50%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> KICK/NEO</td>
                                    <td>0.02053103</td>
                                    <td class="red">-6.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> DBC/NEO</td>
                                    <td>0.02535103</td>
                                    <td class="green">+7.05%</td>
                                </tr>
                                <tr>
                                    <td><i class="icon ion-md-star"></i> GGC/NEO</td>
                                    <td>0.00353103</td>
                                    <td class="red">-4.05%</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="main-chart">
                    <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container" style="height:100%;width:100%">
                        <div class="tradingview-widget-container__widget"
                             style="height:calc(100% - 32px);width:100%"></div>
                        <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/"
                                                                     rel="noopener nofollow" target="_blank"></a></div>
                        <script type="text/javascript"
                                src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js"
                                async>
                            {
                                "width":"100%",
                                "height":550,
                                "symbol":"BINANCE:{{ strtoupper(str_replace('/', '', $trade_pair->pair)) }}",
                                "interval":"D",
                                "timezone":"Etc/UTC",
                                "theme":"light",
                                "style":"1",
                                    "locale"
                            :
                                "en",
                                    "allow_symbol_change"
                            :
                                true,
                                    "calendar"
                            :
                                false,
                                    "support_host"
                            :
                                "https://www.tradingview.com"
                            }
                        </script>
                    </div>

                    <!-- TradingView Widget END -->
                </div>
                <div class="market-trade">

                    <div style="height: 500px" class="tab-content">
                        <div class="tab-pane fade show active" id="pills-trade-limit" role="tabpanel">

                            <div class="d-flex justify-content-between ">
                                 <div class="market-trade-buy col-md-12 col-lg-6">
                                    <form action="{{ route('user.placeBuyTrade') }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" name="amount" class="form-control"
                                                   placeholder="Amount" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ auth()->user()->currency ?? 'USD' }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Pairs</label>
                                            <select name="trade_pair_id" id="" class="form-control">
                                                 <option value="{{ $trade_pair->pair }}">{{ $trade_pair->pair }}</option>
                                                @foreach($pairs as $item)
                                                    @if($item->type == 'crypto')
                                                        <option value="{{ $item->id }}">{{ $item->pair }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Leverage</label>
                                                    <select class="custom-select" name="leverage" id="leverage">
                                                        <option value="10">1:10</option>
                                                        <option value="20">1:20</option>
                                                        <option value="30">1:30</option>
                                                        <option value="40">1:40</option>
                                                        <option value="50">1:50</option>
                                                        <option value="60">1:60</option>
                                                        <option value="70">1:70</option>
                                                        <option value="80">1:80</option>
                                                        <option value="90">1:90</option>
                                                        <option value="100">1:100</option>
                                                        <option value="110">1:110</option>
                                                        <option value="120">1:120</option>
                                                        <option value="130">1:130</option>
                                                        <option value="140">1:140</option>
                                                        <option value="150">1:150</option>
                                                        <option value="160">1:160</option>
                                                        <option value="170">1:170</option>
                                                        <option value="180">1:180</option>
                                                        <option value="190">1:190</option>
                                                        <option value="200">1:200</option>
                                                        <option value="210">1:210</option>
                                                        <option value="220">1:220</option>
                                                        <option value="230">1:230</option>
                                                        <option value="240">1:240</option>
                                                        <option value="250">1:250</option>
                                                        <option value="260">1:260</option>
                                                        <option value="270">1:270</option>
                                                        <option value="280">1:280</option>
                                                        <option value="290">1:290</option>
                                                        <option value="300">1:300</option>
                                                        <option value="310">1:310</option>
                                                        <option value="320">1:320</option>
                                                        <option value="330">1:330</option>
                                                        <option value="340">1:340</option>
                                                        <option value="350">1:350</option>
                                                        <option value="360">1:360</option>
                                                        <option value="370">1:370</option>
                                                        <option value="380">1:380</option>
                                                        <option value="390">1:390</option>
                                                        <option value="400">1:400</option>
                                                        <option value="410">1:410</option>
                                                        <option value="420">1:420</option>
                                                        <option value="430">1:430</option>
                                                        <option value="440">1:440</option>
                                                        <option value="450">1:450</option>
                                                        <option value="460">1:460</option>
                                                        <option value="470">1:470</option>
                                                        <option value="480">1:480</option>
                                                        <option value="490">1:490</option>
                                                        <option value="500">1:500</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Duration</label>
                                                    <select class="custom-select" name="duration" id="expire">
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

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label>Stop Loss</label>
                                                <input type="number" step="0.0001" name="stop_loss"
                                                       class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Take Profit</label>
                                                <input type="number" step="0.0001" name="take_profit"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <p>Bal: <span>{{ $user->formatAmount($user->balance) }}</span></p>
                                        <button class="btn buy" type="submit" name="action_type" value="buy">Buy
                                        </button>
                                    </form>
                                </div>

                                <div class="market-trade-sell col-md-12 col-lg-6">
                                    <form action="{{ route('user.placeSellTrade') }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" name="amount" class="form-control"
                                                   placeholder="Amount" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ auth()->user()->currency ?? 'USD' }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Pairs</label>
                                            <select name="trade_pair_id" id="" class="form-control">
                                                <option value="{{ $trade_pair->pair }}">{{ $trade_pair->pair }}</option>
                                                @foreach($pairs as $item)
                                                    @if($item->type == 'crypto')
                                                        <option value="{{ $item->id }}">{{ $item->pair }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Leverage</label>
                                                    <select class="custom-select" name="leverage" id="leverage">
                                                        <option value="10">1:10</option>
                                                        <option value="20">1:20</option>
                                                        <option value="30">1:30</option>
                                                        <option value="40">1:40</option>
                                                        <option value="50">1:50</option>
                                                        <option value="60">1:60</option>
                                                        <option value="70">1:70</option>
                                                        <option value="80">1:80</option>
                                                        <option value="90">1:90</option>
                                                        <option value="100">1:100</option>
                                                        <option value="110">1:110</option>
                                                        <option value="120">1:120</option>
                                                        <option value="130">1:130</option>
                                                        <option value="140">1:140</option>
                                                        <option value="150">1:150</option>
                                                        <option value="160">1:160</option>
                                                        <option value="170">1:170</option>
                                                        <option value="180">1:180</option>
                                                        <option value="190">1:190</option>
                                                        <option value="200">1:200</option>
                                                        <option value="210">1:210</option>
                                                        <option value="220">1:220</option>
                                                        <option value="230">1:230</option>
                                                        <option value="240">1:240</option>
                                                        <option value="250">1:250</option>
                                                        <option value="260">1:260</option>
                                                        <option value="270">1:270</option>
                                                        <option value="280">1:280</option>
                                                        <option value="290">1:290</option>
                                                        <option value="300">1:300</option>
                                                        <option value="310">1:310</option>
                                                        <option value="320">1:320</option>
                                                        <option value="330">1:330</option>
                                                        <option value="340">1:340</option>
                                                        <option value="350">1:350</option>
                                                        <option value="360">1:360</option>
                                                        <option value="370">1:370</option>
                                                        <option value="380">1:380</option>
                                                        <option value="390">1:390</option>
                                                        <option value="400">1:400</option>
                                                        <option value="410">1:410</option>
                                                        <option value="420">1:420</option>
                                                        <option value="430">1:430</option>
                                                        <option value="440">1:440</option>
                                                        <option value="450">1:450</option>
                                                        <option value="460">1:460</option>
                                                        <option value="470">1:470</option>
                                                        <option value="480">1:480</option>
                                                        <option value="490">1:490</option>
                                                        <option value="500">1:500</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Duration</label>
                                                    <select class="custom-select" name="duration" id="expire">
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

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label>Stop Loss</label>
                                                <input type="number" step="0.0001" name="stop_loss"
                                                       class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Take Profit</label>
                                                <input type="number" step="0.0001" name="take_profit"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <p>Bal: <span>{{ $user->formatAmount($user->balance) }}</span></p>
                                        <button class="btn sell" type="submit" name="action_type" value="sell">
                                            Sell
                                        </button>
                                    </form>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="order-book">
                    <h2 class="heading">Order Book</h2>
                    <table class="table" id="order-book">
                        <thead>
                        <tr>
                            <th>Price(BTC)</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Sell Orders -->
                        <tr class="red-bg-80">
                            <td class="red">0.022572</td>
                            <td>1.253415</td>
                            <td>15.27648</td>
                        </tr>
                        <tr class="red-bg-60">
                            <td class="red">0.020371</td>
                            <td>1.205415</td>
                            <td>15.25648</td>
                        </tr>
                        <tr class="red-bg-40">
                            <td class="red">0.023572</td>
                            <td>1.645415</td>
                            <td>15.23648</td>
                        </tr>
                        <tr class="red-bg-20">
                            <td class="red">0.032378</td>
                            <td>1.206715</td>
                            <td>15.25348</td>
                        </tr>
                        <tr class="red-bg-10">
                            <td class="red">0.022573</td>
                            <td>1.262415</td>
                            <td>15.19648</td>
                        </tr>
                        <tr class="red-bg-8">
                            <td class="red">0.154377</td>
                            <td>1.225415</td>
                            <td>15.35648</td>
                        </tr>
                        <tr class="red-bg-5">
                            <td class="red">0.120373</td>
                            <td>1.285415</td>
                            <td>15.25648</td>
                        </tr>
                        <tr class="red-bg">
                            <td class="red">0.028576</td>
                            <td>1.291415</td>
                            <td>15.26448</td>
                        </tr>
                        </tbody>
                        <tbody class="ob-heading">
                        {{--               <tr>--}}
                        {{--                <td><span>Last Price</span> <span class="last-price">0.020367</span></td>--}}
                        {{--                <td><span>USD</span> <span class="usd-value">148.65</span></td>--}}
                        {{--                <td class="red"><span>Change</span> <span class="change-value">-0.51%</span></td>--}}
                        {{--              </tr>--}}
                        </tbody>
                        <tbody>
                        <!-- Buy Orders -->
                        <tr class="green-bg">
                            <td class="green">0.158373</td>
                            <td>1.209515</td>
                            <td>15.23248</td>
                        </tr>
                        <tr class="green-bg-5">
                            <td class="green">0.020851</td>
                            <td>1.206245</td>
                            <td>15.25458</td>
                        </tr>
                        <tr class="green-bg-8">
                            <td class="green">0.025375</td>
                            <td>1.205715</td>
                            <td>15.65648</td>
                        </tr>
                        <tr class="green-bg-10">
                            <td class="green">0.020252</td>
                            <td>1.205495</td>
                            <td>15.24548</td>
                        </tr>
                        <tr class="green-bg-20">
                            <td class="green">0.020373</td>
                            <td>1.205415</td>
                            <td>15.25648</td>
                        </tr>
                        <tr class="green-bg-40">
                            <td class="green">0.020156</td>
                            <td>1.207515</td>
                            <td>15.28948</td>
                        </tr>
                        <tr class="green-bg-60">
                            <td class="green">0.540375</td>
                            <td>1.205915</td>
                            <td>15.25748</td>
                        </tr>
                        <tr class="green-bg-80">
                            <td class="green">0.020372</td>
                            <td>1.205415</td>
                            <td>15.25648</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="market-history">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#recent-trades" role="tab"
                               aria-selected="false">Recent
                                Trades</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade  show active" id="recent-trades" role="tabpanel">
                            <table class="table" id="order-history">
                                <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="red">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="red">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="red">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="red">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="red">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="green">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                <tr>
                                    <td>13:03:53</td>
                                    <td class="red">0.020191</td>
                                    <td>0.2155045</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

             <div class="col-md-12 mt-2">
                <div class="market-history market-order">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#open-orders" role="tab"
                               aria-selected="true">Open
                                Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#stop-orders" role="tab" aria-selected="false">Closed
                                Orders</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="open-orders" role="tabpanel">
                            <div class="table-responsive ">
                                <table class="table ">
                                    <tr class="d-flex ">
                                        <th>Time</th>
                                        <th>Pairs</th>
                                        <th>Type</th>
                                        <th>Buy/Sell</th>
                                        <th>Amount</th>
                                        <th>Leverage</th>
                                        <th>Duration</th>
                                        <th>Stop Loss</th>
                                        <th>Take Profit</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($trades as $item)
                                        @if($item->status == 'open')
                                            <tr class="d-flex ">
                                                <td>{{ $item->created_at ?? ''}}</td>
                                                <td>{{ $item->trade_pair->pair ?? '' }}</td>
                                                <td>{{ $item->trade_pair->type ?? ''}}</td>
                                                <td>
                                                    @if($item->action_type == "buy")
                                                        <span class="badge bg-primary text-white">buy</span>
                                                    @else
                                                        <span class="badge bg-danger text-white">sell</span>
                                                    @endif
                                                </td>
                                                <td>${{ number_format($item->amount, 2) ?? '' }}</td>
                                                <td>{{ $item->leverage ?? ''}}x</td>
                                                <td>{{ $item->duration ?? ''}} min</td>
                                                <td>{{ $item->stop_loss ?? 'N/A'}}</td>
                                                <td>{{ $item->take_profit ?? 'N/A'}}</td>
                                                <td>{!! $item->status_badge ?? '' !!}</td>
                                                <td>
                                                    <a href="{{ route('user.closeTrade', $item->id) }}"
                                                       class="btn btn-sm btn-danger">Close</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="stop-orders" role="tabpanel">
                            <div class="table-responsive ">
                                <table class="table ">
                                    <tr class="d-flex ">
                                        <th>Time</th>
                                        <th>Pairs</th>
                                        <th>Type</th>
                                        <th>Buy/Sell</th>
                                        <th>Amount</th>
                                        <th>Leverage</th>
                                        <th>Duration</th>
                                        <th>Stop Loss</th>
                                        <th>Take Profit</th>
                                        <th>Status</th>
                                        <th>Profit/Loss</th>
                                    </tr>
                                    @foreach($closed_trades as $item)
                                        @if($item->status == 'closed')
                                            <tr class="d-flex ">
                                                <td>{{ $item->created_at ?? ''}}</td>
                                                <td>{{ $item->trade_pair->pair ?? '' }}</td>
                                                <td>{{ $item->trade_pair->type ?? ''}}</td>
                                                <td>
                                                    @if($item->action_type == "buy")
                                                        <span class="badge bg-primary text-white">buy</span>
                                                    @else
                                                        <span class="badge bg-danger text-white">sell</span>
                                                    @endif
                                                </td>
                                                <td>${{ number_format($item->amount, 2) ?? '' }}</td>
                                                <td>{{ $item->leverage ?? ''}}x</td>
                                                <td>{{ $item->duration ?? ''}} min</td>
                                                <td>{{ $item->stop_loss ?? 'N/A'}}</td>
                                                <td>{{ $item->take_profit ?? 'N/A'}}</td>
                                                <td>{!! $item->status_badge ?? '' !!}</td>
                                                <td>${!! $item->profit_loss ?? '' !!}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        function randomizeBuySellOrders() {
            const rows = document.querySelectorAll('#order-book tbody tr:not(.ob-heading)');

            rows.forEach(row => {
                const priceCell = row.querySelector('td:nth-child(1)');
                const amountCell = row.querySelector('td:nth-child(2)');
                const totalCell = row.querySelector('td:nth-child(3)');

                // Parse the numeric values
                let price = parseFloat(priceCell.textContent);
                let amount = parseFloat(amountCell.textContent);
                let total = parseFloat(totalCell.textContent);

                // Randomize the values with noticeable changes
                price += price * (Math.random() - 0.5) * 0.02; // +/- 2% movement
                amount += amount * (Math.random() - 0.5) * 0.01; // +/- 1% movement
                total = price * amount; // Recalculate total

                // Update the cells with new values
                priceCell.textContent = price.toFixed(6);
                amountCell.textContent = amount.toFixed(6);
                totalCell.textContent = total.toFixed(6);
            });
        }

        function randomizeLastPriceUSDChange() {
            const lastPriceCell = document.querySelector('.last-price');
            const usdCell = document.querySelector('.usd-value');
            const changeCell = document.querySelector('.change-value');

            // Extract the numeric parts
            let lastPrice = parseFloat(lastPriceCell.textContent); // Extract the numeric part from "Last Price"
            let usdValue = parseFloat(usdCell.textContent); // Extract the numeric part from "USD"
            let change = parseFloat(changeCell.textContent); // Extract the numeric part from "Change"

            // Randomize the values with noticeable changes
            lastPrice += lastPrice * (Math.random() - 0.5) * 0.05; // +/- 5% movement for last price
            usdValue += usdValue * (Math.random() - 0.5) * 0.02; // +/- 2% for USD value
            change = ((lastPrice - 0.020367) / 0.020367) * 100; // Calculate change percentage based on initial value

            // Update the cells with new values
            lastPriceCell.textContent = lastPrice.toFixed(6);
            usdCell.textContent = usdValue.toFixed(2);
            changeCell.textContent = `${change.toFixed(2)}%`;
        }

        // Start randomizing the "Last Price", "USD", and "Change" after 10 seconds and repeat every 5 seconds
        setTimeout(() => {
            setInterval(randomizeLastPriceUSDChange, 1000);
        }, 1000);

        // Start randomizing the buy/sell orders after 10 seconds and repeat every 3 seconds
        setTimeout(() => {
            setInterval(randomizeBuySellOrders, 1000);
        }, 1000);

    </script>

    <script>
        function randomizeTimePriceAmount() {
            // Select only the rows in the #order-history table
            const rows = document.querySelectorAll('#order-history tbody tr');

            rows.forEach(row => {
                const timeCell = row.querySelector('td:nth-child(1)');
                const priceCell = row.querySelector('td:nth-child(2)');
                const amountCell = row.querySelector('td:nth-child(3)');

                // Get the current time in HH:MM:SS format
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const currentTime = `${hours}:${minutes}:${seconds}`;

                // Randomize the price and amount
                let price = parseFloat(priceCell.textContent);
                let amount = parseFloat(amountCell.textContent);

                // Adjust price and amount randomly (+/- 1% for price, +/- 2% for amount)
                price += price * (Math.random() - 0.5) * 0.01;
                amount += amount * (Math.random() - 0.5) * 0.02;

                // Update the table cells with new values
                timeCell.textContent = currentTime;
                priceCell.textContent = price.toFixed(6); // Price to 6 decimal places
                amountCell.textContent = amount.toFixed(7); // Amount to 7 decimal places

                // Change the class of priceCell to red or green based on price movement
                if (Math.random() > 0.5) {
                    priceCell.classList.remove('red');
                    priceCell.classList.add('green');
                } else {
                    priceCell.classList.remove('green');
                    priceCell.classList.add('red');
                }
            });
        }

        // Start randomizing the rows after 10 seconds and update every 2 seconds
        setTimeout(() => {
            setInterval(randomizeTimePriceAmount, 2000);
        }, 1000);

    </script>

    <script>
        function simulatePriceChange() {
            // Select all rows in the price-change-table
            const rows = document.querySelectorAll('.price-change-table tbody tr');

            rows.forEach(row => {
                const priceCell = row.querySelector('td:nth-child(2)');
                const changeCell = row.querySelector('td:nth-child(3)');

                // Get the current last price
                let lastPrice = parseFloat(priceCell.textContent);

                // Randomize the last price (+/- 2% movement)
                const priceMovement = (Math.random() - 0.5) * 0.04; // +/- 4%
                const newPrice = lastPrice + (lastPrice * priceMovement);

                // Calculate change percentage based on the old price
                const changePercentage = ((newPrice - lastPrice) / lastPrice) * 100;

                // Update the price cell
                priceCell.textContent = newPrice.toFixed(8); // Price to 8 decimal places

                // Update the change cell and its color
                changeCell.textContent = changePercentage.toFixed(2) + '%';

                // Change the color based on the change value
                if (changePercentage >= 0) {
                    changeCell.classList.remove('red');
                    changeCell.classList.add('green');
                } else {
                    changeCell.classList.remove('green');
                    changeCell.classList.add('red');
                }
            });
        }

        // Start simulating price changes after 10 seconds and repeat every 5 seconds
        setTimeout(() => {
            setInterval(simulatePriceChange, 2000);
        }, 1000);

    </script>

@endsection
