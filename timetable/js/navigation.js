$(function () {
        if (($.cookie('mode') == undefined))
            mode_change('week');
        else
            mode_change($.cookie('mode'));
        $('#FormStudyTab a:first').tab('show');
        $.params = get_params();

        if ($('#MainTable').length) {
            $('#MainTable').carousel({
                interval: 0
            });
            $.week = $('#week_id').val() - 0;
            $.month = $('#month_id').val() - 0;
            $.subgroup = 0;
            resetTimetable();
        }

        function addGrid(grid_id, edge) {
            var grid = '<div class="item"><div id="grid' + grid_id + '" grid_id="' + grid_id + '"></div></div>';
            if ('left' == edge)
                $('#grid_carousel').prepend(grid);
            else if ('right' == edge)
                $('#grid_carousel').append(grid);
            LoadTimeTable(grid_id);
        }

        function resetTimetable() {
            $('#MainTable').carousel($('.item').index($('.item[original]')));
            $('.item:not([original])').remove();
            LoadTimeTable(0, true);
            $.week = $('#week_id').val() - 0;
            $.month = $('#month_id').val() - 0;
            if ('agenda' != $.mode) {
                $.grid_num_right = 1;
                addGrid($.grid_num_right, 'right');

                $.grid_num_left = -1;
                addGrid($.grid_num_left, 'left');
            }
        }

        function LoadTimeTable(grid_id, load_dates) {
            var selector = $('#grid' + grid_id);
            $.interval = '';
            if ('week' == $.mode)
                $.interval = "&week=" + ($.week + grid_id);
            if ('month' == $.mode)
                $.interval = "&month=" + ($.month + grid_id);
            if (undefined != $.params['group']) {
                $.tt_type = 'group=' + $.params['group'];
                if (undefined != $.params['subgroup'])
                    $.tt_type += '&subgroup=' + $.params['subgroup'];
            } else if (undefined != $.params['teacher'])
                $.tt_type = 'teacher=' + $.params['teacher'];
            else if (undefined != $.params['room'])
                $.tt_type = 'room=' + $.params['room'];
            $.url = '/?' + $.tt_type + '&action=main_table' + '&mode=' + $.mode + $.interval;

            $.get($.url, function (data) {
                selector.html(data);
                if (load_dates)
                    $('#date_interval').text($('.item.active #date_interval_new').val());
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
            resetTimetable();
        });

        var load_next_period = function () {
            if ('agenda' == $.mode)
                return false;
            if ($('.item').index($('.item:last')) ==
                $('.item').index($('.item.active')) + 1) {
                ++$.grid_num_right;
                addGrid($.grid_num_right, 'right');
            }
            $('#MainTable').carousel('next');
        };

        var load_prev_period = function () {
            if ('agenda' == $.mode)
                return false;
            if ($('.item').index($('.item:first')) ==
                $('.item').index($('.item.active')) - 1) {
                --$.grid_num_left;
                addGrid($.grid_num_left, 'left');
            }
            $('#MainTable').carousel('prev');
        };

        $('#GoRight').click(load_next_period);
        $('#GoLeft').click(load_prev_period);

        window.onkeydown = function (event) {
            if (event.keyCode == 37) {
                load_prev_period();
            }
            if (event.keyCode == 39) {
                load_next_period();
            }
        };

        var sq = {};
        sq.e = document.getElementById("MainTable");

        if (sq.e.addEventListener) {
            sq.e.addEventListener("mousewheel", MouseWheelHandler, false);
            sq.e.addEventListener("DOMMouseScroll", MouseWheelHandler, false);
        } else {
            sq.e.attachEvent("onmousewheel", MouseWheelHandler);
        }

        function MouseWheelHandler(e) {

            // cross-browser wheel delta
            var e = window.event || e;
            var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
            if (-1 == delta) {
                load_next_period();
            } else {
                load_prev_period();
            }
            return false;
        }


        $('#LoadCurrent').click(function () {
            if ('agenda' == $.mode)
                return false;
            $('#MainTable').carousel($('.item').index($('.item[original]')));
        });

        $('.mode_change').click(function () {
            mode_change($(this).attr('mode'));
            resetTimetable();
        });

        function mode_change(mode) {

            ga('set', 'dimension1', mode);

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

        $('body').on('slid', function () {
            $('#date_interval').text($('.item.active #date_interval_new').val());
        }).on('click', '#popover_close', function () {
            $.lesson.popover('destroy');
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
                content: $('#popover_default').html(),
                container: 'body'
            });
            $(this).popover('show');
            $('.arrow').css('top', '15%');
            $.get('/?action=lesson_info&id=' + $(this).attr('lesson_id'), function (data) {
                $('.popover-content').html(data)
            })

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