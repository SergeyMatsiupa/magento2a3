define([
    'jquery',
    'Magento_Ui/js/form/element/file-uploader',
    'mage/translate',
    'mage/url',
    'Magento_Ui/js/modal/alert'
], function ($, FileUploader, $t, urlBuilder, alert) {
    'use strict';

    return FileUploader.extend({
        /**
         * Initialize component
         *
         * @returns {Object} Chainable
         */
        initialize: function () {
            this._super();
            console.log('Custom file-uploader initialized');

            // Ensure uploaderConfig is initialized
            if (!this.uploaderConfig) {
                this.uploaderConfig = {};
            }

            // Set custom upload URL using Magento URL builder
            this.uploaderConfig.url = this.uploaderConfig.url || urlBuilder.build('lessonone/lesson/upload');
            console.log('Upload URL set to: ' + this.uploaderConfig.url);

            // Add event listeners for upload events
            this.on('beforeFileUpload', this.onBeforeFileUpload.bind(this));
            this.on('fileUploaded', this.onFileUploaded.bind(this));
            this.on('fileUploadError', this.onFileUploadError.bind(this));

            // Log uploaderConfig for debugging
            console.log('Uploader config: ', JSON.stringify(this.uploaderConfig));

            return this;
        },

        /**
         * Handle before file upload
         *
         * @param {Object} event
         * @param {Object} data
         */
        onBeforeFileUpload: function (event, data) {
            console.log('Before file upload: ', JSON.stringify(data));
        },

        /**
         * Handle successful file upload
         *
         * @param {Object} event
         * @param {Object} data
         */
        onFileUploaded: function (event, data) {
            console.log('File uploaded event triggered');
            console.log('Raw data received: ', JSON.stringify(data));

            // Check if the response has an error
            if (data.error) {
                console.log('Error in response: ', data.message);
                this.notifyError(data.message || $t('File upload failed.'));
                return;
            }

            // Check if the response has the required fields
            if (data.file && data.size) {
                console.log('File and size present in response');
                this.notifySuccess($t('File uploaded successfully: ') + data.file);

                // Update form data with file information
                const fileData = {
                    name: data.name || data.file,
                    file: data.file,
                    size: data.size,
                    path: data.path || '', // Changed to path to match response
                    type: data.type || 'application/octet-stream'
                };
                this.value([fileData]); // fileUploader expects an array
                console.log('Form data updated: ', JSON.stringify(this.value()));
            } else {
                console.log('File or size missing in response');
                this.notifyError($t('File upload succeeded, but no file data returned.'));
            }
        },

        /**
         * Handle file upload error
         *
         * @param {Object} event
         * @param {Object} data
         */
        onFileUploadError: function (event, data) {
            console.log('File upload error: ', JSON.stringify(data));
            var errorMessage = data.message || $t('File upload failed.');
            this.notifyError(errorMessage);
        },

        /**
         * Show success notification
         *
         * @param {String} message
         */
        notifySuccess: function (message) {
            alert({
                title: $t('Success'),
                content: message
            });
        },

        /**
         * Show error notification
         *
         * @param {String} message
         */
        notifyError: function (message) {
            alert({
                title: $t('Error'),
                content: message
            });
        }
    });
});