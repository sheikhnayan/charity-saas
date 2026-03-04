/**
 * Custom Fonts Configuration for CKEditor
 * Automatically loads uploaded custom fonts into all CKEditor instances
 */

(function() {
    'use strict';

    // Fetch custom fonts from the server
    async function loadCustomFonts() {
        try {
            const response = await fetch('/fonts/custom.css');
            if (!response.ok) {
                console.warn('Could not load custom fonts CSS');
                return [];
            }

            const css = await response.text();
            
            // Inject the custom fonts CSS into the page
            const style = document.createElement('style');
            style.textContent = css;
            document.head.appendChild(style);

            // Parse font-family names from the CSS
            const fontFamilies = [];
            const fontFaceRegex = /font-family:\s*['"]([^'"]+)['"]/g;
            let match;
            
            while ((match = fontFaceRegex.exec(css)) !== null) {
                const fontFamily = match[1];
                if (!fontFamilies.includes(fontFamily)) {
                    fontFamilies.push(fontFamily);
                }
            }

            return fontFamilies;
        } catch (error) {
            console.error('Error loading custom fonts:', error);
            return [];
        }
    }

    // Build font menu configuration for CKEditor
    function buildFontConfig(customFonts) {
        const defaultFonts = [
            'Arial/Arial, Helvetica, sans-serif',
            'Courier New/Courier New, Courier, monospace',
            'Georgia/Georgia, serif',
            'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif',
            'Tahoma/Tahoma, Geneva, sans-serif',
            'Times New Roman/Times New Roman, Times, serif',
            'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif',
            'Verdana/Verdana, Geneva, sans-serif'
        ];

        // Add custom fonts
        const customFontItems = customFonts.map(font => `${font}/${font}, sans-serif`);
        
        return [...customFontItems, ...defaultFonts];
    }

    // Enhanced ClassicEditor.create wrapper
    window.ClassicEditorWithFonts = {
        create: async function(element, config = {}) {
            // Load custom fonts first
            const customFonts = await loadCustomFonts();
            const fontConfig = buildFontConfig(customFonts);

            // Merge font configuration with provided config
            const editorConfig = {
                ...config,
                fontFamily: {
                    options: fontConfig,
                    supportAllValues: true
                },
                toolbar: config.toolbar || [
                    'heading', '|',
                    'fontFamily', 'fontSize', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'alignment', '|',
                    'indent', 'outdent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                    'undo', 'redo'
                ]
            };

            return ClassicEditor.create(element, editorConfig);
        }
    };

    // Auto-enhance existing ClassicEditor calls on page load
    document.addEventListener('DOMContentLoaded', async function() {
        // Wait a bit to let other scripts initialize
        setTimeout(async () => {
            // If there are textareas with specific classes, auto-initialize
            const editors = document.querySelectorAll('textarea.auto-editor, .ckeditor-editor');
            
            if (editors.length > 0) {
                const customFonts = await loadCustomFonts();
                const fontConfig = buildFontConfig(customFonts);

                editors.forEach(async (textarea) => {
                    if (!textarea.classList.contains('editor-initialized')) {
                        try {
                            await ClassicEditor.create(textarea, {
                                fontFamily: {
                                    options: fontConfig,
                                    supportAllValues: true
                                },
                                toolbar: [
                                    'heading', '|',
                                    'fontFamily', 'fontSize', '|',
                                    'bold', 'italic', 'underline', 'strikethrough', '|',
                                    'link', 'bulletedList', 'numberedList', '|',
                                    'alignment', '|',
                                    'indent', 'outdent', '|',
                                    'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                                    'undo', 'redo'
                                ]
                            });
                            textarea.classList.add('editor-initialized');
                        } catch (error) {
                            console.error('Error initializing editor:', error);
                        }
                    }
                });
            }
        }, 500);
    });

    // Store the original ClassicEditor for advanced usage
    window.OriginalClassicEditor = window.ClassicEditor;

    console.log('Custom Fonts for CKEditor loaded successfully');
})();
