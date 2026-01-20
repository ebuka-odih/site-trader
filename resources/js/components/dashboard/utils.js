/**
 * Format a number as USD currency
 * @param {number|string} value - The value to format
 * @returns {string} Formatted currency string
 */
export const formatCurrency = (value = 0) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 2,
    }).format(Number(value));

/**
 * Mask a balance value if balance is hidden
 * @param {boolean} isBalanceHidden - Whether balance should be hidden
 * @param {string} balance - The balance string to mask
 * @returns {string} Masked or original balance
 */
export const maskBalance = (isBalanceHidden, balance) => {
    if (isBalanceHidden) {
        return '***';
    }
    return balance;
};

