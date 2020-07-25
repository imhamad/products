/** RUBY REACTION */
var RB_REACTION = (function (Module, $) {
    "use strict";

    Module.init = function () {
        this.clickReaction();
        this.loadReactions();
    };

    Module.loadReactions = function () {
        var self = this;

        self.reaction = $('.rb-reaction');
        if (self.reaction.length < 1) {
            return;
        }
        self.reactionProcessing = false;
        self.reaction.each(function () {
            var currentReaction = $(this);
            var uid = currentReaction.data('reaction_uid');
            if (!currentReaction.hasClass('data-loaded')) {
                self.loadCurrentReaction(currentReaction, uid);
            }
        });
    };

    Module.loadCurrentReaction = function (currentReaction, uid) {
        var self = this;
        self.reactionProcessing = true;
        $.ajax({
            type: 'POST',
            url: rbReactionParams.ajaxurl,
            data: {
                action: 'rb_load_reaction',
                uid: uid
            },
            success: function (data) {
                data = $.parseJSON(JSON.stringify(data));
                $.each(data, function (index, val) {
                    currentReaction.find('[data-reaction=' + val + ']').addClass('active');
                });

                currentReaction.addClass('data-loaded');
                self.reactionProcessing = false;
            }
        });
    };

    Module.clickReaction = function () {

        var self = this;
        $('.reaction').off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var target = $(this);
            var reaction = target.data('reaction');
            var uid = target.data('reaction_uid');
            var push = 1;

            if (self.reactionProcessing) {
                return;
            }

            target.addClass('loading');
            self.reactionProcessing = true;
            var reactionCount = target.find('.reaction-count');
            var total = parseInt(reactionCount.html());
            if (target.hasClass('active')) {
                total--;
                push = '-1';
            } else {
                total++;
            }
            $.ajax({
                type: 'POST',
                url: rbReactionParams.ajaxurl,
                data: {
                    action: 'rb_add_reaction',
                    uid: uid,
                    reaction: reaction,
                    push: push
                },

                success: function (data) {
                    if (true == data) {
                        reactionCount.hide().html(total).fadeIn(300);
                        target.toggleClass('active');
                        target.removeClass('loading');
                        self.reactionProcessing = false;
                    }
                }
            });

            return false;
        });
    };

    return Module;

}(RB_REACTION || {}, jQuery));

/** init RUBY REACTION */
jQuery(document).ready(function () {
    jQuery(window).on('load', function () {
        RB_REACTION.init();
    });
});