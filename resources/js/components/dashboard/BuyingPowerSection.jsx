import React from 'react';
import { formatCurrency, maskBalance } from './utils';

const BuyingPowerSection = ({ buyingPower, isBalanceHidden }) => {
    return (
        <div className="rounded-xl border bg-[#0a0a0a] text-white shadow-sm border-[#1a1a1a]">
            <div className="p-3">
                <div className="flex justify-between items-center">
                    <p className="text-xs uppercase font-semibold text-gray-400">
                        Buying Power
                    </p>
                    <p className="text-sm font-bold text-white font-mono">
                        {maskBalance(isBalanceHidden, formatCurrency(buyingPower))}
                    </p>
                </div>
            </div>
        </div>
    );
};

export default BuyingPowerSection;

