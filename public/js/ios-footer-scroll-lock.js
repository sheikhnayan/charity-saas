/**
 * iOS White Space Fix
 * Fixes the white space below footer on iOS by constraining body height to actual content
 * Works with dynamic content without using 100vh or flexbox
 */

(function() {
    'use strict';

    // Detect if device is iOS
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    // Only apply on iOS devices
    if (!isIOS()) {
        console.log('📱 Not an iOS device, iOS white space fix not applied');
        return;
    }

    console.log('📱 iOS detected - applying white space fix');

    // Fix body height to match actual content
    function fixBodyHeight() {
        const footer = document.querySelector('footer');
        if (!footer) {
            console.warn('⚠️ Footer not found, skipping height fix');
            return;
        }

        // Get the actual bottom position of the footer
        const footerRect = footer.getBoundingClientRect();
        const footerBottom = footerRect.bottom + window.scrollY;
        
        // Get current body scroll height
        const bodyScrollHeight = document.body.scrollHeight;
        
        // If body extends beyond footer, constrain it
        if (bodyScrollHeight > footerBottom) {
            console.log('🔧 Body extends beyond footer, fixing...', {
                bodyScrollHeight,
                footerBottom,
                difference: bodyScrollHeight - footerBottom
            });
            
            // Set body max-height to footer bottom position
            document.body.style.maxHeight = footerBottom + 'px';
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'auto';
            
            console.log('✅ Body height constrained to footer position');
        }
    }

    // Remove the constraint and let body expand naturally
    function removeBodyHeightConstraint() {
        document.body.style.maxHeight = 'none';
        document.body.style.overflow = 'visible';
    }

    // Initialize fix
    function init() {
        console.log('🔒 Initializing iOS white space fix');

        // Apply fix after content loads
        fixBodyHeight();

        // Reapply on window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                console.log('📐 Window resized, reapplying fix');
                removeBodyHeightConstraint();
                setTimeout(fixBodyHeight, 100);
            }, 250);
        }, { passive: true });

        // Monitor DOM changes for dynamic content
        const observer = new MutationObserver(() => {
            // Debounce the fix
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                removeBodyHeightConstraint();
                setTimeout(fixBodyHeight, 100);
            }, 500);
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });

        console.log('✅ iOS white space fix initialized');
    }

    // Wait for DOM and footer to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(init, 500);
        });
    } else {
        setTimeout(init, 500);
    }

    // Also run after delays to catch late-loading content
    setTimeout(() => {
        removeBodyHeightConstraint();
        setTimeout(fixBodyHeight, 100);
    }, 1500);

    setTimeout(() => {
        removeBodyHeightConstraint();
        setTimeout(fixBodyHeight, 100);
    }, 3000);

    // Expose methods for manual control
    window.iOSWhiteSpaceFix = {
        apply: fixBodyHeight,
        remove: removeBodyHeightConstraint,
        reapply: function() {
            removeBodyHeightConstraint();
            setTimeout(fixBodyHeight, 100);
        }
    };

    console.log('📱 iOS white space fix ready - use window.iOSWhiteSpaceFix for manual control');
})();
