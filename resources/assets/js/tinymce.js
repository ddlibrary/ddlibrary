var filebrowserImageUploadUrl = baseUrl + '/upload-abstract-image';
var csrfToken = $('meta[name="csrf-token"]').attr('content');

tinymce.init({
    selector: 'textarea#abstract',
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