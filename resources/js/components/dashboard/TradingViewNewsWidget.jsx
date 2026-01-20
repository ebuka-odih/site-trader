import React, { useEffect, useRef } from 'react';

const TradingViewNewsWidget = () => {
    const widgetRef = useRef(null);
    const widgetId = useRef(`tradingview-news-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`);

    useEffect(() => {
        if (!widgetRef.current) return;

        const container = widgetRef.current;
        container.innerHTML = ''; // Clear any existing content

        // Create widget container structure
        const widgetContainer = document.createElement('div');
        widgetContainer.className = 'tradingview-widget-container';
        widgetContainer.id = widgetId.current;
        widgetContainer.style.width = '100%';
        widgetContainer.style.height = '600px';
        widgetContainer.style.border = 'none';
        widgetContainer.style.background = 'transparent';

        const widgetDiv = document.createElement('div');
        widgetDiv.className = 'tradingview-widget-container__widget';
        widgetDiv.style.height = '100%';
        widgetDiv.style.width = '100%';
        widgetDiv.style.border = 'none';
        widgetDiv.style.background = 'transparent';

        // Don't add copyright div to remove TradingView attribution text
        widgetContainer.appendChild(widgetDiv);
        container.appendChild(widgetContainer);

        // Create and load the TradingView news widget script
        const script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-timeline.js';
        script.async = true;
        script.innerHTML = JSON.stringify({
            feedMode: 'all_symbols',
            colorTheme: 'dark',
            isTransparent: true,
            displayMode: 'regular',
            width: '100%',
            height: 600,
            locale: 'en'
        });

        widgetDiv.appendChild(script);

        // Continuously hide TradingView copyright and branding elements
        const hideBranding = () => {
            // Hide copyright footer
            const copyrightElements = container.querySelectorAll('.tradingview-widget-copyright');
            copyrightElements.forEach((el) => {
                el.style.display = 'none';
                el.style.visibility = 'hidden';
                el.style.opacity = '0';
                el.style.height = '0';
                el.style.overflow = 'hidden';
            });

            // Hide any links or text containing "TradingView"
            const allElements = container.querySelectorAll('a, span, div, p');
            allElements.forEach((el) => {
                const text = el.textContent || '';
                if (text.includes('TradingView') || text.includes('Track all markets')) {
                    el.style.display = 'none';
                    el.style.visibility = 'hidden';
                    el.style.opacity = '0';
                }
            });

            // Remove borders from widget elements
            const widgetElements = container.querySelectorAll('*');
            widgetElements.forEach((el) => {
                const computedStyle = window.getComputedStyle(el);
                if (computedStyle.borderWidth !== '0px') {
                    el.style.border = 'none';
                }
            });
        };

        // Run immediately and then periodically
        hideBranding();
        const interval = setInterval(hideBranding, 500);
        
        // Stop checking after 10 seconds (enough time for widget to load)
        setTimeout(() => clearInterval(interval), 10000);

        return () => {
            clearInterval(interval);
            // Cleanup: remove widget container
            if (container && container.contains(widgetContainer)) {
                container.removeChild(widgetContainer);
            }
        };
    }, []);

    return (
        <div className="space-y-3">
            {/* News Header */}
            <div className="flex justify-between items-center px-1">
                <p className="text-sm font-semibold text-white uppercase">MARKET NEWS</p>
            </div>

            {/* TradingView News Widget */}
            <div className="rounded-lg bg-[#0a0a0a] overflow-hidden" style={{ border: 'none' }}>
                <style>{`
                    /* Hide TradingView branding and copyright */
                    .tradingview-widget-copyright,
                    .tradingview-widget-copyright *,
                    [class*="tradingview-widget-copyright"],
                    a[href*="tradingview.com"] {
                        display: none !important;
                        visibility: hidden !important;
                        opacity: 0 !important;
                        height: 0 !important;
                        width: 0 !important;
                        overflow: hidden !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    
                    /* Remove borders from widget */
                    .tradingview-widget-container,
                    .tradingview-widget-container__widget,
                    iframe[src*="tradingview"] {
                        border: none !important;
                        outline: none !important;
                        box-shadow: none !important;
                    }
                    
                    /* Hide any footer elements */
                    footer,
                    [class*="footer"],
                    [id*="footer"] {
                        display: none !important;
                    }
                `}</style>
                <div ref={widgetRef} className="w-full" style={{ minHeight: '600px', border: 'none' }}></div>
            </div>
        </div>
    );
};

export default TradingViewNewsWidget;

