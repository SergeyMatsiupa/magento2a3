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
            this.on('fileUploaded', this.onFileUploaded.bind(this));
            this.on('fileUploadError', this.onFileUploadError.bind(this));
            this.on('beforeFileUpload', this.onBeforeFileUpload.bind(this));

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
            console.log('File uploaded: ', data);
            if (data.result && data.result.file && data.result.size) {
                this.notifySuccess($t('File uploaded successfully: ') + data.result.file);
                // Update form data with file information
                this.value({
                    file: data.result.file,
                    size: data.result.size,
                    url: data.result.url,
                    name: data.result.name,
                    type: data.result.type,
                    previewType: data.result.previewType,
                    previewUrl: data.result.previewUrl
                });
            } else {
                console.log('No file data returned: ', data);
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