(function ($)
{
    $.coreui = (function()
    {
        return {
            editBoxFocus: function () {
                $("input.largeedit, textarea.large").bind('focus', function () {
                    $(this).addClass("active");
                    $(this).removeClass("empty");

                    if ($(this).val() == $(this).attr("defvalue"))
                        $(this).val("");

                    if ($(this).text() == $(this).attr("defvalue"))
                        $(this).text("");

                    if ($(this).val() == $(this).attr("defvalue") && $(this).attr("deftype") == "password")
                        $(this).attr("type", "text");
                    else if ($(this).val() != $(this).attr("defvalue") && $(this).attr("deftype") == "password")
                        $(this).attr("type", "password");
                });
            },

            editBoxBlur: function () {
                $("input.largeedit, textarea.large").bind('blur', function () {
                    $(this).removeClass("active");

                    if ($(this).val() == "")
                        $(this).val($(this).attr("defvalue"));

                    if ($(this).val() == $(this).attr("defvalue"))
                        $(this).addClass("empty");

                    if ($(this).val() == $(this).attr("defvalue") && $(this).attr("deftype") == "password")
                        $(this).attr("type", "text");
                    else if ($(this).val() != $(this).attr("defvalue") && $(this).attr("deftype") == "password")
                        $(this).attr("type", "password");
                });
            },

            lastQueryToggle: function () {
                $("div#query").bind("click", function () {
                    parent = $(this);
                    $(this).children("div").toggle("fast", function () {
                        if ($(this).is(":visible") == true)
                            $(parent).addClass("collapse");
                        else
                            $(parent).removeClass("collapse");
                    });
                });
            },

            typePlanSelect: function () {
                $(".typePlan .tab").bind("click", function () {
                    var href = $(this).attr("id");
                    $(".viewPlan div").each(function () {
                        if (href != $(this).attr("id")) {
                            $(this).css("display", "none");
                            $(".typePlan .tab#" + $(this).attr("id")).removeClass("active");
                        }
                        else {
                            $(this).css("display", "block");
                            $(".typePlan .tab#" + $(this).attr("id")).addClass("active");
                        }
                    });
                });
            },

            showDialog: function (text, type, timeout, options)
            {
                var dialog = $("#faDialog");
                var card = dialog.find('.dialog-card');
                var typeIcon, typeClass;
                switch (parseInt(type))
                {
                    case parseInt($.messageLevels.SUCCESS):
                        typeIcon = "check";
                        typeClass = "success";
                        break;

                    case parseInt($.messageLevels.INFO):
                        typeIcon = "info";
                        typeClass = "question";
                        break;

                    case parseInt($.messageLevels.WARNING):
                        typeIcon = "exclamation";
                        typeClass = "warning";
                        break;

                    case parseInt($.messageLevels.ERROR):
                    case parseInt($.messageLevels.DBERROR):
                        typeIcon = "times";
                        typeClass = "error";
                        break;
                }

                $(".dialog-card .dialog-title h5").html($.messageLevelTitle[parseInt(type)]);
                $(".dialog-info p").html(text);
                $("#typeIcon").attr("class", "dialog-"+ typeClass +"-sign");
                $("#typeIcon i").attr("class", "fa fa-"+ typeIcon);

                dialog.fadeIn();

                card.css(
                    {'margin-top' : -card.outerHeight()/2});

                $('button, .dialog-title span').on('click', function ()
                {
                    $.coreui.hideAllDialogs();
                });

                $('.dialog-overlay').on('click', function (e)
                {
                    if (e.target == this)
                        $.coreui.hideAllDialogs();
                });

                $(document).keyup(function(e)
                {
                    if (e.keyCode == 27 || e.keyCode == 13) {
                        if (timeout > 0) {
                            setTimeout(function () {
                                $.coreui.hideAllDialogs();
                            }, timeout);
                        }
                    }
                });

                if (timeout > 0) {
                    setTimeout(function () {
                        $.coreui.hideAllDialogs();
                    }, timeout);
                }
            },

            hideAllDialogs: function()
            {
                $('.dialog-overlay').fadeOut();
            }
        };
        
    })();
    
    $.fn.coreui = function()
    {
        return fn.call(this);
    }
    
})(jQuery);