var filebrowserImageBrowseUrl = baseUrl + '/laravel-filemanager?type=Images';
var filebrowserImageUploadUrl = baseUrl + '/laravel-filemanager/upload?type=Images&_token=' + $(
    'meta[name="csrf-token"]').attr('content');
var filebrowserBrowseUrl = baseUrl + '/laravel-filemanager?type=Files';
var filebrowserUploadUrl = baseUrl + '/laravel-filemanager/upload?type=Files&_token=' + $('meta[name="csrf-token"]')
    .attr('content');
var csrfToken = $('meta[name="csrf-token"]').attr('content');

$('textarea#abstract').tinymce({
    height: 500,
    menubar: false,
    plugins: [
        'a11ychecker', 'accordion', 'advlist', 'anchor', 'autolink', 'autosave',
        'charmap', 'code', 'codesample', 'directionality', 'emoticons', 'exportpdf',
        'exportword', 'fullscreen', 'help', 'image', 'importcss', 'importword',
        'insertdatetime', 'link', 'lists', 'markdown', 'math', 'media', 'nonbreaking',
        'pagebreak', 'preview', 'quickbars', 'save', 'searchreplace', 'table',
        'visualblocks', 'visualchars', 'wordcount'
        ],
        toolbar: 'undo redo | accordion accordionremove | ' +
        'importword exportword exportpdf | math | ' +
        'blocks fontfamily fontsize | bold italic underline strikethrough | ' +
        'align numlist bullist | image | table media | image | ' +
        'lineheight outdent indent | forecolor backcolor removeformat | ' +
        'charmap emoticons | code fullscreen preview | save print | ' +
        'pagebreak anchor codesample | ltr rtl',
    menubar: 'file edit view insert format tools table help',
    // Image upload handler for paste/drag-drop (uses Laravel File Manager upload URL)
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

                // Laravel File Manager may return different response formats
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
                    // If not JSON, might be a direct URL string or HTML
                    // Laravel File Manager sometimes returns the URL directly
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
            // Laravel File Manager expects 'upload' as the field name
            formData.append('upload', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        });
    },
    // Laravel File Manager integration for browsing
    file_picker_callback: function(callback, value, meta) {
        var x = window.innerWidth || document.documentElement.clientWidth || document
            .getElementsByTagName('body')[0].clientWidth;
        var y = window.innerHeight || document.documentElement.clientHeight || document
            .getElementsByTagName('body')[0].clientHeight;
        var type = meta.filetype;
        var url;

        if (type == 'image') {
            url = filebrowserImageBrowseUrl;
        } else {
            url = filebrowserBrowseUrl;
        }

        // Store the callback for Laravel File Manager to use
        window.tinymceFilePickerCallback = callback;

        // Open Laravel File Manager in a popup window
        window.open(url, 'FileManager', 'width=' + x * 0.8 + ',height=' + y * 0.8 + ',resizable=yes');

        // Laravel File Manager will call window.SetUrl when a file is selected
        window.SetUrl = function(items) {
            if (window.tinymceFilePickerCallback) {
                if (items && items.length > 0) {
                    // Get the URL from the selected item
                    // items can be an array of objects with 'url' property, or just URLs
                    var fileUrl = items[0].url || items[0];
                    window.tinymceFilePickerCallback(fileUrl);
                }
                // Clear the callback after use
                window.tinymceFilePickerCallback = null;
            }
        };
    },
    // Image settings
    image_advtab: true,
    image_caption: true,
    image_title: true,
    image_description: false,
    // Content direction
    directionality: localLanguage != 'en' ? 'rtl' : 'ltr',
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