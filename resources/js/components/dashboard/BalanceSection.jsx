import React from 'react';
import { maskBalance } from './utils';

const BalanceSection = ({ profit, profitChange, profitIsPositive, isBalanceHidden }) => {
    // Parse change value to extract amount and percentage
    const parseChange = (changeText) => {
        if (!changeText || isBalanceHidden) return { amount: '$0.00', percentage: '(0%)' };
        
        // Format: "$0.00 (+0.00%)" or "$0.00 (-0.00%)"
        const match = changeText.match(/\$([\d,]+\.?\d*)\s*\(([+-]?[\d.]+%)\)/);
        if (match) {
            return {
                amount: `$${match[1]}`,
                percentage: `(${match[2]})`
            };
        }
        
        // Fallback: if it's just a percentage like "+0.00%"
        if (changeText.includes('%')) {
            return {
                amount: '$0.00',
                percentage: changeText.startsWith('+') || changeText.startsWith('-') ? `(${changeText})` : changeText
            };
        }
        
        return { amount: '$0.00', percentage: '(0%)' };
    };

    const changeData = parseChange(profitChange);
    const isPositive = profitIsPositive ?? true;

    return (
        <div className="px-0 sm:px-1">
            <p className="text-xs uppercase font-semibold text-gray-400 mb-2 tracking-widest">
                PNL
            </p>
            <p className="text-3xl font-bold text-white font-mono">
                {maskBalance(isBalanceHidden, profit)}
            </p>
            {!isBalanceHidden && profitChange && (
                <div className="flex items-center gap-1 mt-1">
                    {/* Arrow Icon */}
                    {isPositive ? (
                        <svg className="w-4 h-4 text-[#00ff63]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    ) : (
                        <svg className="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    )}
                    {/* Change Amount and Percentage */}
                    <p className={`text-sm font-semibold ${isPositive ? 'text-[#00ff63]' : 'text-red-400'}`}>
                        {changeData.amount} {changeData.percentage}
                    </p>
                </div>
            )}
        </div>
    );
};

export default BalanceSection;

