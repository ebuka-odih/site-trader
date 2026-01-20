# PWA Debug Guide

## Issue: Install Prompt Not Showing

If the PWA install prompt is not appearing, follow these debugging steps:

### 1. Check Browser Console

Open your browser's developer console (F12) and look for PWA-related messages:

```
[PWA] Initializing install prompt...
[PWA] Is iOS: true/false
[PWA] Is installed: true/false
[PWA] Has seen prompt: true/false
```

### 2. Check if Scripts are Loading

Open DevTools → Network tab and verify:
- `/js/pwa-sw-register.js` loads successfully (200 status)
- `/js/pwa-install-prompt.js` loads successfully (200 status)
- `/sw.js` loads successfully (200 status)
- `/manifest.json` loads successfully (200 status)

### 3. Check Manifest Route

Visit: `http://your-domain.com/manifest.json`

You should see a JSON response with app details. If you get 404:
- Clear route cache: `php artisan route:clear`
- Check route registration in `routes/web.php`

### 4. Check Service Worker

Open DevTools → Application → Service Workers

You should see:
- Status: Activated and running
- Scope: Your domain

If not registered:
- Check console for errors
- Ensure site is served over HTTPS (or localhost)

### 5. Check Local Storage

Open DevTools → Application → Local Storage → Your domain

Look for:
- `pwa-install-prompt-dismissed` - should be null/undefined on first visit
- If set to "true", clear it and refresh

### 6. Test on Debug Page

Visit: `http://your-domain.com/pwa-debug.html`

This page will show:
- Service worker status
- Manifest loading status
- Device detection
- Test buttons to trigger prompt manually

### 7. Common Issues

#### Issue: Prompt Already Dismissed
**Solution**: Clear localStorage or wait 7 days for auto-reset

#### Issue: App Already Installed
**Solution**: Uninstall the app or test in incognito mode

#### Issue: Not HTTPS
**Solution**: Service workers require HTTPS (except localhost)

#### Issue: Scripts Not Loading
**Solution**: 
- Check file permissions
- Verify files exist in `public/js/`
- Check browser console for 404 errors

#### Issue: iOS Not Showing
**Solution**:
- Ensure you're in Safari (not Chrome on iOS)
- Check if `standalone` mode is detected
- Clear localStorage and refresh

#### Issue: Android Not Showing
**Solution**:
- Ensure you're in Chrome browser
- Check if `beforeinstallprompt` event fires (see console)
- May need to meet PWA installability criteria

### 8. Manual Testing

Open browser console and run:

```javascript
// Check if prompt is available
console.log(window.pwaInstallPrompt);

// Manually trigger iOS prompt
window.pwaInstallPrompt.triggerInstall();

// Clear dismissed status
localStorage.removeItem('pwa-install-prompt-dismissed');
location.reload();
```

### 9. PWA Installability Criteria

For Android Chrome, the app must meet:
- ✅ Has a web manifest
- ✅ Served over HTTPS (or localhost)
- ✅ Has a registered service worker
- ✅ Has at least one icon (192x192 or larger)
- ✅ Has a `start_url` in manifest

### 10. Force Show Prompt (For Testing)

Add this to browser console:

```javascript
// Force show iOS prompt
localStorage.removeItem('pwa-install-prompt-dismissed');
if (window.pwaInstallPrompt) {
  window.pwaInstallPrompt.hasSeenPrompt = false;
  window.pwaInstallPrompt.showIOSInstructions();
}

// Force show Android prompt (if deferredPrompt exists)
if (window.pwaInstallPrompt && window.pwaInstallPrompt.deferredPrompt) {
  window.pwaInstallPrompt.showAndroidPrompt();
}
```

### Quick Fix Checklist

1. ✅ Clear route cache: `php artisan route:clear`
2. ✅ Clear browser cache and reload
3. ✅ Check manifest is accessible: `/manifest.json`
4. ✅ Verify scripts are loading (Network tab)
5. ✅ Clear localStorage: Remove `pwa-install-prompt-dismissed`
6. ✅ Check browser console for errors
7. ✅ Test in incognito/private mode
8. ✅ Ensure HTTPS (or localhost)

### Still Not Working?

1. Check the browser console for specific errors
2. Verify all files are in correct locations
3. Test on a different device/browser
4. Check if any browser extensions are blocking PWA features
5. Ensure the site meets all PWA installability criteria

