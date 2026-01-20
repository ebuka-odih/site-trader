import React from 'react';
import { createRoot } from 'react-dom/client';
import TradingPage from './components/trading/TradingPage';

const container = document.getElementById('react-trading-page');

if (container) {
    const props = container.dataset.props
        ? JSON.parse(container.dataset.props)
        : {};

    const root = createRoot(container);
    root.render(<TradingPage {...props} />);
}
















