/** RUBY COMPOSER */
var RUBY_COMPOSER = (function (Composer, $) {
    "use strict";

    /*********** CORE *************/
    Composer = {

        init: function () {
            var self = this;
            self.initComposerData();

            self.$classicEditor = $('#postdivrich');
            self.$classicTemplateBox = $('#page_template');
            self.$editor = $('#editor');
            self.$eleBtn = $('#elementor-switch-mode-button');

            var switchMode = wp.template('rbc-switch-mode');
            if (self.$classicEditor.length > 0) {
                self.$isGutenberg = false;
                self.addComposerPanel();
                if ('rbc-frontend.php' != self.$classicTemplateBox.val()) {
                    self.$classicEditor.before(switchMode());
                } else {
                    self.$classicEditor.addClass('is-hidden');
                    self.$eleBtn.addClass('is-hidden');
                    $(self.$composerID).removeClass('is-hidden');
                }
                self.classicChangeMode();
            } else {
                var rbcAddTimeCounter = setInterval(function () {
                    if (self.$editor.length > 0 && self.$globalMetaPanel.length > 0) {
                        clearInterval(rbcAddTimeCounter);
                        self.$isGutenberg = true;
                        self.addComposerPanel();
                        self.$currentTemplateData = wp.data.select('core/editor').getEditedPostAttribute('template');
                        if ($.isEmptyObject(self.$rbcSaveData) && 'rbc-frontend.php' != self.$currentTemplateData) {
                            self.$editor.find('.edit-post-header-toolbar').append(switchMode());
                        } else {
                            self.gutenbergShowComposer();
                        }
                        self.gutenbergChangeMode();
                    }
                }, 10);
            }
        },

        /** initComposerData */
        initComposerData: function () {
            this.$globalConfigs = $.parseJSON(rbcConfigs);
            this.$globalMetaPanel = $('#rbc-global-meta');
            this.$rbcSaveData = this.$globalConfigs.rbcContent;
            this.$setupSections = this.$globalConfigs.setupSections;
            this.$setupBlocks = this.$globalConfigs.setupBlocks;
            this.$setupTabs = this.$globalConfigs.setupTabs;
            this.$templateList = this.$globalConfigs.templateList;
            this.$unload = false;
            this.$pageBody = $('html,body');
            this.$isDocument = $(document);
            this.$composerID = '#rbc-editor';
            this.$rbIndex = 0;
        },

        /** classic mode */
        classicChangeMode: function () {

            var self = this;
            this.$isDocument.on('click', '#rbc-switch-mode-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.$classicTemplateBox.val('rbc-frontend.php');
                self.$classicTemplateBox.trigger('change');
            });

            self.$classicTemplateBox.on('change', function () {
                if ('rbc-frontend.php' == $(this).val()) {
                    $(self.$composerID).removeClass('is-hidden');
                    self.$classicEditor.addClass('is-hidden');
                } else {
                    $(self.$composerID).addClass('is-hidden');
                    self.$classicEditor.removeClass('is-hidden');
                }
                $('#rbc-switch-mode-btn').remove();
            });
        },

        /** gutenberg mode */
        gutenbergChangeMode: function () {
            var self = this;
            self.$isDocument.on('click', '#rbc-switch-mode-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.gutenbergShowComposer();
                self.gutenbergAddPageAttrs();
                $(this).remove();
            });

            self.$isDocument.on('change', '.editor-page-attributes__template select', function () {
                if ('rbc-frontend.php' == pageTemplateBox.val()) {
                    self.gutenbergShowComposer();
                    self.gutenbergAddPageAttrs();
                } else {
                    self.gutenbergHideComposer();
                }
                $('#rbc-switch-mode-btn').remove();
            });
        },

        /** gutenberg show composer */
        gutenbergShowComposer: function () {
            var self = this;
            $(self.$composerID).removeClass('is-hidden');
            self.$editor.find('.editor-block-list__layout, .editor-post-text-editor, .block-editor-block-list__layout').addClass('is-hidden');
            self.$editor.addClass('rbc-enabled');
        },

        /** add page attrs */
        gutenbergAddPageAttrs: function () {
            var pageTitle = wp.data.select('core/editor').getEditedPostAttribute('title');
            if (!pageTitle) {
                wp.data.dispatch('core/editor').editPost({
                    title: 'Ruby Composer Daft #' + $('#post_ID').val()
                });
            }
            wp.data.dispatch('core/editor').editPost({template: 'rbc-frontend.php'});
        },

        /** gutenberg hide composer */
        gutenbergHideComposer: function () {
            var self = this;
            self.$editor.find('.editor-block-list__layout, .editor-post-text-editor, .block-editor-block-list__layout').removeClass('is-hidden');
            self.$editor.removeClass('rbc-enabled');

            $(self.$composerID).addClass('is-hidden');
        },

        /** trigger update button */
        gutenbergUpdateBtn: function () {
            var self = this;
            self.$isDocument.on('RBC:addBlock RBC:addSection RBC:deleteSection RBC:deleteBlock RBC:changeInput', function () {
                if (self.$isGutenberg) {
                    wp.data.dispatch('core/editor').editPost({rbc_edit_update: 1});
                }
            });
        },

        /** add panel */
        addComposerPanel: function () {
            var self = this;
            var composerPanel = wp.template('rbc-panel-composer');
            self.$globalMetaPanel.append(composerPanel());
            self.$composerWrapper = $(self.$composerID);

            self.setupPanelSection();
            self.loadSaveSections();
            self.defaultTemplateAction();
            self.defaultSectionActions();
            self.defaultBlockActions();
            self.gutenbergUpdateBtn();
        },

        /** setup composer panel & section list */
        setupPanelSection: function () {
            var self = this;
            var sectionList = $('#rbc-section-list');
            var sectionItemTemplate = wp.template('rbc-section-item');

            if ('undefined' != typeof self.$setupSections) {
                $.each(self.$setupSections, function (key, data) {
                    sectionList.append(sectionItemTemplate(data));
                })
            }

            self.jsLoaded();
        },

        /** load sections */
        loadSaveSections: function (composerSaveData) {

            var self = this;
            if ('undefined' == typeof composerSaveData) {
                composerSaveData = self.$rbcSaveData;
            }

            if ('object' == typeof composerSaveData && composerSaveData) {
                $.each(composerSaveData, function (sectionID, sectionSaveData) {
                    self.loadEachSection(sectionSaveData['type'], sectionSaveData);
                });
            }

            self.$composerWrapper.find('.rbc-loader').fadeTo(300, 0, function () {
                $('.rbc-block-expand').trigger('click');
                $(this).remove();
            })
        },


        /** load section */
        loadEachSection: function (type, sectionSaveData) {
            var data = {'type': type};
            var self = this;
            if (!self.$setupSections[type]) {
                return;
            }

            var stackSections = self.$composerWrapper.find('#rbc-stack-sections');
            var sectionTemp = wp.template('rbc-section-' + type);

            if ('undefined' != typeof self.$setupSections[type].title) {
                data.title = self.$setupSections[type].title;
                data.rbIndex = ++ self.$rbIndex;
            }

            if ('undefined' == typeof sectionSaveData || 'undefined' == typeof sectionSaveData.uuid) {
                data.uuid = self.uuid();
            } else {
                data.uuid = sectionSaveData.uuid;
            }

            switch (type) {
                case 'content' :
                    if ('undefined' != typeof sectionSaveData && 'undefined' != typeof sectionSaveData.sidebar_name) {
                        data.sidebar_name = sectionSaveData.sidebar_name;
                    }
                    if ('undefined' != typeof sectionSaveData && 'undefined' != typeof sectionSaveData.sidebar_pos) {
                        data.sidebar_pos = sectionSaveData.sidebar_pos;
                    }
                    if ('undefined' != typeof sectionSaveData && 'undefined' != typeof sectionSaveData.sidebar_sticky) {
                        data.sidebar_sticky = sectionSaveData.sidebar_sticky;
                    }
                    break;
                default :
            }

            var newSection = $(sectionTemp(data));

            if ('undefined' != typeof self.$setupSections[type].inputs) {

                var tempOption = wp.template('rbc-section-option');
                $.each(self.$setupSections[type].inputs, function (index, sectionConfig) {
                    if ('undefined' != typeof sectionConfig.type && 'undefined' != typeof sectionConfig.name) {

                        sectionConfig.value = '';
                        sectionConfig.uuid = data.uuid;

                        if ('undefined' != typeof sectionConfig.default) {
                            sectionConfig.value = sectionConfig.default;
                        }

                        if ('undefined' != typeof sectionSaveData && 'undefined' != typeof sectionSaveData[sectionConfig.name]) {
                            sectionConfig.value = sectionSaveData[sectionConfig.name];
                        }

                        var option = $(tempOption(sectionConfig));
                        var optionInput = option.find('.rbc-option-input');
                        var inputTemp = wp.template('rbc-section-' + sectionConfig.type);
                        optionInput.append(inputTemp(sectionConfig));

                        newSection.find('.rbc-section-settings').append(option);
                    }
                })
            }

            if ('undefined' == typeof sectionSaveData) {
                newSection.addClass('is-opacity');
            }
            stackSections.append(newSection);
            stackSections.find('.rbc-section-empty').remove();

            self.loadBlocks(newSection, type, sectionSaveData);
            self.sortBlocks(newSection);

            return newSection;
        },

        /** load blocks */
        loadBlocks: function (newSection, type, sectionSaveData) {
            var self = this;
            self.setupPanelBlock(newSection, type);
            self.loadSaveBlocks(newSection, sectionSaveData);
        },

        /** section block panel */
        setupPanelBlock: function (section, type) {
            var self = this;
            var blockList = section.find('.rbc-block-list');
            var blockListTemplate = wp.template('rbc-block-item');

            if ('undefined' != typeof self.$setupBlocks && 'undefined' != typeof self.$setupBlocks[type]) {
                $.each(self.$setupBlocks[type], function (index, block) {
                    blockList.append(blockListTemplate(block));
                })
            }
        },

        /** load save blocks */
        loadSaveBlocks: function (newSection, sectionSaveData) {
            var self = this;
            if ('undefined' != typeof sectionSaveData && 'undefined' != typeof sectionSaveData.blocks) {
                $.each(sectionSaveData.blocks, function (blockID, blockSaveData) {
                    if ('undefined' != typeof blockSaveData.name) {
                        self.loadEachBlock(blockSaveData.name, newSection, blockSaveData);
                    }
                })
            }
        },

        /** load block */
        loadEachBlock: function (blockName, parentSection, blockSaveData) {
            var data = {};
            var self = this;
            var sectionType = parentSection.find('.rbc-section-type').val();
            var stackBlocks = parentSection.find('.rbc-stack-blocks');
            var tempBlock = wp.template('rbc-block');

            data.name = blockName;
            data.sectionID = parentSection.find('.rbc-section-order').val();

            if ('undefined' != typeof self.$setupBlocks[sectionType] && 'undefined' != typeof self.$setupBlocks[sectionType][blockName]) {
                var blockConfig = self.$setupBlocks[sectionType][blockName];

                if ('undefined' != typeof blockConfig.title) {
                    data.title = blockConfig.title;
                }

                if ('undefined' != typeof blockSaveData && '' != typeof blockSaveData.title) {
                    data.header = blockSaveData.title;
                }

                if ('undefined' != typeof blockConfig.description) {
                    data.description = blockConfig.description;
                }

                if ('undefined' != typeof blockConfig.tips) {
                    data.tips = blockConfig.tips;
                }

                if ('undefined' == typeof blockSaveData || 'undefined' == typeof blockSaveData.uuid) {
                    data.uuid = self.uuid();
                } else {
                    data.uuid = blockSaveData.uuid;
                }

                var newBlock = $(tempBlock(data));
                blockConfig.uuid = data.uuid;
                newBlock = self.loadBlockOptions(newBlock, blockConfig, blockSaveData);
                if ('undefined' == typeof  blockSaveData) {
                    newBlock.addClass('is-opacity');
                }
                stackBlocks.append(newBlock);
            }

            parentSection.find('.rbc-empty').remove();
            self.$isDocument.trigger('RBC:addBlock');

            return newBlock;
        },

        /** load block options */
        loadBlockOptions: function (block, blockConfig, blockSaveData) {

            var self = this;
            var tempOption = wp.template('rbc-block-options');
            var stackOptions = block.find('.rbc-stack-options');
            var tempTabNav = wp.template('rbc-tab-nav');
            var tempTabWrap = wp.template('rbc-tab');

            $.each(blockConfig.tabs, function (index, tab) {
                var data = {};
                data.tab = tab;
                if ('undefined' != typeof self.$setupTabs[tab]) {
                    data.title = self.$setupTabs[tab];
                } else {
                    data.title = tab;
                }
                block.find('.rbc-tabs-nav').append(tempTabNav(data));
                stackOptions.append(tempTabWrap(data));
            });

            if ('undefined' != typeof  blockConfig.inputs) {
                $.each(blockConfig.inputs, function (index, fieldConfig) {
                    if ('undefined' != typeof fieldConfig.type) {

                        fieldConfig.value = '';
                        fieldConfig.uuid = blockConfig.uuid;

                        if ('undefined' == typeof fieldConfig.name) {
                            fieldConfig.name = fieldConfig.type;
                        }

                        if ('undefined' != typeof  fieldConfig.default) {
                            fieldConfig.value = fieldConfig.default;
                        }

                        if ('undefined' != typeof blockSaveData && 'undefined' != typeof blockSaveData[fieldConfig.name]) {
                            fieldConfig.value = blockSaveData[fieldConfig.name];
                        }

                        var option = $(tempOption(fieldConfig));
                        var optionInput = option.find('.rbc-option-input');
                        var tempInput = wp.template('rbc-input-' + fieldConfig.type);
                        optionInput.append(tempInput(fieldConfig));

                        if ('undefined' != typeof fieldConfig.tab && stackOptions.find('.rbc-tab-' + fieldConfig.tab).length > 0) {
                            stackOptions.find('.rbc-tab-' + fieldConfig.tab).append(option)
                        } else {
                            stackOptions.append(option);
                        }
                    }
                });
            }

            self.initSlippingTab(block);
            block.find('.rbc-options-wrap').removeClass('is-hidden');

            return block;
        },

        /** initSlippingTab */
        initSlippingTab: function (block) {
            var currentTab = block.find('.rbc-tab-nav-target:first-child');
            currentTab.addClass('filter-active');
            block.find('.rbc-tab-wrap').hide();
            block.find('.rbc-tab-' + currentTab.data('tab-filter')).show();
        },


        /** section actions */
        defaultSectionActions: function () {
            this.sectionPanelToggle();
            this.addNewSection();
            this.expandToggleSection();
            this.deleteSection();
            this.sortSections();
            this.tabSectionPanelToggle();
            this.cloneSection();
        },

        /** panel toggle */
        sectionPanelToggle: function () {
            var self = this;
            self.$isDocument.on('click', '#rbc-section-list-header', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).next('#rbc-section-list').slideToggle(0);
            });
        },

        /** add new section */
        addNewSection: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-section-target', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.$unload = true;
                var type = $(this).data('type');
                var newSection = self.loadEachSection(type);
                self.scrollToSection(newSection.attr('id'));
                self.$isDocument.trigger('RBC:addSection');
            });
        },

        /** expand blocks */
        expandToggleSection: function () {
            this.$isDocument.on('click', '.rbc-section-expand, .section-label', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).parents('.rbc-section').find('.rbc-stack-blocks, .rbc-panel-block').slideToggle(0);
            });
        },

        /** clone section */
        cloneSection: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-section-clone', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var section = $(this).parents('.rbc-section');
                var $originalInputData = section.find('.rbc-field:not(".rbc-order")');

                var blocks = section.find('.rbc-block');
                var oldSectionID = section.data('uuid');
                var copySection = section.clone(true);
                var copySectionHTML = copySection.get(0).outerHTML;
                var newSectionID = self.uuid();
                copySectionHTML = self.replaceAll(copySectionHTML, oldSectionID, newSectionID);
                if (blocks.length > 0) {
                    blocks.each(function (index, block) {
                        var oldBlockID = $(block).data('uuid');
                        copySectionHTML = self.replaceAll(copySectionHTML, oldBlockID, self.uuid());
                    })
                }
                copySection = $(copySectionHTML).addClass('is-opacity');

                copySection.find('.rbc-field:not(".rbc-order")').each(function (index, input) {
                    $(input).val($originalInputData.eq(index).val());
                });

                section.after(copySection);
                self.scrollToSection('rbc-section-' + newSectionID);
            });
        },

        /** delete section */
        deleteSection: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-section-delete', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var confirmDelete = confirm(self.$globalConfigs.confirmDS);
                if (confirmDelete) {
                    self.$unload = true;
                    var section = $(this).parents('.rbc-section');
                    section.addClass('is-opacity');
                    setTimeout(function () {
                        section.remove()
                    }, 300);
                    self.$isDocument.trigger('RBC:deleteSection');
                }
            });
        },

        /** scroll to section */
        scrollToSection: function (sectionID) {
            var self = this;
            var section = self.$isDocument.find('#' + sectionID);
            if ('undefined' != typeof section) {
                section = $(section);
                if (self.$isGutenberg) {
                    $('.edit-post-layout__content, .edit-post-editor-regions__content, .block-editor-editor-skeleton__content').animate({
                        scrollTop: section.offset().top - 150
                    }, 300);
                } else {
                    this.$pageBody.animate({
                        scrollTop: section.offset().top - 100
                    }, 300);
                }
                setTimeout(function () {
                    section.removeClass('is-opacity');
                }, 300)
            }
        },

        /** block actions */
        defaultBlockActions: function () {
            this.addNewBlock();
            this.deleteBlock();
            this.blockOptionsToggle();
            this.blockPanelToggle();
            this.tabOptionsToggle();
            this.cloneBlock();
            this.changeComposerInput();
        },

        /** block toggle */
        blockPanelToggle: function () {
            this.$isDocument.on('click', '.rbc-panel-block-expand', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).parents('.rbc-panel-block').find('.rbc-panel-block-content').slideToggle(0);
            });
        },

        /** add block */
        addNewBlock: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-block-target', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.$unload = true;
                var name = $(this).data('name');
                var parentSection = $(this).parents('.rbc-section');
                var newBlock = self.loadEachBlock(name, parentSection);
                self.scrollToBlock(newBlock.attr('id'));
            });
        },


        /** clone block */
        cloneBlock: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-block-clone', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var block = $(this).parents('.rbc-block');
                var $originalInputData = block.find('.rbc-field:not(".rbc-order")');
                var oldBlockID = block.data('uuid');
                var copyBlock = block.clone(true);
                var copyBlockHMTL = copyBlock.get(0).outerHTML;
                var newBlockID = self.uuid();
                copyBlockHMTL = self.replaceAll(copyBlockHMTL, oldBlockID, newBlockID);
                copyBlock = $(copyBlockHMTL).addClass('is-opacity');

                copyBlock.find('.rbc-field:not(".rbc-order")').each(function (index, input) {
                    $(input).val($originalInputData.eq(index).val());
                });

                block.after(copyBlock);
                self.scrollToBlock('rbc-block-' + newBlockID);
            });
        },

        /** delete block */
        deleteBlock: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-block-delete', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.$unload = true;
                $(this).parents('.rbc-block').fadeTo(300, 0, function () {
                    $(this).remove()
                });
                self.$isDocument.trigger('RBC:deleteSection');
            });
        },

        /** block option toggle */
        blockOptionsToggle: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-block-expand, .block-label', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).parents('.rbc-block').find('.rbc-options-wrap').slideToggle(0);
            });
        },

        /** scroll to block */
        scrollToBlock: function (blockID) {
            var self = this;
            var block = self.$isDocument.find('#' + blockID);
            if ('undefined' != typeof block) {
                block = $(block);
                if (self.$isGutenberg) {
                    var parentSection = block.parents('.rbc-section');
                    $('.edit-post-layout__content, .edit-post-editor-regions__content, .block-editor-editor-skeleton__content').animate({
                        scrollTop: parentSection.position().top + block.position().top + $(window).height() - 150
                    }, 300);
                } else {
                    this.$pageBody.animate({
                        scrollTop: block.offset().top - 100
                    }, 300);
                }
                setTimeout(function () {
                    block.removeClass('is-opacity');
                }, 300);
            }
        },

        /** settings tab slipping */
        tabOptionsToggle: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-tab-nav-target', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var target = $(this);
                var tabNavWrapper = target.parent();
                var currentBlock = target.parents('.rbc-block');
                tabNavWrapper.find('a').removeClass('filter-active');
                target.addClass('filter-active');
                currentBlock.find('.rbc-tab-wrap').hide();
                currentBlock.find('.rbc-tab-' + target.data('tab-filter')).show();
            })
        },

        /** load slipping tabs */
        tabSectionPanelToggle: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-panel-nav-target', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var target = $(this);
                var currentPanel = target.parents('.rbc-panel-block');
                currentPanel.find('.rbc-panel-nav-target').removeClass('is-nav-active');
                target.addClass('is-nav-active');
                currentPanel.find('.rbc-panel-tab').hide();
                currentPanel.find('.rbc-panel-tab-' + target.data('target')).show();
                var panelBlockContent = currentPanel.find('.rbc-panel-block-content');
                if (panelBlockContent.is(':hidden')) {
                    panelBlockContent.slideToggle(300);
                }
            })
        },

        /** generate ID */
        uuid: function () {
            return 'uid_' + 'xxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 12 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(12);
            });
        },

        /** replace all */
        replaceAll: function (content, search, replace) {
            return content.replace(new RegExp(search, "g"), replace);
        },

        /** sort sections */
        sortSections: function () {
            var stackSections = $('#rbc-stack-sections');
            if ('undefined' != typeof stackSections) {
                stackSections.sortable({
                    handle: '.rbc-section-move, h3.section-label',
                    placeholder: 'rbc-highlight',
                    forcePlaceholderSize: true,
                    update: function () {
                        self.$unload = true;
                    }
                });
            }
        },

        /** sort blocks */
        sortBlocks: function (section) {
            section.find('.rbc-stack-blocks').sortable({
                handle: '.rbc-block-move, h3.block-label',
                placeholder: 'rbc-highlight',
                forcePlaceholderSize: true,
                update: function () {
                    self.$unload = true;
                }
            });
        },

        /** template actions */
        defaultTemplateAction: function () {
            this.templatePanelToggle();
            this.saveContentTemplate();
            this.getTemplateList();
            this.deleteTemplateList();
            this.loadSaveTemplate();
        },

        /** load templates */
        loadSaveTemplate: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-template-list-add', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var confirmAddTemplate = confirm(self.$globalConfigs.confirmAT);
                if (confirmAddTemplate) {
                    self.$unload = true;
                    var target = $(this);
                    var name = target.data('name');
                    if ('undefined' != name) {
                        $.ajax({
                            type: 'POST',
                            async: true,
                            dataType: 'json',
                            url: rbcParams.ajaxurl,
                            data: {
                                action: 'rbc_load_template',
                                name: name
                            },
                            success: function (data) {
                                data = $.parseJSON(JSON.stringify(data));
                                self.loadSaveSections(self.randTemplateIDs(data));
                            }
                        });
                    }
                }
            });
        },

        /** create template ID */
        randTemplateIDs: function (data) {

            var self = this;
            if ('undefined' != typeof data && data) {
                $.each(data, function (index, sectionData) {

                    var newSectionID = self.uuid();
                    if ('undefined' != typeof  sectionData.uuid) {
                        sectionData.uuid = newSectionID;
                    }

                    if ('undefined' != typeof sectionData && 'undefined' != typeof sectionData.blocks && sectionData.blocks) {
                        $.each(sectionData.blocks, function (index, blockData) {

                            var newBlockID = self.uuid();
                            if ('undefined' != typeof  blockData.uuid) {
                                blockData.uuid = newBlockID;
                            }
                            sectionData.blocks[newBlockID] = blockData;
                            delete(sectionData.blocks[index]);
                        });
                    }

                    data[newSectionID] = sectionData;
                    delete(data[index]);
                });
            }

            return data;
        },


        /** panel toggle */
        templatePanelToggle: function () {
            var self = this;
            self.$isDocument.on('click', '#rbc-main-template-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).next('.rbc-template-panel').slideToggle(300);
            });
        },

        /** save template */
        saveContentTemplate: function () {
            var self = this;
            self.$isDocument.on('click', '#rbc-template-save-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var target = $(this);
                target.next('#rbc-template-submit-panel').fadeTo(300, 1);
                target.hide();
                self.submitContentTemplate();
            });
        },

        /** submit content */
        submitContentTemplate: function () {

            var self = this;
            var input = $('#rbc-template-name');

            input.on('keyup', function (e) {
                e.preventDefault();
                e.stopPropagation();
                input.removeClass('rbc-input-error');
                input.next('.rbc-input-notice').hide();
            });

            self.$isDocument.on('click', '#rbc-template-submit-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var target = $(this);
                if (!input.val()) {
                    input.addClass('rbc-input-error');
                    input.next('.rbc-input-notice').show();
                    return false;
                }
                target.next('.saving-load').css('opacity', 1);
                var composerDataPOST = self.getCurrentComposerData('rbc_submit_template', self.$composerID);
                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: 'json',
                    url: rbcParams.ajaxurl,
                    data: composerDataPOST,
                    success: function (data) {
                        data = $.parseJSON(JSON.stringify(data));
                        self.getTemplateList(data);
                        target.next('.saving-load').css('opacity', 0);
                        $('#rbc-template-submit-panel').hide();
                        $('#rbc-template-name').val('');
                        $('#rbc-template-save-btn').show();
                    }
                });
            });
        },

        /** get templates */
        getTemplateList: function (data) {
            if ('undefined' == typeof data) {
                if ('undefined' != typeof this.$templateList) {
                    data = this.$templateList;
                } else {
                    return;
                }
            }

            var listTemplate = wp.template('rbc-template-list');
            var listWrapper = $('#rbc-template-loaded-panel');
            listWrapper.empty();
            if (data.length > 0) {
                listWrapper.next('.rbc-template-empty').hide();
                $.each(data, function (index, name) {
                    data = {name: name};
                    listWrapper.hide().append(listTemplate(data)).fadeTo(300, 1);
                });
            } else {
                listWrapper.next('.rbc-template-empty').show();
            }
        },

        /** delete template list */
        deleteTemplateList: function () {
            var self = this;
            self.$isDocument.on('click', '.rbc-template-list-delete', function (e) {

                e.preventDefault();
                e.stopPropagation();

                var confirmDeleteTemplate = confirm(self.$globalConfigs.confirmDT);
                if (confirmDeleteTemplate) {
                    var name = $(this).data('name');
                    $.ajax({
                        type: 'POST',
                        async: true,
                        dataType: 'json',
                        url: rbcParams.ajaxurl,
                        data: {
                            action: 'rbc_delete_template',
                            name: name
                        },
                        success: function (data) {
                            data = $.parseJSON(JSON.stringify(data));
                            self.getTemplateList(data);
                        }
                    });
                }
            });
        },


        /** get all current input data */
        getCurrentComposerData: function (action, wrapperID) {
            if ('undefined' == typeof (wrapperID)) {
                wrapperID = this.$composerID;
            }

            var composerInputs = $(wrapperID).find('.rbc-field');
            var composerDataPOST = 'action=' + action;
            composerInputs.each(function () {
                composerDataPOST += '&' + $(this).serialize();
            });

            return composerDataPOST;
        },

        /** unload checker */
        unloadChecker: function () {
            var self = this;

            $('#publishing-action, .editor-post-save-draft, .editor-post-publish-panel__toggle').on('click', function () {
                self.$unload = false;
            });


            $(window).on('beforeunload', function () {
                if (true === self.$unload) {
                    self.$unload = false;
                    return 'unload';
                }
            })
        },

        /** trigger update button */
        changeComposerInput: function () {
            var self = this;
            self.$isDocument.on('change', '.rbc-field', function () {
                self.$isDocument.trigger('RBC:changeInput');
                self.$unload = true;
            });
        },

        jsLoaded: function () {
            var jsLoadedTemp = wp.template('rbc-js-loaded');
            this.$composerWrapper.prepend(jsLoadedTemp);
        }

    };

    return Composer;

}(RUBY_COMPOSER || {}, jQuery));

jQuery(document).ready(function () {
    RUBY_COMPOSER.init();
});