define([
    'jquery',
    'Magento_Ui/js/form/element/file-uploader',
    'mage/translate'
], function ($, FileUploader, $t) {
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
            console.log('Uploader Config:', this.uploaderConfig);

            // Ensure uploaderConfig is applied
            this.uploaderConfig.url = this.uploaderConfig.url || this.getUploadUrl();
            console.log('Upload URL set to: ' + this.uploaderConfig.url);

            // Add event listeners for upload events
            this.on('fileUploaded', this.onFileUploaded.bind(this));
            this.on('fileUploadError', this.onFileUploadError.bind(this));

            return this;
        },

        /**
         * Get URL for file upload
         *
         * @returns {String}
         */
        getUploadUrl: function () {
            // Ensure correct admin URL
            return window.location.origin + '/admin/lessonone/lesson/upload';
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
            this.addMessage('success', message);
        },

        /**
         * Show error notification
         *
         * @param {String} message
         */
        notifyError: function (message) {
            this.addMessage('error', message);
        },

        /**
         * Add message to the notification area
         *
         * @param {String} type
         * @param {String} message
         */
        addMessage: function (type, message) {
            require(['Magento_Ui/js/lib/notification'], function (notification) {
                notification().add({
                    message: message,
                    messageType: type
                });
            });
        }
    });
});