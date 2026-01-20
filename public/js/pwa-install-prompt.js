/**
 * PWA Install Prompt Handler
 * Handles "Add to Home Screen" prompts for iOS and Android
 */

class PWAInstallPrompt {
  constructor() {
    this.deferredPrompt = null;
    this.isIOS = this.detectIOS();
    this.isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                        window.navigator.standalone === true;
    this.hasSeenPrompt = this.getStoredPromptStatus();
    
    this.init();
  }

  /**
   * Detect if device is iOS
   */
  detectIOS() {
    const userAgent = window.navigator.userAgent.toLowerCase();
    const isIOS = /iphone|ipad|ipod/.test(userAgent);
    const isIPadOS = navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1;
    return isIOS || isIPadOS;
  }

  /**
   * Check if app is already installed
   */
  isInstalled() {
    return this.isStandalone;
  }

  /**
   * Get stored prompt status from localStorage
   */
  getStoredPromptStatus() {
    try {
      return localStorage.getItem('pwa-install-prompt-dismissed') === 'true';
    } catch (e) {
      return false;
    }
  }

  /**
   * Store prompt dismissal status
   */
  setPromptDismissed() {
    try {
      localStorage.setItem('pwa-install-prompt-dismissed', 'true');
      this.hasSeenPrompt = true;
    } catch (e) {
      console.error('Failed to save prompt status:', e);
    }
  }

  /**
   * Show iOS installation instructions
   */
  showIOSInstructions() {
    // Check if already dismissed
    if (this.hasSeenPrompt) {
      return;
    }

    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.id = 'pwa-install-overlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
    overlay.innerHTML = `
      <div class="bg-[#050505] border border-[#1fff9c] rounded-2xl p-6 max-w-md w-full shadow-xl">
        <div class="text-center mb-6">
          <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-[#1fff9c]/40 bg-[#041207] text-[#1fff9c] mb-4">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
          <h2 class="text-xl font-semibold text-white mb-2">Install App</h2>
          <p class="text-sm text-gray-400">Add this app to your home screen for quick access</p>
        </div>
        
        <div class="space-y-4 mb-6">
          <div class="flex items-start space-x-3 text-white">
            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#1fff9c] text-black font-semibold flex items-center justify-center text-sm">1</span>
            <p class="text-sm pt-0.5">Tap the <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-700 text-white text-xs font-semibold">Share</span> button at the bottom of your screen</p>
          </div>
          <div class="flex items-start space-x-3 text-white">
            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#1fff9c] text-black font-semibold flex items-center justify-center text-sm">2</span>
            <p class="text-sm pt-0.5">Scroll down and tap <span class="inline-flex items-center justify-center px-2 py-1 rounded bg-gray-700 text-white text-xs font-semibold">
              <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Add to Home Screen
            </span></p>
          </div>
          <div class="flex items-start space-x-3 text-white">
            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-[#1fff9c] text-black font-semibold flex items-center justify-center text-sm">3</span>
            <p class="text-sm pt-0.5">Tap <span class="inline-flex items-center justify-center px-2 py-1 rounded bg-gray-700 text-white text-xs font-semibold">Add</span> to confirm</p>
          </div>
        </div>
        
        <div class="flex gap-3">
          <button id="pwa-install-dismiss" class="flex-1 px-4 py-2 rounded-xl border border-gray-600 text-gray-300 hover:bg-gray-800 transition-colors text-sm font-medium">
            Maybe Later
          </button>
          <button id="pwa-install-got-it" class="flex-1 px-4 py-2 rounded-xl bg-[#1fff9c] text-black hover:brightness-110 transition-colors text-sm font-semibold">
            Got it!
          </button>
        </div>
      </div>
    `;

    document.body.appendChild(overlay);

    // Handle dismiss button
    document.getElementById('pwa-install-dismiss').addEventListener('click', () => {
      this.dismissPrompt(overlay, true);
    });

    // Handle got it button
    document.getElementById('pwa-install-got-it').addEventListener('click', () => {
      this.dismissPrompt(overlay, true);
    });

    // Close on overlay click
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) {
        this.dismissPrompt(overlay, true);
      }
    });
  }

  /**
   * Show Android installation prompt
   */
  async showAndroidPrompt() {
    if (!this.deferredPrompt) {
      return;
    }

    // Show the install prompt
    this.deferredPrompt.prompt();

    // Wait for user response
    const { outcome } = await this.deferredPrompt.userChoice;

    console.log(`User response to install prompt: ${outcome}`);

    // Clear the deferred prompt
    this.deferredPrompt = null;

    // Remove the install button if user accepted
    if (outcome === 'accepted') {
      this.setPromptDismissed();
    }
  }

  /**
   * Dismiss the prompt
   */
  dismissPrompt(overlay, saveStatus = false) {
    if (overlay && overlay.parentNode) {
      overlay.parentNode.removeChild(overlay);
    }
    if (saveStatus) {
      this.setPromptDismissed();
    }
  }

  /**
   * Check if should show prompt
   */
  shouldShowPrompt() {
    // Don't show if already installed
    if (this.isInstalled()) {
      return false;
    }

    // Don't show if already dismissed
    if (this.hasSeenPrompt) {
      return false;
    }

    // Show for iOS or Android
    return this.isIOS || this.deferredPrompt !== null;
  }

  /**
   * Initialize the PWA install prompt
   */
  init() {
    console.log('[PWA] Initializing install prompt...');
    console.log('[PWA] Is iOS:', this.isIOS);
    console.log('[PWA] Is installed:', this.isInstalled());
    console.log('[PWA] Has seen prompt:', this.hasSeenPrompt);
    
    // Don't show if already installed
    if (this.isInstalled()) {
      console.log('[PWA] App already installed, skipping prompt');
      return;
    }

    // Listen for beforeinstallprompt event (Android/Chrome)
    window.addEventListener('beforeinstallprompt', (e) => {
      console.log('[PWA] beforeinstallprompt event fired');
      
      // Prevent the mini-infobar from appearing
      e.preventDefault();
      
      // Stash the event so it can be triggered later
      this.deferredPrompt = e;
      
      // Show prompt after a delay (give user time to see the page)
      setTimeout(() => {
        if (this.shouldShowPrompt() && !this.isIOS) {
          console.log('[PWA] Showing Android install prompt');
          this.showAndroidPrompt();
        } else {
          console.log('[PWA] Skipping Android prompt - shouldShowPrompt:', this.shouldShowPrompt(), 'isIOS:', this.isIOS);
        }
      }, 3000);
    });

    // Show iOS instructions
    if (this.isIOS) {
      const showIOSPrompt = () => {
        if (this.shouldShowPrompt()) {
          console.log('[PWA] Showing iOS install instructions');
          this.showIOSInstructions();
        } else {
          console.log('[PWA] Skipping iOS prompt - shouldShowPrompt:', this.shouldShowPrompt());
        }
      };

      // Check if page is already loaded
      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        // Page already loaded, show after short delay
        setTimeout(showIOSPrompt, 2000);
      } else {
        // Wait for page load
        window.addEventListener('load', () => {
          setTimeout(showIOSPrompt, 2000);
        });
      }
    }

    // Listen for app installed event
    window.addEventListener('appinstalled', () => {
      console.log('[PWA] App was installed');
      this.setPromptDismissed();
      this.deferredPrompt = null;
    });

    // Reset prompt status after 7 days (optional - allows showing again)
    this.schedulePromptReset();
  }

  /**
   * Schedule reset of prompt status after 7 days
   */
  schedulePromptReset() {
    try {
      const lastReset = localStorage.getItem('pwa-prompt-last-reset');
      const now = Date.now();
      const sevenDays = 7 * 24 * 60 * 60 * 1000;

      if (!lastReset || (now - parseInt(lastReset)) > sevenDays) {
        localStorage.removeItem('pwa-install-prompt-dismissed');
        localStorage.setItem('pwa-prompt-last-reset', now.toString());
        this.hasSeenPrompt = false;
      }
    } catch (e) {
      console.error('Failed to reset prompt status:', e);
    }
  }

  /**
   * Manually trigger install prompt (for custom buttons)
   */
  triggerInstall() {
    if (this.isIOS) {
      this.showIOSInstructions();
    } else if (this.deferredPrompt) {
      this.showAndroidPrompt();
    } else {
      console.warn('[PWA] Install prompt not available');
    }
  }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.pwaInstallPrompt = new PWAInstallPrompt();
  });
} else {
  window.pwaInstallPrompt = new PWAInstallPrompt();
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
  module.exports = PWAInstallPrompt;
}

