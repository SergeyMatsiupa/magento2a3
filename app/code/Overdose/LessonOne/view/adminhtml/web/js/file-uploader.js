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

            // Set custom upload URL
            this.uploaderConfig.url = this.getUploadUrl();
            // Add event listener for upload completion
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
            return window.location.origin + '/admin/lessonone/lesson/upload';
        },

        /**
         * Handle successful file upload
         *
         * @param {Object} event
         * @param {Object} data
         */
        onFileUploaded: function (event, data) {
            if (data.result && data.result.file) {
                this.notifySuccess($t('File uploaded successfully: ') + data.result.file);
            }
        },

        /**
         * Handle file upload error
         *
         * @param {Object} event
         * @param {Object} data
         */
        onFileUploadError: function (event, data) {
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