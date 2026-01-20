import React from 'react';
import { createRoot } from 'react-dom/client';
import NeoDashboard from './components/dashboard/NeoDashboard';

const container = document.getElementById('react-dashboard');

if (container) {
    const props = container.dataset.props
        ? JSON.parse(container.dataset.props)
        : {};

    const root = createRoot(container);
    root.render(<NeoDashboard {...props} />);
}
