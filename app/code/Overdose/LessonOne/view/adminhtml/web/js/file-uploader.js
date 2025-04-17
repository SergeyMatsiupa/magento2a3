define([
    'jquery',
    'Magento_Ui/js/form/element/file-uploader',
    'mage/translate',
    'mage/url',
    'Magento_Ui/js/modal/alert' // Используем для уведомлений
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

            return this;
        },

        /**
         * Handle before file upload
         *
         * @param {Object} event
         * @param {Object} data
         */
        onBeforeFileUpload: function (event, data) {
            console.log('Before file upload: ', data);
        },

        /**
         * Handle successful file upload
         *
         * @param {Object} event
         * @param {Object} data
         */
        onFileUploaded: function (event, data) {
            console.log('File uploaded event triggered');
            console.log('Raw data received: ', data);

            if (data.result) {
                console.log('Result field present: ', data.result);
                if (data.result.file && data.result.size) {
                    console.log('File and size present in result');
                    this.notifySuccess($t('File uploaded successfully: ') + data.result.file);
                    // Update form data with file information
                    this.value({
                        file: data.result.file,
                        size: data.result.size,
                        url: data.result.url || '',
                        name: data.result.name || data.result.file,
                        type: data.result.type || 'application/octet-stream',
                        previewType: data.result.previewType || 'document',
                        previewUrl: data.result.previewUrl || ''
                    });
                    console.log('Form data updated: ', this.value());
                } else {
                    console.log('File or size missing in result');
                    this.notifyError($t('File upload succeeded, but no file data returned.'));
                }
            } else {
                console.log('Result field missing in response');
                this.notifyError($t('Invalid response format: no result field.'));
            }
        },

        /**
         * Handle file upload error
         *
         * @param {Object} event
         * @param {Object} data
         */
        onFileUploadError: function (event, data) {
            console.log('File upload error: ', data);
            var errorMessage = data.error || $t('File upload failed.');
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