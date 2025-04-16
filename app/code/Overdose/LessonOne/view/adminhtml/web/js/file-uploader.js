define([
    'jquery',
    'Magento_Ui/js/form/element/file-uploader'
], function ($, FileUploader) {
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

            return this;
        },

        /**
         * Get URL for file upload
         *
         * @returns {String}
         */
        getUploadUrl: function () {
            return window.location.origin + '/admin/lessonone/lesson/upload';
        }
    });
});