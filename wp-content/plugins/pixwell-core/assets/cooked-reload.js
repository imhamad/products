/** FIX COOKED LOAD SCRIPT */
var PIXWELL_RELOAD_COOKED_PLUGIN = (function (Module, $) {
    "use strict";

    /** init */
    Module.init = function () {

        /****   1. Variables   ****/

        //$_Cooked_Ingredient_Boxes = $('.cooked-ingredient-checkbox');
        //$_Cooked_Fotorama = $('.cooked-recipe-gallery');
        //$_Cooked_Ajax_List = $('.cooked-recipe-loader');
        //$_Cooked_Recipe_Search = $('.cooked-recipe-search');
        //$_Cooked_Timers = $('.cooked-timer > a');
        //$_Cooked_FSM_Button = $('.cooked-fsm-button');

        //fix use strict
        var $_Cooked_Ingredient_Boxes = $('.cooked-ingredient-checkbox');
        var $_Cooked_Fotorama = $('.cooked-recipe-gallery');
        var $_Cooked_Ajax_List = $('.cooked-recipe-loader');
        var $_Cooked_Recipe_Search = $('.cooked-recipe-search');
        var $_Cooked_Timers = $('.cooked-timer > a');
        var $_Cooked_FSM_Button = $('.cooked-fsm-button');


        /****   2a. Cooked Gallery   ****/
        if ($_Cooked_Fotorama.length) {
            $_Cooked_Fotorama.on('fotorama:ready', function (e, fotorama) {
                setTimeout(function () {
                    $_Cooked_Fotorama.addClass('cooked-gallery-loaded');
                }, 100);
            });
        }

        // remove ready
        // $(document).ready(function() {

        /****   2b. Cooked Gallery   ****/

        if ($_Cooked_Fotorama.length) {
            $_Cooked_Fotorama.fotorama();
        }

        /****   3. Ingredients   ****/

        if ($_Cooked_Ingredient_Boxes.length) {

            init_cooked_ingredients($_Cooked_Ingredient_Boxes);

            //function init_cooked_ingredients(Cooked_Ingredient_Boxes) {
            //    Cooked_Ingredient_Boxes.on('click', function (e) {
            //        var thisCheckbox = $(this);
            //        if (thisCheckbox.hasClass('cooked-checked')) {
            //            thisCheckbox.parent().removeClass('cooked-checked');
            //            thisCheckbox.removeClass('cooked-checked');
            //        } else {
            //            thisCheckbox.parent().addClass('cooked-checked');
            //            thisCheckbox.addClass('cooked-checked');
            //        }
            //    });
            //}

        }

        //fix inside function
        function init_cooked_ingredients(Cooked_Ingredient_Boxes) {

            // fix duplicate click
            // Cooked_Ingredient_Boxes.on('click', function (e) {
            Cooked_Ingredient_Boxes.off('click').on('click', function (e) {
                var thisCheckbox = $(this);
                if (thisCheckbox.hasClass('cooked-checked')) {
                    thisCheckbox.parent().removeClass('cooked-checked');
                    thisCheckbox.removeClass('cooked-checked');
                } else {
                    thisCheckbox.parent().addClass('cooked-checked');
                    thisCheckbox.addClass('cooked-checked');
                }
            });
        }

        /****   4. Servings Switcher   ****/

        if ($('.cooked-servings').length) {
            var servingsSelectField = $('.cooked-servings').find('select');
            servingsSelectField.on('change', function (e) {
                e.preventDefault();
                var thisVal = servingsSelectField.find('option:selected').val();
                window.location = thisVal;
            });
        }

        /****   5. Browse Search Button   ****/

        if ($_Cooked_Recipe_Search.length) {

            $('body').on('click', function (e) {
                var thisButton = false;

                // Did someone click the Browse Button in one way or another?
                if ($('.cooked-browse-select').has(e.target).length > 0) {
                    thisButton = $(e.target).parents('.cooked-browse-select');
                } else if ($(e.target).hasClass('cooked-browse-select')) {
                    thisButton = $(e.target);
                }

                // Yep, they clicked the button!
                if (thisButton) {
                    if (thisButton.hasClass('cooked-active') && $(e.target).hasClass('cooked-browse-select') || thisButton.hasClass('cooked-active') && $(e.target).hasClass('cooked-field-title')) {
                        thisButton.removeClass('cooked-active');
                    } else {
                        thisButton.addClass('cooked-active');
                    }

                    // Nope, they clicked something else.
                } else {
                    $('.cooked-browse-select').removeClass('cooked-active');
                }

            });

            var browseSearchButton = $('.cooked-browse-search-button');
            browseSearchButton.on('click', function (e) {
                e.preventDefault();
                var thisButton = $(this);
                thisButton.parents('form').trigger('submit');
            });

        }

        /****   6. Timers   ****/

        function init_cooked_timers(Cooked_Timers) {

            // fix off click
            // Cooked_Timers.on('click', function (e) {
            Cooked_Timers.off('click').on('click', function (e) {

                e.preventDefault();
                var thisTimer = $(this),
                    timerID = 'cookedTimer-' + thisTimer.data('timer-id'),
                    totalTimers = $('#cooked-timers-wrap').find('.cooked-timer-block').length,
                    visibleClass, newHeight;

                // This timer is already here, let's just flash it.
                if ($('div#' + timerID).length) {

                    $('div#' + timerID).css({'background': '#eeeeee'});
                    setTimeout(function () {
                        $('div#' + timerID).css({'background': ''});
                    }, 200);
                    return;

                } else {

                    // Only 4 timers allowed at a time.
                    if (totalTimers == 4) {
                        $('#cooked-timers-wrap').css({'transform': 'translate3d(0,0.5em,0)'});
                        setTimeout(function () {
                            $('#cooked-timers-wrap').css({'transform': ''});
                        }, 200);
                        return;
                    }

                    // Okay we're good to go, let's add this timer!
                    totalTimers = totalTimers + 1;
                    newHeight = totalTimers * 7.5;
                    if (thisTimer.parents('.cooked-single-direction').length) {
                        var thisStep = thisTimer.parents('.cooked-single-direction').data('step');
                    } else {
                        var thisStep = cooked_js_vars.i18n_timer;
                    }
                    var Timer = {
                        id: timerID,
                        seconds: thisTimer.data('seconds'),
                        step: thisStep,
                        desc: thisTimer.data('desc')
                    };

                    // Timers wrap already there?
                    if ($('#cooked-timers-wrap').length) {

                        if (totalTimers == 1) {
                            visibleClass = ' cooked-visible';
                        } else {
                            visibleClass = '';
                        }

                        if (totalTimers > multiplesTrigger) {
                            $('#cooked-timers-wrap').addClass('cooked-multiples');
                        } else {
                            $('#cooked-timers-wrap').removeClass('cooked-multiples');
                        }

                        $('#cooked-timers-wrap').addClass('cooked-visible');
                        $('#cooked-timers-wrap').append('<div id="' + Timer.id + '" class="cooked-timer-block' + visibleClass + '"><span class="cooked-timer-step">' + Timer.step + '</span><span class="cooked-timer-desc">' + Timer.desc + '</span><div class="cooked-timer-obj" data-seconds-left="' + Timer.seconds + '"></div><i class="cooked-icon cooked-icon-times"></i><div class="cooked-progress"><span></span></div></div>');
                        var thisTimerObj = $('#' + Timer.id).find('.cooked-timer-obj');
                        cookedTimer(thisTimerObj, false);
                        setTimeout(function () {
                            $('#cooked-timers-wrap').css({'height': newHeight + 'em'});
                            $('.cooked-timer-block').addClass('cooked-visible');
                        }, 50);

                    } else {

                        $('body').append('<div id="cooked-timers-wrap"><div id="' + Timer.id + '" class="cooked-timer-block cooked-visible"><span class="cooked-timer-step">' + Timer.step + '</span><span class="cooked-timer-desc">' + Timer.desc + '</span><div class="cooked-timer-obj" data-seconds-left="' + Timer.seconds + '"></div><i class="cooked-icon cooked-icon-times"></i><div class="cooked-progress"><span></span></div></div></div>');
                        var thisTimerObj = $('#' + Timer.id).find('.cooked-timer-obj');
                        cookedTimer(thisTimerObj, false);
                        setTimeout(function () {
                            $('#cooked-timers-wrap').addClass('cooked-visible');
                        }, 50);

                    }


                }

            });

        }

        function cookedTimer(timerObj, startPaused) {

            var timer_sound = cooked_js_vars.timer_sound;
            var audio = new Audio(timer_sound);

            var thisTimerID = timerObj.parents('.cooked-timer-block').attr('id'),
                secondsLeft = timerObj.data('seconds-left'),
                parentBlock = timerObj.parents('.cooked-timer-block');

            timerObj.startTimer({
                classNames: {
                    hours: 'cooked-timer-hours',
                    minutes: 'cooked-timer-minutes',
                    seconds: 'cooked-timer-seconds',
                    clearDiv: 'cooked-timer-clearDiv',
                    timeout: 'cooked-timer-timeout'
                },
                onComplete: function () {
                    audio.play();
                    timerObj.addClass('cooked-timer-complete');
                }
            });

            timerObj.prepend('<i class="cooked-icon cooked-icon-reverse"></i><i class="cooked-icon cooked-icon-pause"></i><i class="cooked-icon cooked-icon-play"></i>');

            if (startPaused) {
                timerObj.trigger('pause');
                parentBlock.addClass('cooked-paused');
                parentBlock.find('i.cooked-icon-pause').hide();
                parentBlock.find('i.cooked-icon-play').css({'display': 'inline-block'});
                parentBlock.addClass('cooked-paused');
                $(this).parent().find('i.cooked-icon-play').css({'display': 'inline-block'});
            }

            cookedTimer_progress_bar(parentBlock, 10, 10);

            timerObj.on('update', function (e, timeLeft) {
                cookedTimer_progress_bar(parentBlock, timeLeft, secondsLeft);
            });

            timerObj.on('complete', function (e, timeLeft) {
                audio.play();
                parentBlock.find('i.cooked-icon-pause').hide();
                parentBlock.find('i.cooked-icon-play').hide();
                parentBlock.find('.cooked-timer-seconds').html('00');
            });

            // fix click
            // timerObj.on('click', 'i.cooked-icon-pause', function (e) {
            timerObj.off('click').on('click', 'i.cooked-icon-pause', function (e) {
                e.preventDefault();
                $(this).hide();
                parentBlock.addClass('cooked-paused');
                $(this).parent().find('i.cooked-icon-play').css({'display': 'inline-block'});
                timerObj.trigger('pause');
            });

            //timerObj.on('click', 'i.cooked-icon-play', function (e) {
            timerObj.off('click').on('click', 'i.cooked-icon-play', function (e) {
                e.preventDefault();
                $(this).hide();
                parentBlock.removeClass('cooked-paused cooked-complete');
                $(this).parent().find('i.cooked-icon-pause').css({'display': 'inline-block'});
                timerObj.trigger('resume');
            });

            //timerObj.on('click', 'i.cooked-icon-reverse', function (e) {
            timerObj.off('click').on('click', 'i.cooked-icon-reverse', function (e) {
                e.preventDefault();
                parentBlock.removeClass('cooked-paused cooked-complete');
                $(this).parent().find('i.cooked-icon-play').css({'display': 'inline-block'});
                $(this).parent().find('i.cooked-icon-pause').hide();
                timerObj.remove();
                $('#' + thisTimerID).append('<div class="cooked-timer-obj" data-seconds-left="' + secondsLeft + '"></div>');
                var newTimer = $('#' + thisTimerID).find('.cooked-timer-obj');
                cookedTimer(newTimer, true);
            });


            //parentBlock.on('click', 'i.cooked-icon-times', function (e) {
            parentBlock.off('click').on('click', 'i.cooked-icon-times', function (e) {
                e.preventDefault();

                if ($('#cooked-timers-wrap').find('.cooked-timer-block').length == 1) {
                    $('#cooked-timers-wrap').removeClass('cooked-visible');
                } else {
                    var totalTimers = $('#cooked-timers-wrap').find('.cooked-timer-block').length - 1;
                    var newHeight = totalTimers * 7.5;
                    $('#cooked-timers-wrap').css({'height': newHeight + 'em'});
                    parentBlock.removeClass('cooked-visible');
                    if (totalTimers == multiplesTrigger) {
                        $('#cooked-timers-wrap').removeClass('cooked-multiples');
                    }
                }

                setTimeout(function () {
                    parentBlock.remove();
                }, 200);

            });

        }

        function cookedTimer_progress_bar(container, remaining_time, total_time) {
            var progressPercent = 100 - ( ( remaining_time / total_time ) * 100 );
            container.find('.cooked-progress > span').css({'width': progressPercent + '%'});
            if (progressPercent >= 100) {
                container.addClass('cooked-complete');
            }
        }

        if ($_Cooked_Timers.length) {

            // How many timers to show before it moves to the right side of the screen?
            var multiplesTrigger = 1;

            init_cooked_timers($_Cooked_Timers);

        }

        /****   7. Full-Screen Mode   ****/

        if ($_Cooked_FSM_Button.length) {


            //fix duplicate click
            //$_Cooked_FSM_Button.on('click', function (e) {
            $_Cooked_FSM_Button.off('click').on('click', function (e) {

                e.preventDefault();
                var recipe_id = $(this).data('recipe-id'),
                    FSM_Container = $('.cooked-fsm[data-recipe-id="' + recipe_id + '"]');

                $('body').addClass('cooked-noscroll cooked-fsm-active');
                var New_FSM_Container = FSM_Container.clone().appendTo('body');

                //fix duplicate ID
                if (FSM_Container.length > 1) {
                    FSM_Container = FSM_Container.eq(0)
                }

                setTimeout(function () {
                    New_FSM_Container.addClass('cooked-visible');
                }, 10);
                setTimeout(function () {
                    New_FSM_Container.addClass('cooked-active');
                }, 50);

                var Cooked_Timers = New_FSM_Container.find('.cooked-timer > a');
                var Cooked_Ingredient_Boxes = New_FSM_Container.find('.cooked-ingredient-checkbox');
                init_cooked_timers(Cooked_Timers);
                init_cooked_ingredients(Cooked_Ingredient_Boxes);

                New_FSM_Container.on('click', '.cooked-close-fsm', function (e) {
                    e.preventDefault();
                    New_FSM_Container.removeClass('cooked-active');
                    $('body').removeClass('cooked-noscroll cooked-fsm-active');

                    setTimeout(function () {
                        New_FSM_Container.remove();
                    }, 350);

                });

            });

            $('body').on('click', '.cooked-fsm-mobile-nav > a', function (e) {

                e.preventDefault();

                var thisButton = $(this),
                    nav_id = thisButton.data('nav-id'),
                    FSM_Container = thisButton.parents('.cooked-fsm');

                FSM_Container.find('.cooked-fsm-mobile-nav > a').removeClass('cooked-active');
                FSM_Container.find('.cooked-fsm-content').removeClass('cooked-active');

                thisButton.addClass('cooked-active');
                FSM_Container.find('.cooked-fsm-content.cooked-fsm-' + nav_id).addClass('cooked-active');

            });

        }

        // remove ready
        //});

    };

    return Module;

}(PIXWELL_RELOAD_COOKED_PLUGIN || {}, jQuery));

jQuery(window).on('load', function () {
    PIXWELL_RELOAD_COOKED_PLUGIN.init();
});