$(function () {
        if (($.cookie('mode') == undefined))
            mode_change('week');
        else
            $.mode = $.cookie('mode');
        $('#FormStudyTab a:first').tab('show');
        $.params = get_params();

        if (($('#MainTable').length) && ($('#MainTable').text() == '')) {
            $.week = $.week_original = $('#week_id').val();
            $.month = $.month_original = $('#month_id').val();
            $.subgroup = 0;
            LoadTimeTable();
        }

        function LoadTimeTable() {
            $.interval = '';
            if ('week' == $.mode)
                $.interval = "&week=" + $.week;
            if ('month' == $.mode)
                $.interval = "&month=" + $.month;
            if (undefined != $.params['group']) {
                $.tt_type = 'group=' + $.params['group'];
                if (undefined != $.params['subgroup'])
                    $.tt_type += '&subgroup=' + $.params['subgroup'];
            } else if (undefined != $.params['teacher'])
                $.tt_type = 'teacher=' + $.params['teacher'];
            $.url = '/?' + $.tt_type + '&action=main_table' + '&mode=' + $.mode + $.interval;


            $.get($.url, function (data) {
                $('#MainTable').html(data);
                $("#date_interval").text($("#date_interval_new").val());
            });
        }

        $('#GetExport').click(function () {
            $.url = "/?action=export";
            if ($.params['teacher'] != undefined) {
                $.url += "&teacher=" + $.params['teacher'];
            } else if ($.params['group'] != undefined) {
                $.url += "&group=" + $.params['group'];
                if ($.params['subgroup'] != undefined)
                    $.url += "&subgroup=" + $.params['subgroup'];
            }
            $.get($.url, function (data) {
                $("#ExportLinkInput").val(data);
            });
        });

        $('.set_subgroup').click(function () {
            $('#subgroup_name').text($(this).text());
            $.params['subgroup'] = $(this).attr('sub_id');
            LoadTimeTable();
        });

        $('#GoRight').click(function () {
            $.week++;
            $.month++;
            LoadTimeTable();
        });

        $('#GoLeft').click(function () {
            $.week--;
            $.month--;
            LoadTimeTable();
        });

        $('#LoadCurrent').click(function () {
            $.week = $.week_original;
            $.month = $.month_original;
            LoadTimeTable();
        });

        $('.mode_change').click(function () {
            mode_change($(this).attr('mode'));
            LoadTimeTable();
        });

        function mode_change(mode) {
            $.cookie('mode', mode);
            $.mode = mode;
            if ('agenda' == mode)
                $('#GoLeft,#GoRight,#LoadCurrent').addClass('disabled');
            else
                $('#GoLeft,#GoRight,#LoadCurrent').removeClass('disabled');

        }

        function get_params() {
            var get = location.search;
            var param = [];
            if (get != '') {
                tmp = (get.substr(1)).split('&');
                for (var i = 0; i < tmp.length; i++) {
                    tmp2 = tmp[i].split('=');
                    param[tmp2[0]] = tmp2[1];
                }
            }
            return param;
        }

        $('#disclaimer_close').click(function () {
            $('#disclaimer_info').css('display', '');
            $.cookie('hide_alert', 1);
        });

        $('#MainTable').on('click', '.lesson, .agenda_lesson, .month_lesson', function () {
            if ($.lesson)
                $.lesson.popover('destroy');
            $.q = $(this).parent().parent().attr('weekday_id');
            var pl;
            if (3 < $.q)
                pl = 'left';
            else
                pl = 'right';
            $.lesson = $(this).popover({
                title: 'Подробная информация' +
                    '<button id="popover_close" type="button" class="close" data-dismiss="alert">&times;</button>',
                placement: pl,
                html: true,
                content: $('#popover_default').html()
            });
            $(this).popover('show');
            $('.arrow').css('top', '15%');
            $.get('/?action=lesson_info&id=' + $(this).attr('lesson_id'), function (data) {
                $('.popover-content').html(data)
            })

        });

        $('#MainTable').on('click', '#popover_close', function () {
            $.lesson.popover('destroy');
        });

    }

)
;

function FullWidth(lesson) {
    window.current_width = lesson.style.width;
    window.current_left = lesson.style.left;
    lesson.style.width = '95%';
    lesson.style.left = '0px';
    lesson.style.zIndex = 1;
}

function NormalWidth(lesson) {
    lesson.style.width = window.current_width;
    lesson.style.left = window.current_left;
    lesson.style.zIndex = 0;
}