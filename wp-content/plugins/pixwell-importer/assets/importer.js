/** Import Demo */
var RUBY_IMPORTER = (function (Module, $) {
    'use strict';

    Module.$processImport = false;
    Module.$imported = false;
    Module.$error = false;

    Module.init = function () {
        this.activePlugin();
        this.installPackaged();
        this.selectData();
        this.installDemo();
    };

    /** install & active repo plugins */
    Module.activePlugin = function () {
        $('.rb-demos .rb-activate-plugin').unbind('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var target = $(this);
            var url = target.attr('href');

            target.addClass('loading');
            target.html('<span class="rb-loading-info">Please wait...</span>');
            jQuery.ajax({
                type: 'GET',
                url: url,
                success: function (response) {
                    if (response.length > 0 && (response.match(/Plugin activated./gi))) {
                        target.find('.spinner').remove();
                        target.replaceWith('<span class="activate-info activated">Activated</span>');
                    } else {
                        window.onbeforeunload = null;
                        location.reload(true);
                    }
                }
            });
        });
    };

    Module.selectData = function () {

        var self = this;
        var rbDemos = $('.rb-demo-item');
        if (rbDemos.length > 0) {
            rbDemos.each(function () {
                self.importerBtnStatus($(this));
            });
        }

        $('.rb-importer-checkbox').unbind('click').on('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            var checkbox = jQuery(this);
            if (checkbox.data('checked') == 1) {
                checkbox.removeClass('checked');
                checkbox.data('checked', 0);
            } else {
                checkbox.addClass('checked');
                checkbox.data('checked', 1);
            }

            var outer = checkbox.parents('.demo-content');
            var name = checkbox.data('title');
            var wrap = checkbox.parents('.data-select');
            if (checkbox.data("checked") && 'rb_import_all' == name) {
                wrap.find('.rb_import_content').data("checked", 1).addClass('checked');
                wrap.find('.rb_import_pages').data("checked", 1).addClass('checked');
                wrap.find('.rb_import_tops').data("checked", 1).addClass('checked');
                wrap.find('.rb_import_widgets').data("checked", 1).addClass('checked');
            }
            if (!checkbox.data("checked") && 'rb_import_all' != name) {
                wrap.find('.rb_import_all').data("checked", 0).removeClass('checked');
            }
            if (checkbox.data("checked") && 'rb_import_pages' == name) {
                wrap.find('.rb_import_content').data("checked", 0).removeClass('checked');
            }

            self.importerBtnStatus(outer);
        });
    };

    /** importer button */
    Module.importerBtnStatus = function (wrapper) {
        var importAll = wrapper.find('.rb_import_all').data('checked');
        var importContent = wrapper.find('.rb_import_content').data('checked');
        var importPages = wrapper.find('.rb_import_pages').data('checked');
        var importTops = wrapper.find('.rb_import_tops').data('checked');
        var importWidgets = wrapper.find('.rb_import_widgets').data('checked');

        if (importAll || importContent || importPages || importTops || importWidgets) {
            wrapper.find('.rb-disabled').removeClass('rb-disabled');
            return true;
        }
        wrapper.find('.rb-importer-btn').addClass('rb-disabled');
        return false;
    };

    /** install package */
    Module.installPackaged = function () {

        $('.rb-install-package').unbind('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var target = $(this);
            target.addClass('loading');
            target.html('<span class="spinner rb-show-spinner"></span><span class="loading-info">Please wait...</span>');

            var installData = target.data();
            jQuery.post(RBImporter.ajaxurl, installData, function (response) {
                window.onbeforeunload = null;
                location.reload(true);
            });
        });
    };

    /** install demo */
    Module.installDemo = function () {

        var self = this;
        $('.rb-do-import, .rb-do-reimport').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (self.$processImport) {
                return false;
            }

            var target = $(this);
            var parent = target.parents('.rb-demo-item');
            var message = 'Import Demo Content?';

            if (parent.hasClass('is-imported')) {
                message = 'Re-Import Content?';
            }
            var confirm = window.confirm(message);
            if (confirm == false) {
                return;
            }

            self.$processImport = true;
            parent.addClass('is-importing');

            var importData = parent.data();
            importData.import_all = parent.find('.rb_import_all').data('checked');
            importData.import_content = parent.find('.rb_import_content').data('checked');
            importData.import_pages = parent.find('.rb_import_pages').data('checked');
            importData.import_opts = parent.find('.rb_import_tops').data('checked');
            importData.import_widgets = parent.find('.rb_import_widgets').data('checked');

            jQuery.post(RBImporter.ajaxurl, importData, function (response) {
                self.$processImport = false;
                if (response.length > 0 && (response.match(/Have fun!/gi) || response.match(/Skip content/gi))) {
                    self.$imported = true;
                } else {
                    self.$error = true;
                    alert('There was an error importing demo content: \n\n' + response.replace(/(<([^>]+)>)/gi, ""));
                }
            });
            self.checkImportProgress(parent);
            return false;
        });
    };

    /** check import progress */
    Module.checkImportProgress = function (parent) {
        var self = this;
        var checkImport = setInterval(function () {
            jQuery.ajax({
                type: 'POST',
                data: {
                    action: 'rb_check_progress'
                },
                url: RBImporter.ajaxurl,
                success: function (response) {
                    if (self.$error) {
                        parent.find('.process-count').text('Error');
                        clearInterval(checkImport);
                    } else {
                        if (self.$imported) {
                            clearInterval(checkImport);
                            parent.find('.demo-status').text('Already Imported');
                            parent.find('.process-count').text('Completed');
                            parent.find('.process-percent').addClass('is-completed');
                            parent.addClass('just-complete');
                            return false;
                        } else {
                            var obj = jQuery.parseJSON(JSON.stringify(response));
                            if (typeof obj == 'object') {
                                var percentage = Math.floor((obj.imported_count / obj.total_post ) * 100);
                                percentage = (percentage > 0) ? percentage - 1 : percentage;
                                parent.find('.process-percent').css('width', percentage + '%');
                                parent.find('.process-count').text(percentage + '%');
                            }
                        }
                    }
                },
                error: function (response) {
                    clearInterval(checkImport);
                }
            });
        }, 2000);
    };

    return Module;

}(RUBY_IMPORTER || {}, jQuery));

jQuery(document).ready(function () {
    RUBY_IMPORTER.init();
});
