function addSelectItem(t, ev)
{
    ev.stopPropagation();

    var bs = $(t).closest('.bootstrap-select');
    var elemSelect = bs.find('select');
    var textVal = bs.find('.bss-input').val().replace(/[|]/g,"");
    var textVal = $(t).prev().val().replace(/[|]/g,"");
    if ($.trim(textVal) == '')
    {
        $.coreui.showDialog("Поле не может быть пустым", $.messageLevels.ERROR, 1000);
        return;
    }

    var elemOption = $('option', elemSelect).eq(1);
    $.coremanage.listAdd(elemOption, elemSelect, textVal);
}

function addKeywordItem(t)
{
    var textVal = $(t).prev().val().replace(/[|]/g,"");
    if ($.trim(textVal) == '')
    {
        $.coreui.showDialog("Поле не может быть пустым", $.messageLevels.ERROR, 1000);
        $(".keywordContent span.keywordBtn").trigger("click");
        return;
    }

    $.coremanage.listAdd($(t), null, textVal);
}

function removeSelectItem(t, ev)
{
    ev.stopPropagation();

    $.coremanage.listRemove(t, $(t).closest('.bootstrap-select').find('select'));

    return;
}

function editSelectItem(t, ev)
{
    ev.stopPropagation();

    $.coremanage.listEdit(t, $(t).closest('.bootstrap-select').find('select'));

    return;
}

function addSelectInpKeyPress(t,ev)
{
    ev.stopPropagation();

    // do not allow pipe character
    if (ev.which==124) ev.preventDefault();

// enter character adds the option
    if (ev.which==13)
    {
        ev.preventDefault();
        addSelectItem($(t).next(),ev);
    }
}

$(function()
{
    var isChangeTime = false;
    var content = "<input type='text' class='form-control bss-input' style='width: 94.5%; display: inline;' onKeyDown='event.stopPropagation();' onKeyPress='addSelectInpKeyPress(this, event)' onClick='event.stopPropagation()' placeholder='Добавить в список'> <span class='glyphicon glyphicon-plus addnewicon form-control-add' onClick='addSelectItem(this, event);'></span>";

    $('.selectpicker[name=salesTypeField]').prepend($('<option/>', {class: 'addItem'}).data('content', content)).selectpicker();

    if ($('.selectpicker[name=prepayTypeField]').attr("data-showinsert") == "true")
        $('.selectpicker[name=prepayTypeField]').prepend($('<option/>', {class: 'addItem'}).data('content', content)).selectpicker();

    $('.selectpicker[name=eventField]').prepend($('<option/>', {class: 'addItem'}).data('content', content)).selectpicker();
    $('.selectpicker[name=nameField]').prepend($('<option/>', {class: 'addItem'}).data('content', content)).selectpicker();
    $('.selectpicker[name=streetField]').prepend($('<option/>', {class: 'addItem'}).data('content', content)).selectpicker();

    $(document).ready(function()
    {
        var datePickerMoment = new Date();
        var timePickerMoment = new Date();

        $('[data-toggle="tooltip"]').tooltip();

        $.coreui.editBoxFocus();
        $.coreui.editBoxBlur();

        if ($('#orderdatepicker input[type=hidden]').val() != undefined && $('#orderdatepicker input[type=hidden]').val().length > 0)
            datePickerMoment = $('#orderdatepicker input[type=hidden]').val();

        if ($('#ordertimepicker input[type=hidden]').val() != undefined && $('#ordertimepicker input[type=hidden]').val().length > 0) {
            timePickerMoment = datePickerMoment + " " + $('#ordertimepicker input[type=hidden]').val() + ":00";
            isChangeTime = true;
        }

        $('#orderdatepicker').datetimepicker({
            inline: true,
            sideBySide: false,
            locale: 'ru',
            format: 'DD.MM.YYYY',
            defaultDate: moment(datePickerMoment)
        });

        $('#ordertimepicker').datetimepicker({
            inline: true,
            sideBySide: true,
            locale: 'ru',
            format: 'HH:mm',
            defaultDate: moment(timePickerMoment)
        });

        $('#orderfrom_datepicker, #orderto_datepicker').datetimepicker({
            locale: 'ru',
            format: 'DD.MM.YYYY',
            defaultDate: moment(new Date())
        });

        $('#orderfrom_datepicker').on('dp.change', function(e){
            var strToArr = $("input[name=orderto_datepicker]").val().split(".");
            var strFromArr = $("input[name=orderfrom_datepicker]").val().split(".");
            var strDateTo = new Date(strToArr[2] + "-" + strToArr[1] + "-" + strToArr[0]).getTime();
            var strDateFrom = new Date(strFromArr[2] + "-" + strFromArr[1] + "-" + strFromArr[0]).getTime();

            if (strDateFrom > strDateTo) {
                $("input[name=orderto_datepicker]").val($("input[name=orderfrom_datepicker]").val());
            }
        });

        $('#orderto_datepicker').on('dp.change', function(e){
            var strToArr = $("input[name=orderto_datepicker]").val().split(".");
            var strFromArr = $("input[name=orderfrom_datepicker]").val().split(".");
            var strDateTo = new Date(strToArr[2] + "-" + strToArr[1] + "-" + strToArr[0]).getTime();
            var strDateFrom = new Date(strFromArr[2] + "-" + strFromArr[1] + "-" + strFromArr[0]).getTime();

            if (strDateTo < strDateFrom) {
                $("input[name=orderto_datepicker]").parent().find("span.input-group-addon").animate({"background-color": "#ffaca7"}, 400, "linear", function(){
                    $(this).animate({"background-color": "#eee"}, 400, "linear");
                    $("input[name=orderto_datepicker]").val($("input[name=orderfrom_datepicker]").val());
                });
            }
        });

        $(".fancybox").fancybox({
            openEffect	: 'none',
            closeEffect	: 'none',
            arrows: false,
            loop: false,
            closeBtn: false,
            helpers: {
                title: {type: 'float'},
                buttons: {}
            }
        });

        $(".selectpicker#orderType").change(function()
        {
            if (parseInt($(this).val()) == 2)
                $(".section_content").removeClass("hide");
            else
                $(".section_content").addClass("hide");
        });

        $(".selectpicker[type=statusTypeField]").change(function()
        {
            $.coremanage.saveOrder($(this));
        });

        $(".selectpicker[type=prepayTypeField], .selectpicker[type=balanceTypeField]").change(function()
        {
            $.coremanage.saveOrder($(this));
        });

        $('table.table-condensed tr td a.btn').on("click", function(e)
        {
           isChangeTime = true;
            $("table.table-condensed tr td.separator").removeClass("opacityHide");
        });

        setInterval(function()
        {
            if (!isChangeTime)
                $("table.table-condensed tr td.separator").toggleClass("opacityHide");
        }, 900);

        setInterval(function()
        {
            var curDate = new Date(Date.now());

            if (!isChangeTime) {
                $("table.table-condensed tr td span.timepicker-minute").html(parseInt(curDate.getMinutes()) < 10  ? "0" + curDate.getMinutes() : curDate.getMinutes());
                $("table.table-condensed tr td span.timepicker-hour").html(parseInt(curDate.getHours()) < 10  ? "0" + curDate.getHours() : curDate.getHours());
            }
        }, 5000);

        $(".keywordContent span.keywordBtn").click(function()
        {
            $(".keywordContent div.keywordInsert").slideToggle();
        });

        $(".keywordContent").on("click", ".keywordValue", function(e)
        {
            if ($("textarea[name=descField]").html().length == 0)
                $("textarea[name=descField]").append($(this).html());
            else
                $("textarea[name=descField]").append("&nbsp;" + $(this).html());
        });

        $(".keywordContent").on("click", "sup", function(e)
        {
            $.coremanage.listRemove(null, this);
        });

        var current_fs, next_fs, previous_fs;

        $("button[name=next]").click(function(){
            btnElem = $(this);
            current_fs = $(this).parent().parent();
            next_fs = $(this).parent().parent().next();
            current_fs.fadeOut("fast", function () {
                next_fs.fadeIn("fast");
            });
        });

        $("button[name=previous]").click(function(){
            btnElem = $(this);
            current_fs = $(this).parent().parent();
            previous_fs = $(this).parent().parent().prev();

            current_fs.fadeOut("fast", function () {
                previous_fs.fadeIn("fast");
            });
        });

        $("button[name=cancel]").click(function(){
            window.history.back();
        });

        $(".grid-item").on("click", function()
        {
            if ($(this).attr("id") == "login")
            {
                //$.coreui.showDialog("bla", $.messageLevels.DBERROR, 0);

            }
        });
    });
});