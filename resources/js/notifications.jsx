import React from 'react';
import { createRoot } from 'react-dom/client';
import NotificationsPage from './components/notifications/NotificationsPage';

const container = document.getElementById('react-notifications');

if (container) {
    const props = container.dataset.props
        ? JSON.parse(container.dataset.props)
        : {};

    const root = createRoot(container);
    root.render(<NotificationsPage {...props} />);
}















