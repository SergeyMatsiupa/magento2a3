define([
    'jquery',
    'Magento_Ui/js/form/element/file-uploader',
    'mage/translate'
], function ($, FileUploader, $t) {
    'use strict';

    console.log('file-uploader.js: Script loaded');

    return FileUploader.extend({
        defaults: {
            uploads: 0
        },

        initialize: function () {
            this._super();
            console.log('Custom file-uploader initialized');
            console.log('Upload URL set to: ', this.uploaderConfig.url);
            console.log('Uploader config: ', this.uploaderConfig);
            console.log('Initial value: ', this.value());
            console.log('Element name: ', this.name);
            return this;
        },

        onBeforeFileUpload: function (e, data) {
            console.log('Before file upload: ', data);
            this._super(e, data);
            return this;
        },

        onUpload: function (e, data) {
            console.log('Upload event triggered: ', data);
            this._super(e, data);
            return this;
        },

        onFileUploaded: function (e, data) {
            console.log('File uploaded event triggered');
            console.log('Raw data received: ', data);
            if (data.result && !data.result.error) {
                var fileData = {
                    name: data.result.name,
                    size: data.result.size,
                    url: data.result.url
                };
                console.log('Form data updated: ', fileData);
                this.value([fileData]);
                this.uploads++;
            } else {
                console.error('Upload error: ', data.result.error);
            }
            return this;
        }
    });
});