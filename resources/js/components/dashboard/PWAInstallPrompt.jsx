import React, { useState, useEffect } from 'react';

/**
 * PWA Install Prompt Component for React
 * Shows install instructions for iOS and Android
 */
const PWAInstallPrompt = () => {
    const [showPrompt, setShowPrompt] = useState(false);
    const [isIOS, setIsIOS] = useState(false);
    const [deferredPrompt, setDeferredPrompt] = useState(null);
    const [isStandalone, setIsStandalone] = useState(false);

    useEffect(() => {
        // Detect if app is already installed
        const standalone = window.matchMedia('(display-mode: standalone)').matches || 
                          window.navigator.standalone === true;
        setIsStandalone(standalone);

        // Detect iOS
        const userAgent = window.navigator.userAgent.toLowerCase();
        const ios = /iphone|ipad|ipod/.test(userAgent) || 
                   (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        setIsIOS(ios);

        // Check if prompt was previously dismissed
        let dismissed = localStorage.getItem('pwa-install-prompt-dismissed');
        
        // Don't show if already installed
        if (standalone) {
            console.log('[PWA React] App already installed, skipping prompt');
            return;
        }

        // For testing on dashboard: Check if we're on dashboard page and show prompt
        const isDashboardPage = window.location.pathname.includes('/user/dashboard');
        if (isDashboardPage && dismissed === 'true') {
            // Reset dismissal for dashboard page to always show (optional - for testing)
            // Uncomment next line to always show on dashboard
            // dismissed = null;
        }
        
        // Don't show if dismissed
        if (dismissed === 'true') {
            console.log('[PWA React] Prompt was dismissed, skipping');
            return;
        }

        // Show iOS prompt after short delay
        if (ios) {
            console.log('[PWA React] iOS detected, will show prompt in 1 second');
            const timer = setTimeout(() => {
                console.log('[PWA React] Showing iOS prompt');
                setShowPrompt(true);
            }, 1000); // Show after 1 second
            return () => clearTimeout(timer);
        }

        // Listen for Android install prompt
        const handleBeforeInstallPrompt = (e) => {
            console.log('[PWA React] beforeinstallprompt event fired');
            e.preventDefault();
            setDeferredPrompt(e);
            setTimeout(() => {
                console.log('[PWA React] Showing Android prompt');
                setShowPrompt(true);
            }, 1500); // Show after 1.5 seconds
        };

        window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
        
        console.log('[PWA React] Component initialized, waiting for install prompt event');

        return () => {
            window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
        };
    }, []);

    const handleDismiss = (saveDismissal = true) => {
        setShowPrompt(false);
        if (saveDismissal) {
            localStorage.setItem('pwa-install-prompt-dismissed', 'true');
        }
    };

    const handleInstall = async () => {
        if (isIOS) {
            // iOS instructions are already shown, just dismiss
            handleDismiss(true);
        } else if (deferredPrompt) {
            // Show Android install prompt
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response: ${outcome}`);
            setDeferredPrompt(null);
            handleDismiss(true);
        }
    };

    // Don't render if app is installed or prompt shouldn't show
    if (isStandalone || !showPrompt) {
        return null;
    }

    return (
        <div className="fixed inset-0 bg-black bg-opacity-75 z-[9999] flex items-center justify-center p-4">
            <div className="bg-[#050505] border border-[#1fff9c] rounded-2xl p-6 max-w-md w-full shadow-xl">
                <div className="text-center mb-6">
                    <div className="inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-[#1fff9c]/40 bg-[#041207] text-[#1fff9c] mb-4">
                        <svg className="h-8 w-8" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 className="text-xl font-semibold text-white mb-2">Install App</h2>
                    <p className="text-sm text-gray-400">Add this app to your home screen for quick access</p>
                </div>
                
                {isIOS ? (
                    // iOS Instructions
                    <div className="space-y-4 mb-6">
                        <div className="flex items-start space-x-3 text-white">
                            <span className="flex-shrink-0 w-6 h-6 rounded-full bg-[#1fff9c] text-black font-semibold flex items-center justify-center text-sm">1</span>
                            <p className="text-sm pt-0.5">Tap the <span className="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-700 text-white text-xs font-semibold">Share</span> button at the bottom of your screen</p>
                        </div>
                        <div className="flex items-start space-x-3 text-white">
                            <span className="flex-shrink-0 w-6 h-6 rounded-full bg-[#1fff9c] text-black font-semibold flex items-center justify-center text-sm">2</span>
                            <p className="text-sm pt-0.5">Scroll down and tap <span className="inline-flex items-center justify-center px-2 py-1 rounded bg-gray-700 text-white text-xs font-semibold">
                                <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add to Home Screen
                            </span></p>
                        </div>
                        <div className="flex items-start space-x-3 text-white">
                            <span className="flex-shrink-0 w-6 h-6 rounded-full bg-[#1fff9c] text-black font-semibold flex items-center justify-center text-sm">3</span>
                            <p className="text-sm pt-0.5">Tap <span className="inline-flex items-center justify-center px-2 py-1 rounded bg-gray-700 text-white text-xs font-semibold">Add</span> to confirm</p>
                        </div>
                    </div>
                ) : (
                    // Android Instructions
                    <div className="mb-6 text-center">
                        <p className="text-sm text-gray-300 mb-4">
                            Tap the button below to install this app on your device for a better experience.
                        </p>
                    </div>
                )}
                
                <div className="flex gap-3">
                    <button 
                        onClick={() => handleDismiss(true)}
                        className="flex-1 px-4 py-2 rounded-xl border border-gray-600 text-gray-300 hover:bg-gray-800 transition-colors text-sm font-medium"
                    >
                        Maybe Later
                    </button>
                    <button 
                        onClick={handleInstall}
                        className="flex-1 px-4 py-2 rounded-xl bg-[#1fff9c] text-black hover:brightness-110 transition-colors text-sm font-semibold"
                    >
                        {isIOS ? 'Got it!' : 'Install'}
                    </button>
                </div>
            </div>
        </div>
    );
};

export default PWAInstallPrompt;

