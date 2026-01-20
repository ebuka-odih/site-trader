import React from 'react';
import CheckmarkIcon from './icons/CheckmarkIcon';
import { maskBalance } from './utils';

const AccountCards = ({ investingTab, walletTab, isBalanceHidden, onToggleBalanceVisibility }) => {
    return (
        <div className="grid grid-cols-2 gap-2">
            {/* Investing Card */}
            <button
                type="button"
                className="border-2 rounded-lg p-2.5 flex flex-col justify-between cursor-pointer relative overflow-hidden group animate-slide-up border-[#00ff63] bg-[#00ff63]/5 transition-all duration-300 hover:scale-105"
                style={{ transition: '0.3s cubic-bezier(0.34, 1.56, 0.64, 1)' }}
            >
                <div className="flex items-center justify-between">
                    <p className="text-xs uppercase font-semibold text-gray-400">
                        {investingTab.label || 'Investing'}
                    </p>
                    <button
                        type="button"
                        onClick={onToggleBalanceVisibility}
                        className="cursor-pointer hover:opacity-80 transition-opacity"
                        aria-label={isBalanceHidden ? 'Show balance' : 'Hide balance'}
                    >
                        <CheckmarkIcon />
                    </button>
                </div>
                <div className="group-hover:scale-105 transition-transform duration-300">
                    <p className="text-base font-bold font-mono text-[#00ff63]">
                        {maskBalance(isBalanceHidden, investingTab.balance)}
                    </p>
                    <p className={`text-xs ${investingTab.isPositive ? 'text-[#00ff63]' : 'text-red-400'}`}>
                        {isBalanceHidden ? '***' : (investingTab.change || '+0.00%')}
                    </p>
                </div>
            </button>

            {/* Wallet Balance Card */}
            <button
                type="button"
                className="border-2 rounded-lg p-2.5 flex flex-col justify-between cursor-pointer relative overflow-hidden group animate-slide-up border-[#00ff63] bg-[#00ff63]/5 transition-all duration-300 hover:scale-105"
                style={{ transition: '0.3s cubic-bezier(0.34, 1.56, 0.64, 1)', animationDelay: '0.1s' }}
            >
                <div className="flex items-center justify-between">
                    <p className="text-xs uppercase font-semibold text-gray-400">
                        {walletTab.label || 'Wallet Balance'}
                    </p>
                    <button
                        type="button"
                        onClick={onToggleBalanceVisibility}
                        className="cursor-pointer hover:opacity-80 transition-opacity"
                        aria-label={isBalanceHidden ? 'Show balance' : 'Hide balance'}
                    >
                        <CheckmarkIcon />
                    </button>
                </div>
                <div className="group-hover:scale-105 transition-transform duration-300">
                    <p className="text-base font-bold font-mono text-[#00ff63]">
                        {maskBalance(isBalanceHidden, walletTab.balance)}
                    </p>
                </div>
            </button>
        </div>
    );
};

export default AccountCards;

