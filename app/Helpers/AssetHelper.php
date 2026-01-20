<?php

namespace App\Helpers;

class AssetHelper
{
    public static function getAssetIconClass($symbol)
    {
        // Define colors for popular cryptocurrencies
        $cryptoColors = [
            'BTC' => 'bg-yellow-500',
            'ETH' => 'bg-blue-500',
            'ADA' => 'bg-blue-600',
            'XRP' => 'bg-black',
            'USDT' => 'bg-green-500',
            'USDC' => 'bg-blue-400',
            'BNB' => 'bg-yellow-400',
            'SOL' => 'bg-purple-500',
            'DOT' => 'bg-pink-500',
            'AVAX' => 'bg-red-500',
            'MATIC' => 'bg-purple-600',
            'LINK' => 'bg-blue-700',
            'UNI' => 'bg-pink-400',
            'LTC' => 'bg-gray-500',
            'BCH' => 'bg-orange-500',
            'XLM' => 'bg-purple-400',
            'ATOM' => 'bg-indigo-500',
            'FTT' => 'bg-green-400',
            'FIL' => 'bg-blue-800',
            'TRX' => 'bg-red-600',
            'ETC' => 'bg-green-600',
            'XMR' => 'bg-orange-600',
            'EOS' => 'bg-gray-600',
            'AAVE' => 'bg-purple-700',
            'ALGO' => 'bg-gray-400',
            'NEO' => 'bg-green-700',
            'VET' => 'bg-teal-500',
            'ICP' => 'bg-pink-600',
            'THETA' => 'bg-purple-800',
            'XTZ' => 'bg-blue-900',
            'CAKE' => 'bg-pink-300',
            'MKR' => 'bg-orange-400',
            'COMP' => 'bg-blue-300',
            'YFI' => 'bg-yellow-600',
            'SUSHI' => 'bg-pink-700',
            'SNX' => 'bg-cyan-500',
            'BAL' => 'bg-indigo-600',
            'CRV' => 'bg-blue-200',
            '1INCH' => 'bg-red-400',
            'ZRX' => 'bg-gray-300',
            'REN' => 'bg-gray-700',
            'BAND' => 'bg-purple-300',
            'STORJ' => 'bg-blue-100',
            'MANA' => 'bg-orange-300',
            'SAND' => 'bg-yellow-300',
            'ENJ' => 'bg-purple-200',
            'CHZ' => 'bg-red-300',
            'HOT' => 'bg-green-300',
            'BAT' => 'bg-orange-200',
            'ZEC' => 'bg-yellow-700',
            'DASH' => 'bg-blue-600',
            'WAVES' => 'bg-purple-100',
            'QTUM' => 'bg-green-200',
            'IOTA' => 'bg-orange-100',
            'NANO' => 'bg-gray-200',
            'ICX' => 'bg-purple-900',
            'ONT' => 'bg-green-800',
            'ZIL' => 'bg-blue-500',
            'VTHO' => 'bg-red-200',
            'HBAR' => 'bg-purple-600',
            'CRO' => 'bg-blue-700',
            'XDC' => 'bg-green-600',
            'ONE' => 'bg-purple-500',
            'IOTX' => 'bg-blue-800',
            'ANKR' => 'bg-blue-600',
            'BTT' => 'bg-blue-500',
            'WIN' => 'bg-green-500',
            'CHR' => 'bg-purple-400',
            'MASK' => 'bg-blue-400',
            'AR' => 'bg-red-500',
            'FLOW' => 'bg-blue-600',
            'RUNE' => 'bg-yellow-500',
            'KSM' => 'bg-purple-500',
            'DYDX' => 'bg-gray-500',
            'IMX' => 'bg-blue-500',
            'GALA' => 'bg-purple-400',
            'ROSE' => 'bg-pink-400',
            'OP' => 'bg-red-400',
            'ARB' => 'bg-blue-500',
            'INJ' => 'bg-blue-600',
            'TIA' => 'bg-purple-500',
            'SEI' => 'bg-blue-500',
            'SUI' => 'bg-blue-600',
            'APT' => 'bg-blue-500',
            'NEAR' => 'bg-black',
            'FTM' => 'bg-blue-500',
            'HEDERA' => 'bg-purple-600'
        ];
        
        // Define colors for popular stocks
        $stockColors = [
            'AAPL' => 'bg-gray-800',
            'MSFT' => 'bg-blue-600',
            'GOOGL' => 'bg-blue-500',
            'AMZN' => 'bg-orange-500',
            'TSLA' => 'bg-red-500',
            'META' => 'bg-blue-700',
            'NVDA' => 'bg-green-600',
            'NFLX' => 'bg-red-600',
            'ADBE' => 'bg-red-400',
            'CRM' => 'bg-blue-400',
            'PYPL' => 'bg-blue-500',
            'INTC' => 'bg-blue-600',
            'AMD' => 'bg-red-500',
            'ORCL' => 'bg-red-600',
            'CSCO' => 'bg-blue-500',
            'IBM' => 'bg-blue-600',
            'QCOM' => 'bg-green-500',
            'TXN' => 'bg-red-500',
            'AVGO' => 'bg-blue-500',
            'ACN' => 'bg-blue-600',
            'WMT' => 'bg-blue-500',
            'JPM' => 'bg-blue-600',
            'V' => 'bg-blue-500',
            'JNJ' => 'bg-red-500',
            'PG' => 'bg-blue-500',
            'UNH' => 'bg-blue-600',
            'HD' => 'bg-orange-500',
            'MA' => 'bg-orange-600',
            'DIS' => 'bg-blue-500',
            'BAC' => 'bg-red-500',
            'ADBE' => 'bg-red-400',
            'CRM' => 'bg-blue-400',
            'PYPL' => 'bg-blue-500',
            'INTC' => 'bg-blue-600',
            'AMD' => 'bg-red-500',
            'ORCL' => 'bg-red-600',
            'CSCO' => 'bg-blue-500',
            'IBM' => 'bg-blue-600',
            'QCOM' => 'bg-green-500',
            'TXN' => 'bg-red-500',
            'AVGO' => 'bg-blue-500',
            'ACN' => 'bg-blue-600',
            'WMT' => 'bg-blue-500',
            'JPM' => 'bg-blue-600',
            'V' => 'bg-blue-500',
            'JNJ' => 'bg-red-500',
            'PG' => 'bg-blue-500',
            'UNH' => 'bg-blue-600',
            'HD' => 'bg-orange-500',
            'MA' => 'bg-orange-600',
            'DIS' => 'bg-blue-500',
            'BAC' => 'bg-red-500'
        ];
        
        // Check if we have a predefined color for this crypto
        if (isset($cryptoColors[$symbol])) {
            return $cryptoColors[$symbol];
        }
        
        // Check if we have a predefined color for this stock
        if (isset($stockColors[$symbol])) {
            return $stockColors[$symbol];
        }
        
        // For stocks or unknown cryptos, use a default color based on the first letter
        $defaultColors = [
            'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500',
            'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-orange-500', 'bg-gray-500'
        ];
        
        $firstChar = strtoupper(substr($symbol, 0, 1));
        $colorIndex = ord($firstChar) % count($defaultColors);
        
        return $defaultColors[$colorIndex];
    }
    
    public static function getAssetInitial($symbol)
    {
        return strtoupper(substr($symbol, 0, 1));
    }
}
