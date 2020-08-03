allEvents = [
    "SYSTEM_ENTER",
    "SYSTEM_EXIT",
    "CREATE_ORDER",
    "EDIT_ORDER",
    "SHOW_ORDERS",
    "SAVE_ORDER",
    "LIST_ADD",
    "LIST_REMOVE",
    "LIST_EDIT",
    "PROFILE_EDIT",
    "GET_LIST",
    "SHOW_MODULE",
    "SHOW_STATISTIC",
    "SHOW_STATISTIC_SALE"
];

var debugInfo = true;

function WriteMessageDebug(text) {
    if (debugInfo)
        console.log(text);
}

$.textPreset =
    {
        DialogTitleError: "Ошибка",
        DialogTitleWarning: "Внимание",
        DialogTitleInfo: "Информация",
        DialogTitleConfirm: "Подтверждение",

        DialogButtonYes: "Да",
        DialogButtonNo: "Нет",

        DialogMessageDefault: "Вы подтверждаете выполнение операции?",
        DialogMessageLogout: "Вы действительно желаете выйти из системы?",
        DialogMessageRemove: "Вы действительно желаете удалить?"
    };

$.messageLevels =
    {
        SUCCESS: 1,
        INFO: 2,
        WARNING: 3,
        ERROR: 4,
        DBERROR: 5
    };

$.messageLevelTitle =
    {
        1: "Успешно",
        2: "Информация",
        3: "Предупреждение",
        4: "Ошибка"
    };

(function ($) {
    $.coremanage = (function () {
        var optionsQuery = {
            cache: false,
            type: 'POST',
            url: 'reqfiles/callhandler.php',
            dataType: 'html',
            async: false,
            error: function (objxhr, status, errMsg) {
                $.coremanage.MessageBoxError(errMsg, $.messageLevels.ERROR);
            }
        };

        var optionsDialog = {
            position: {my: "center", at: "center", of: window},
            modal: true,
            draggable: false,
            title: "",
            open: function () {
                $(".ui-widget-overlay").unbind("click");
                $(".ui-widget-overlay").bind("click", function () {
                    $("#dialogEdit").dialog("close");
                });
            }
        };

        var _postStr = function (typeOperation, data) {
            return "opindex=" + typeOperation + "&data=" + data;
        };

        $.ajaxSetup(optionsQuery);

        return {
            commafy: function (n) {
                var n = n || 0;
                n = (n + '').split('.').map(function (s, i) {
                    return i ? s : s.replace(/(\d)(?=(?:\d{3})+$)/g, '$1 ')
                }).join('.');
                return n;
            },
            GetEventId: function (nameId) {
                return allEvents.indexOf(nameId)
            },
            GetOptionsQuery: function () {
                return optionsQuery
            },
            QueryString: function (typeOperation, data) {
                return _postStr(typeOperation, data)
            },
            DataSend: function (data) {
                optionsQuery.data = data;
                $.ajax(optionsQuery);
            },
            MessageBox: function (text, level) {
                optionsDialog.title = $.messageLevelTitle[parseInt(level)];

                $("#dialogEdit").html(text).dialog(optionsDialog);
                $("button.ui-dialog-titlebar-close").hide();
                setTimeout(function () {
                    $("#dialogEdit").dialog("close");
                }, 3000);
            },

            listAdd: function (elemOption, elemSelect, textVal) {
                var queryString = 0;

                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) == $.messageLevels.SUCCESS) {
                        if (elemSelect != null) {
                            elemOption.before($("<option>", {"text": textVal, "value": receiveArray.message}));
                            elemSelect.selectpicker('refresh');
                        }
                        else {
                            $(".keywordContent").append("<span class='keywordValue'>" + textVal + "</span><sup name='" + elemOption.prev().attr("name") + "' id='" + receiveArray.message + "'>X</sup>");
                            $(elemOption).prev().val("");
                            $(elemOption).parent().slideToggle();
                            $(".keywordContent").css("opacity", "1");
                        }
                    }
                    else
                        $.coreui.showDialog(receiveArray.message, receiveArray.status);
                };

                if (elemSelect != null) {
                    queryString = this.QueryString($.coremanage.GetEventId("LIST_ADD"), textVal + "&nameVal=" + elemSelect.attr("name"));

                    if (elemSelect.attr("name") == "nameField")
                        queryString = this.QueryString($.coremanage.GetEventId("LIST_ADD"), textVal + "&nameVal=" + elemSelect.attr("name") + "&sexField=" + parseInt($('.selectpicker[name=sexField]').val()));
                }
                else
                    queryString = this.QueryString($.coremanage.GetEventId("LIST_ADD"), textVal + "&nameVal=" + elemOption.prev().attr("name"));

                this.DataSend(queryString);
            },

            listEdit: function (elem, typeList) {
                var queryString = 0;

                if (elem.hasClass("selectpicker")) {
                    queryString = this.QueryString($.coremanage.GetEventId("LIST_EDIT"), elem.val() + "&typeList=" + typeList + "&listId=" + elem.closest("tr").find("span.text").attr("list-id") + "&isSelect=1");
                    this.DataSend(queryString);
                } else if (elem.hasClass("fa-check")) {
                    elem.hide();
                    elem.closest("tr").find("i.fa-pen").show();
                    elem.closest("tr").find("input").hide();
                    elem.closest("tr").find("span.text").show();
                    if (elem.closest("tr").find("span.text").html() !== elem.closest("tr").find("input").val()) {
                        queryString = this.QueryString($.coremanage.GetEventId("LIST_EDIT"), elem.closest("tr").find("input").val() + "&typeList=" + typeList + "&listId=" + elem.closest("tr").find("span.text").attr("list-id"));
                        this.DataSend(queryString);
                    }
                } else if (elem.hasClass("fa-pen")) {
                    elem.hide();
                    elem.closest("tr").find("i.fa-check").show();
                    elem.closest("tr").find("span.text").hide();
                    elem.closest("tr").find("input").val(elem.closest("tr").find("span.text").html());
                    elem.closest("tr").find("input").show();
                }

                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) !== $.messageLevels.SUCCESS) {
                        $.coreui.bootstrapMessage(receiveArray.status, receiveArray.message);
                    } else {
                        if (!elem.hasClass("selectpicker")) {
                            elem.closest("tr").find("span.text").html(elem.closest("tr").find("input").val());
                        }
                    }
                };
            },

            listRemove: function (elem, elemSelect) {
                var optionsConfirm = {
                    'title': $.textPreset.DialogTitleConfirm,
                    'message': $.textPreset.DialogMessageRemove,
                    'buttons': {
                        Yes: {
                            'name': $.textPreset.DialogButtonYes,
                            'class': 'blue',
                            'action': function () {
                                optionsQuery.success = function (receive) {
                                    WriteMessageDebug(receive);
                                    var receiveArray = JSON.parse(receive);

                                    if (parseInt(receiveArray.status) == $.messageLevels.SUCCESS) {
                                        if (elem != null)
                                            $(elem).parent().parent().remove();
                                        else {
                                            $(elemSelect).prev().remove();
                                            $(elemSelect).remove();
                                        }
                                    }
                                    else
                                        $.coreui.showDialog(receiveArray.message, receiveArray.status);
                                };

                                if (elem != null)
                                    optionsQuery.data = _postStr($.coremanage.GetEventId("LIST_REMOVE"), $(elem).parent().find("span.text").attr("id") + "&nameVal=" + elemSelect.attr("name"));
                                else
                                    optionsQuery.data = _postStr($.coremanage.GetEventId("LIST_REMOVE"), $(elemSelect).attr("id") + "&nameVal=" + $(elemSelect).attr("name"));
                                $.ajax(optionsQuery);
                            }
                        },
                        No: {
                            'name': $.textPreset.DialogButtonNo,
                            'class': 'gray'
                        }
                    }
                };

                $.confirm(optionsConfirm);
            },

            setDayRange: function(type) {
                var valToDate = 0;
                var currentDate = new Date();
                var currentMonth = parseInt(currentDate.getMonth() + 1) < 10 ? "0" + (currentDate.getMonth() + 1) : currentDate.getMonth() + 1;
                var currentDay = parseInt(currentDate.getDate()) < 10 ? "0" + (currentDate.getDate()) : currentDate.getDate();
                var curDateStr = currentDay + "." + currentMonth + "." + currentDate.getFullYear();

                switch (parseInt(type)) {
                    // Неделя
                    case 0:
                        valToDate = 86400 * 7;
                        break;

                    // Две недели
                    case 1:
                        valToDate = 86400 * 14;
                        break;

                    // Месяц
                    case 2:
                        valToDate = 86400 * 30;
                        break;

                    // Два месяца
                    case 3:
                        valToDate = 86400 * 60;
                        break;

                    // Квартал
                    case 4:
                        valToDate = 86400 * 90;
                        break;

                    // Год
                    case 5:
                        valToDate = 86400 * 365;
                        break;

                    default:
                        valToDate = 86400 * 7;
                }

                var sevenDate = new Date(Math.ceil(((new Date().getTime() / 1000) + valToDate)) * 1000);
                var sevenDay = parseInt(sevenDate.getDate()) < 10 ? "0" + (sevenDate.getDate()) : sevenDate.getDate();
                var sevenMonth = parseInt(sevenDate.getMonth() + 1) < 10 ? "0" + (sevenDate.getMonth() + 1) : sevenDate.getMonth() + 1;
                var sevenDateStr = sevenDay + "." + sevenMonth + "." + sevenDate.getFullYear();

                $("input[name=orderfrom_datepicker]").val(curDateStr);
                $("input[name=orderto_datepicker]").val(sevenDateStr);
            },

            showOrdersByLink: function (type) {
                var valToDate = 0;
                var currentDate = new Date();
                var currentMonth = parseInt(currentDate.getMonth() + 1) < 10 ? "0" + (currentDate.getMonth() + 1) : currentDate.getMonth() + 1;
                var currentDay = parseInt(currentDate.getDate()) < 10 ? "0" + (currentDate.getDate()) : currentDate.getDate();
                var curDateStr = currentDay + "." + currentMonth + "." + currentDate.getFullYear();

                switch (parseInt(type)) {
                    // Сегодня
                    case 0:
                        valToDate = 0;
                        break;

                    // Завтра
                    case 1:
                        valToDate = 86400;
                        break;

                    // Неделя
                    case 2:
                        valToDate = 86400 * 7;
                        break;

                    // Две недели
                    case 3:
                        valToDate = 86400 * 14;
                        break;

                    // Месяц
                    case 4:
                        valToDate = 86400 * 30;
                        break;

                    // Два месяца
                    case 5:
                        valToDate = 86400 * 60;
                        break;

                    // Квартал
                    case 6:
                        valToDate = 86400 * 90;
                        break;

                    // Год
                    case 7:
                        valToDate = 86400 * 365;
                        break;

                    default:
                        valToDate = 0;
                }

                var sevenDate = new Date(Math.ceil(((new Date().getTime() / 1000) + valToDate)) * 1000);
                var sevenDay = parseInt(sevenDate.getDate()) < 10 ? "0" + (sevenDate.getDate()) : sevenDate.getDate();
                var sevenMonth = parseInt(sevenDate.getMonth() + 1) < 10 ? "0" + (sevenDate.getMonth() + 1) : sevenDate.getMonth() + 1;
                var sevenDateStr = sevenDay + "." + sevenMonth + "." + sevenDate.getFullYear();

                $("input[name=orderfrom_datepicker]").val(curDateStr);
                $("input[name=orderto_datepicker]").val(sevenDateStr);

                $.coremanage.showOrders();

                //$("#uploadForm").ajaxSubmit(optionsQuery, true, $.coremanage.GetEventId("SHOW_ORDERS"));
                //$('#showOrder').trigger('click');
            },

            showOrders: function () {
                $("#uploadForm").ajaxSubmit(optionsQuery, true, $.coremanage.GetEventId("SHOW_ORDERS"));
                $('#showOrder').trigger('click');
            },

            clearFilter: function () {
                var currentDate = new Date();
                var currentMonth = parseInt(currentDate.getMonth() + 1) < 10 ? "0" + (currentDate.getMonth() + 1) : currentDate.getMonth() + 1;
                var currentDay = parseInt(currentDate.getDate()) < 10 ? "0" + (currentDate.getDate()) : currentDate.getDate();
                var curDateStr = currentDay + "." + currentMonth + "." + currentDate.getFullYear();

                var sevenDate = new Date(Math.ceil(((new Date().getTime() / 1000) + 604800)) * 1000);
                var sevenDay = parseInt(sevenDate.getDate()) < 10 ? "0" + (sevenDate.getDate()) : sevenDate.getDate();
                var sevenMonth = parseInt(sevenDate.getMonth() + 1) < 10 ? "0" + (sevenDate.getMonth() + 1) : sevenDate.getMonth() + 1;
                var sevenDateStr = sevenDay + "." + sevenMonth + "." + sevenDate.getFullYear();

                $("input[name=orderfrom_datepicker]").val(curDateStr);
                $("input[name=orderto_datepicker]").val(sevenDateStr);
            },

            createOrder: function () {
                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) == $.messageLevels.SUCCESS) {
                        $("#uploadForm").resetForm();
                        setTimeout(function () {
                            window.location.href = "?p=orders";
                        }, 3000);
                    }

                    $.coreui.showDialog(receiveArray.message, receiveArray.status, receiveArray.status == $.messageLevels.DBERROR ? 0 : 3000);
                };

                $("#uploadForm").ajaxSubmit(optionsQuery, true, $.coremanage.GetEventId("CREATE_ORDER"));
            },

            editOrder: function () {
                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) == $.messageLevels.SUCCESS) {
                        setTimeout(function () {
                            window.location.href = "?p=orders";
                        }, 1500);
                    }

                    $.coreui.showDialog(receiveArray.message, receiveArray.status, receiveArray.status == $.messageLevels.DBERROR ? 0 : 3000);
                };

                $("#uploadForm").ajaxSubmit(optionsQuery, true, $.coremanage.GetEventId("EDIT_ORDER"));
            },

            saveOrder: function (elem) {
                var dialogElem = 0;
                var isSuccessed = false, showAlways = false;
                var queryStringDefault;
                var optionsQuerySuccessed = optionsQuery, optionsQueryDefault = optionsQuery, optionsQueryList = optionsQuery;
                var queryStringList = this.QueryString($.coremanage.GetEventId("GET_LIST"), "balanceTypeField");

                optionsQueryDefault.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) != $.messageLevels.SUCCESS)
                        $.coreui.showDialog(receiveArray.message, receiveArray.status);
                    else {
                        $("tr#id" + $(elem).attr("id")).animate({"background-color": $(elem).parent().find('option:selected').attr("color")}, 400);
                        $("tr#id" + $(elem).attr("id")).next("tr").animate({"background-color": $(elem).parent().find('option:selected').attr("color")}, 400);
                        $("tr#id" + $(elem).attr("id")).find("td span#balance").html("не указано");
                    }
                };

                queryStringDefault = this.QueryString($.coremanage.GetEventId("SAVE_ORDER"), parseInt($(elem).val()) + "&valid=" + parseInt($(elem).attr("id")) + "&type=" + $(elem).attr("type"));

                if (parseInt($(elem).parent().find('option:selected').attr("operation")) == 0) {
                    optionsQueryDefault.data = queryStringDefault;
                    $.ajax(optionsQueryDefault);
                }
                else {
                    optionsQueryList.success = function (receive) {
                        WriteMessageDebug(receive);

                        if (showAlways)
                            return false;

                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            title: 'Завершение заказа',
                            message: receive,
                            buttons: [{
                                label: 'Сохранить',
                                cssClass: 'btn-success',
                                hotkey: 13,
                                action: function (dialogRef) {
                                    dialogElem = dialogRef;
                                    optionsQuerySuccessed.data = queryStringDefault + "&balanceId=" + parseInt($(".selectpicker[type=prepayTypeField]").val());
                                    optionsQuerySuccessed.success = function (receive) {
                                        WriteMessageDebug(receive);

                                        $("tr#id" + $(elem).attr("id")).animate({"background-color": $(elem).parent().find('option:selected').attr("color")}, 400);
                                        $("tr#id" + $(elem).attr("id")).next("tr").animate({"background-color": $(elem).parent().find('option:selected').attr("color")}, 400, function () {
                                            if (parseInt($(elem).parent().find('option:selected').attr("operation")) == 1) {
                                                isSuccessed = true;
                                                dialogElem.close();
                                                $("tr#id" + $(elem).attr("id")).find("td span#balance").html($(".selectpicker[type=prepayTypeField]").find("option:selected").text());
                                            }
                                        });
                                    };
                                    $.ajax(optionsQuerySuccessed);
                                }
                            }],
                            onhide: function (dialogRef) {
                                if (!isSuccessed) {
                                    $.coreui.showDialog("Заказ не завершен!", $.messageLevels.ERROR);
                                    return false;
                                }
                            },
                            onshow: function () {
                                setTimeout(function () {
                                    $('.selectpicker[type=prepayTypeField]').selectpicker();
                                }, 200);
                                showAlways = true;
                            }
                        });
                    };

                    optionsQueryList.data = queryStringList;
                    $.ajax(optionsQueryList);
                }
            },

            systemEnter: function () {
                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) == $.messageLevels.SUCCESS) {
                        $(".bootstrap-dialog-message").find(".alert").removeClass("alert-danger").addClass("alert-success").html(receiveArray.message).slideDown();

                        setTimeout(function () {
                            BootstrapDialog.closeAll();
                            $.coremanage.showModule("main", false);
                        }, 1000);
                    }
                    else {
                        $(".bootstrap-dialog-message").find(".alert").addClass("alert-danger").html(receiveArray.message).slideDown();
                        $(".bootstrap-dialog-message").find(".input-group").addClass("has-error");
                        setTimeout(function () {
                            $(".bootstrap-dialog-message").find(".input-group").removeClass("has-error");
                            $(".bootstrap-dialog-message").find(".alert").slideUp();
                        }, 2000);
                    }
                };

                $("#uploadForm").ajaxSubmit(optionsQuery, true, $.coremanage.GetEventId("SYSTEM_ENTER"));
            },

            systemExit: function (isRefresh) {
                BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_LARGE,
                    type: BootstrapDialog.TYPE_INFO,
                    title: $.textPreset.DialogTitleConfirm,
                    message: $.textPreset.DialogMessageLogout,
                    buttons: [
                        {
                            label: $.textPreset.DialogButtonYes,
                            cssClass: 'btn-success',
                            hotkey: 13,
                            action: function (dialogRef) {
                                optionsQuery.success = function (receive) {
                                    WriteMessageDebug(receive);

                                    BootstrapDialog.closeAll();
                                    if (isRefresh)
                                        window.location.href = "?p=main";
                                    else
                                        $.coremanage.showModule("main", false);
                                };

                                optionsQuery.data = _postStr($.coremanage.GetEventId("SYSTEM_EXIT"));
                                $.ajax(optionsQuery);
                            }
                        },
                        {
                            label: $.textPreset.DialogButtonNo,
                            cssClass: 'btn-danger',
                            hotkey: 27,
                            action: function (dialogRef) {
                                dialogRef.close();
                            }
                        }
                    ]
                });
            },

            profileEdit: function () {
                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) != $.messageLevels.SUCCESS) {
                        $.coreui.showDialog(receiveArray.message, receiveArray.status, 0);
                    }
                };

                $("#uploadForm").ajaxSubmit(optionsQuery, true, $.coremanage.GetEventId("PROFILE_EDIT"));
            },

            profileEditPass: function () {
                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    if (parseInt(receiveArray.status) == $.messageLevels.SUCCESS) {
                        $(".bootstrap-dialog-message").find(".alert").removeClass("alert-danger").addClass("alert-success").html(receiveArray.message).slideDown();

                        setTimeout(function () {
                            BootstrapDialog.closeAll();
                        }, 1000);
                    }
                    else {
                        $(".bootstrap-dialog-message").find(".alert").addClass("alert-danger").html(receiveArray.message).slideDown();
                        $(".bootstrap-dialog-message").find(".input-group").addClass("has-error");
                        setTimeout(function () {
                            $(".bootstrap-dialog-message").find(".input-group").removeClass("has-error");
                            $(".bootstrap-dialog-message").find(".alert").slideUp();
                        }, 2000);
                    }

                };

                var passVal = $(".bootstrap-dialog-message").find("input[name=passField]").val().replace(/[|]/g,"");
                var passNewVal = $(".bootstrap-dialog-message").find("input[name=passNewField]").val().replace(/[|]/g,"");
                var passConfirmVal = $(".bootstrap-dialog-message").find("input[name=passConfirmField]").val().replace(/[|]/g,"");
                if ($.trim(passVal) == '' || $.trim(passNewVal) == '' || $.trim(passConfirmVal) == '')
                {
                    $(".bootstrap-dialog-message").find(".alert").addClass("alert-danger").html("Поле не может быть пустым").slideDown();
                    $(".bootstrap-dialog-message").find(".input-group").addClass("has-error");
                    setTimeout(function () {
                        $(".bootstrap-dialog-message").find(".input-group").removeClass("has-error");
                        $(".bootstrap-dialog-message").find(".alert").slideUp();
                    }, 2000);
                }
                else
                    $("#passForm").ajaxSubmit(optionsQuery, true, $.coremanage.GetEventId("PROFILE_EDIT"));
            },

            getList: function (typeList) {
                var queryString = this.QueryString($.coremanage.GetEventId("GET_LIST"), typeList);

                /*optionsQuery.success = function(receive)
                 {
                 WriteMessageDebug(receive);

                 var receiveArray = JSON.parse(receive);
                 $.coreui.showDialog(receiveArray.message, receiveArray.status, 1500);
                 }*/

                $.coremanage.DataSend(queryString);
            },

            showModule: function (module, isDialog) {
                var queryString = this.QueryString($.coremanage.GetEventId("SHOW_MODULE"), module);

                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    if (isDialog) {
                        BootstrapDialog.show({
                            cssClass: "modal-center",
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_INFO,
                            title: $(receive).find(".caption").val(),
                            message: receive
                        });
                    }
                    else
                    {
                        $("#viewPage").fadeOut("fast", function()
                        {
                            $("#viewPage").html(receive).fadeIn();
                        });
                    }
                };

                $.coremanage.DataSend(queryString);
            },
			
			showMap: function (addr) {
                BootstrapDialog.show({
					size: BootstrapDialog.SIZE_WIDE,
					type: BootstrapDialog.TYPE_INFO,
					title: "Просмотр адреса: " + addr,
					message: "<div style='width: 868px; height:500px;' id='yandex_map'></div>",
					onshow: function () {
                        setTimeout(function () {
                            var myMap = new ymaps.Map ("yandex_map", {
                                center: [53.195063, 45.018316],
                                zoom: 13
                            });

                            ymaps.ready(function()
                            {
                                var myGeocoder = ymaps.geocode("Пенза, " + addr);
                                myGeocoder.then(
                                    function (res)
                                    {
                                        var firstGeoObject = res.geoObjects.get(0),
                                            // Координаты геообъекта.
                                            coords = firstGeoObject.geometry.getCoordinates(),
                                            // Область видимости геообъекта.
                                            bounds = firstGeoObject.properties.get('boundedBy');

                                        // Добавляем первый найденный геообъект на карту.
                                        myMap.geoObjects.add(firstGeoObject);
                                        // Масштабируем карту на область видимости геообъекта.
                                        myMap.setBounds(bounds, {
                                            // Проверяем наличие тайлов на данном масштабе.
                                            checkZoomRange: true
                                        });
                                    },
                                    function (err) {
                                        console.log(err);
                                    }
                                );
                            });
                        }, 200);
                        showAlways = true;
                    }
                });
            },

            showStatistic: function (isSale) {
                optionsQuery.success = function (receive) {
                    WriteMessageDebug(receive);

                    var receiveArray = JSON.parse(receive);
                    console.log(receiveArray);
                    $("#myChart").remove();

                    $(".wrap-table100").append('<canvas id="myChart"></canvas>');
                    var ctx = document.getElementById("myChart");
                    if (ctx != undefined) {
                        ctx.getContext('2d').clearRect(0, 0, $("#myChart").width(), $("#myChart").height());
                        var dataArr = {
                            labels: receiveArray.date,
                            datasets: [{
                                label: "",
                                borderColor: '#248ee6',
                                fill: false,
                                data: receiveArray.amount
                            }]
                        };

                        var optionsArr = {
                            title: {
                                display: false
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false
                                    }
                                }],
                                    yAxes: [{
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false,
                                    }
                                }]
                            },
                            chartArea: {
                                backgroundColor: '#fff'
                            }
                        };

                        salesBackgroundColors = [
                            "white",
                            "silver",
                            "gray",
                            "red",
                            "orange",
                            "yellow",
                            "green",
                            "skyblue"
                        ];

                        if (isSale) {
                            dataArr = {
                                labels: receiveArray.saleName,
                                datasets: [{
                                    borderColor: '#248ee6',
                                    backgroundColor: salesBackgroundColors,
                                    data: receiveArray.saleCount
                                }]
                            };

                            optionsArr = {

                            };
                        }
                        var chart = new Chart(ctx.getContext('2d'), {
                            type: isSale ? 'pie' : 'line',
                            data: dataArr,
                            options: optionsArr
                        });
                    }

                    $(".summary .form-group").html(receiveArray.summary);

                    $(".chartTableBlock").find(".chartTable").html("");
                    chartTableBlock = $(".chartTableBlock").clone();
                    $(".chartTableBlock").remove();

                    var chartTableRow = "<tr></tr>";
                    if (isSale) {
                        chartTableBlock.find(".chartTable").append("<thead><tr><th>#</th><th>Канал</th><th>Продаж</th></tr></thead>");
                        for (i = 0; i < receiveArray.saleName.length; i++) {
                            chartTableRow = $("<tr></tr>");
                            chartTableRow.css("background-color", salesBackgroundColors[i]);
                            chartTableRow.append("<td>" + (i + 1) + "</td>");
                            chartTableRow.append("<td>" + receiveArray.saleName[i] + "</td>");
                            chartTableRow.append("<td>" + receiveArray.saleCount[i] + "</td>");
                            chartTableBlock.find(".chartTable").append(chartTableRow);
                        }
                    } else {
                        chartTableBlock.find(".chartTable").append("<thead><tr><th>#</th><th>" + (receiveArray.rangeType === "d" ? "День" : "Месяц") + "</th><th>Сумма</th></tr></thead>");
                        for (i = 0; i < receiveArray.date.length; i++) {
                            chartTableRow = $("<tr></tr>");
                            chartTableRow.append("<td>" + (i + 1) + "</td>");
                            chartTableRow.append("<td>" + receiveArray.date[i] + "</td>");
                            chartTableRow.append("<td>" + receiveArray.amount[i] + "</td>");
                            chartTableBlock.find(".chartTable").append(chartTableRow);
                        }
                    }

                    $("#myChart").parent().append(chartTableBlock);
                    chartTableBlock.show();
                    chartTableBlock.find(".chartTable").DataTable({
                        "order": [[ 3, "desc" ]]
                    });
                };

                $("#uploadForm").ajaxSubmit(optionsQuery, true, isSale ? $.coremanage.GetEventId("SHOW_STATISTIC_SALE") : $.coremanage.GetEventId("SHOW_STATISTIC"));
            },

            citiesEdit: function (isSale) {

            }
        };
    })();

    $.fn.coremanage = function () {
        return fn.call(this);
    }

})(jQuery);