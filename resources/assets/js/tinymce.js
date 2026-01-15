// Wait for DOM to be ready before initializing TinyMCE
(function() {
    'use strict';
    
    // Prevent multiple initializations
    var isInitializing = false;
    var isInitialized = false;
    
    // Get CSRF token using vanilla JavaScript (no jQuery dependency)
    function getCsrfToken() {
        var metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : '';
    }
    
    // Initialize TinyMCE when DOM is ready
    function initTinyMCE() {
        // Prevent multiple simultaneous initializations
        if (isInitializing || isInitialized) {
            return;
        }
        
        isInitializing = true;
        // Check if TinyMCE is loaded
        if (typeof tinymce === 'undefined') {
            console.error('TinyMCE is not loaded');
            // Retry after a short delay in case the script is still loading
            setTimeout(initTinyMCE, 100);
            return;
        }
        
        // Check if TinyMCE.init is available
        if (typeof tinymce.init !== 'function') {
            console.error('TinyMCE.init is not available');
            setTimeout(initTinyMCE, 100);
            return;
        }
        
        // Check if required textareas exist
        var editorTextareas = document.querySelectorAll('textarea.editor');
        if (editorTextareas.length === 0) {
            return; // No editors to initialize
        }
        
        // Verify each textarea is actually in the DOM
        var validTextareas = [];
        for (var i = 0; i < editorTextareas.length; i++) {
            var textarea = editorTextareas[i];
            // Check if element is actually in the document
            if (textarea && document.body.contains(textarea)) {
                validTextareas.push(textarea);
            }
        }
        
        if (validTextareas.length === 0) {
            isInitializing = false;
            return; // No valid editors to initialize
        }
        
        // Remove any existing TinyMCE instances to prevent conflicts
        try {
            if (tinymce.editors && tinymce.editors.length > 0) {
                tinymce.editors.forEach(function(editor) {
                    try {
                        if (editor && editor.remove) {
                            editor.remove();
                        }
                    } catch (e) {
                        // Ignore errors when removing editors
                    }
                });
            }
        } catch (e) {
            // Ignore errors during cleanup
        }
        
        var csrfToken = getCsrfToken();
        
        // Initialize TinyMCE for all textareas with .editor class
        try {
            tinymce.init({
            selector: 'textarea.editor',
            license_key: 'gpl',
            height: 500,
            menubar: false,
            // Only FREE plugins
            plugins: [
                'advlist', 'anchor', 'autolink', 'charmap', 'code', 'codesample',
                'directionality', 'fullscreen', 'help', 'image', 'insertdatetime',
                'link', 'lists', 'media', 'nonbreaking', 'pagebreak', 'preview',
                'searchreplace', 'table', 'visualblocks', 'visualchars', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                'bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'numlist bullist | outdent indent | ' +
                'forecolor backcolor removeformat | ' +
                'image link media table | ' +
                'charmap codesample | code fullscreen preview | ' +
                'pagebreak anchor searchreplace | ltr rtl | help',
            menubar: 'file edit view insert format tools table help',
            // Setup callback to ensure element exists before TinyMCE tries to attach listeners
            setup: function(editor) {
                // This callback runs when each editor instance is created
                // It ensures the element is ready before TinyMCE attaches event listeners
                editor.on('init', function() {
                    // Editor is fully initialized
                    try {
                        var element = editor.getElement();
                        if (!element || !document.body.contains(element)) {
                            console.warn('TinyMCE editor element not found in DOM:', editor.id);
                        }
                    } catch (e) {
                        console.warn('Error checking TinyMCE editor element:', e);
                    }
                });
            },
            // Callback when initialization fails
            init_instance_callback: function(editor) {
                try {
                    // Verify the editor element exists
                    var element = editor.getElement();
                    if (!element) {
                        console.error('TinyMCE editor element is null for editor:', editor.id);
                        return;
                    }
                    if (!document.body.contains(element)) {
                        console.error('TinyMCE editor element not in DOM:', editor.id);
                        return;
                    }
                } catch (e) {
                    console.error('Error in TinyMCE init_instance_callback:', e);
                }
            },
            // Image upload handler for paste/drag-drop
            images_upload_handler: function(blobInfo, progress) {
                return new Promise(function(resolve, reject) {
                    var xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', filebrowserImageUploadUrl);

                    xhr.upload.onprogress = function(e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function() {
                        if (xhr.status === 403) {
                            reject({
                                message: 'HTTP Error: ' + xhr.status,
                                remove: true
                            });
                            return;
                        }

                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }

                        var responseText = xhr.responseText.trim();

                        // Try to parse as JSON first
                        try {
                            var json = JSON.parse(responseText);
                            // Check for common response formats
                            if (json.url) {
                                resolve(json.url);
                            } else if (json.location) {
                                resolve(json.location);
                            } else if (json.path) {
                                resolve(baseUrl + json.path);
                            } else {
                                reject('Invalid response format: ' + responseText);
                            }
                        } catch (e) {
                            if (responseText.startsWith('http://') || responseText.startsWith(
                                    'https://') || responseText.startsWith('/')) {
                                resolve(responseText);
                            } else {
                                reject('Invalid response: ' + responseText);
                            }
                        }
                    };

                    xhr.onerror = function() {
                        reject('Image upload failed due to a XHR Transport error. Code: ' + xhr
                            .status);
                    };

                    var formData = new FormData();
                    // Use 'upload' as the field name
                    formData.append('upload', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', csrfToken);

                    xhr.send(formData);
                });
            },
            // File picker disabled - users can drag & drop or paste images
            // Images will be uploaded via images_upload_handler above
            file_picker_callback: function(callback, value, meta) {
                // Trigger file input for image selection
                if (meta.filetype === 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function() {
                        var file = this.files[0];
                        var formData = new FormData();
                        formData.append('upload', file);
                        formData.append('_token', csrfToken);

                        // Show loading indicator
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', filebrowserImageUploadUrl);

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                try {
                                    var json = JSON.parse(xhr.responseText);
                                    callback(json.url || json.location, { alt: file.name });
                                } catch (e) {
                                    alert('Error uploading image');
                                }
                            } else {
                                alert('Image upload failed: ' + xhr.status);
                            }
                        };

                        xhr.onerror = function() {
                            alert('Image upload failed');
                        };

                        xhr.send(formData);
                    };

                    input.click();
                }
            },
            // Image settings
            image_advtab: true,
            image_caption: true,
            image_title: true,
            image_description: false,
            // Content direction
            directionality: typeof localLanguage !== 'undefined' && localLanguage != 'en' ? 'rtl' : 'ltr',
            // Content language
            content_langs: [{
                    title: 'English',
                    code: 'en'
                },
                {
                    title: 'فارسی',
                    code: 'fa'
                },
                {
                    title: 'پښتو',
                    code: 'ps'
                }
            ],
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            });
        } catch (error) {
            console.error('Error initializing TinyMCE:', error);
            isInitializing = false;
            return;
        }
        
        // Mark as initialized
        isInitialized = true;
        isInitializing = false;
        
        // Setup form submission handlers for all forms containing editor textareas
        // Use a small delay to ensure TinyMCE is fully initialized
        setTimeout(function() {
            setupFormHandlers();
        }, 100);
    }
    
    // Helper function to get TinyMCE editor instance from textarea element
    function getTinyMCEEditor(textarea) {
        if (!textarea || typeof tinymce === 'undefined') {
            return null;
        }
        
        // Try by ID first
        if (textarea.id) {
            var editor = tinymce.get(textarea.id);
            if (editor) {
                return editor;
            }
        }
        
        // Try by name
        if (textarea.name) {
            var editor = tinymce.get(textarea.name);
            if (editor) {
                return editor;
            }
        }
        
        // Find by iterating through all editors and matching the element
        var allEditors = tinymce.editors;
        for (var i = 0; i < allEditors.length; i++) {
            try {
                if (allEditors[i].getElement() === textarea) {
                    return allEditors[i];
                }
            } catch (e) {
                // Continue searching if getElement() fails
            }
        }
        
        return null;
    }
    
    // Setup form submission handlers
    function setupFormHandlers() {
        // Find all forms that contain editor textareas
        var forms = document.querySelectorAll('form');
        
        forms.forEach(function(form) {
            // Check if this form has any editor textareas
            var hasEditor = form.querySelector('textarea.editor');
            if (!hasEditor) {
                return; // Skip forms without editors
            }
            
            // Add submit event listener
            form.addEventListener('submit', function(e) {
                // Get textareas by name
                var summaryTextarea = form.querySelector('textarea[name="summary"]');
                var bodyTextarea = form.querySelector('textarea[name="body"]');
                
                // Validate summary if it exists
                if (summaryTextarea) {
                    var summaryEditor = getTinyMCEEditor(summaryTextarea);
                    if (summaryEditor) {
                        summaryEditor.save();
                        var summaryText = summaryEditor.getContent({ format: 'text' }).trim();
                        if (!summaryText) {
                            e.preventDefault();
                            alert('Summary field is required.');
                            summaryEditor.focus();
                            return false;
                        }
                    }
                }
                
                // Validate body if it exists
                if (bodyTextarea) {
                    var bodyEditor = getTinyMCEEditor(bodyTextarea);
                    if (bodyEditor) {
                        bodyEditor.save();
                        var bodyText = bodyEditor.getContent({ format: 'text' }).trim();
                        if (!bodyText) {
                            e.preventDefault();
                            alert('Body field is required.');
                            bodyEditor.focus();
                            return false;
                        }
                    }
                }
            });
        });
    }
    
    // Initialize when DOM is ready and TinyMCE is loaded
    function waitForTinyMCEAndInit() {
        if (typeof tinymce === 'undefined' || typeof tinymce.init !== 'function') {
            // TinyMCE not loaded yet, wait a bit more
            setTimeout(waitForTinyMCEAndInit, 50);
            return;
        }
        
        // TinyMCE is loaded, now check DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                // Add a small delay to ensure all DOM manipulations are complete
                setTimeout(initTinyMCE, 50);
            });
        } else {
            // DOM is already ready, but wait a bit to ensure everything is settled
            setTimeout(initTinyMCE, 50);
        }
    }
    
    // Start waiting for TinyMCE and DOM
    waitForTinyMCEAndInit();
})();
