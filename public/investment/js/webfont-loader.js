// Local WebFont loader replacement
// This replaces the Google WebFont loader with local font imports

document.addEventListener('DOMContentLoaded', function() {
    // Add Google Fonts via CSS import instead of WebFont.load
    const fontLink = document.createElement('link');
    fontLink.href = 'https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Outfit:wght@300;400;500;600;700&display=swap';
    fontLink.rel = 'stylesheet';
    document.head.appendChild(fontLink);
    
    // Add the touch detection functionality
    !function(o,c){
        var n=c.documentElement,t=" w-mod-";
        n.className+=t+"js";
        ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className+=t+"touch");
    }(window,document);
});
