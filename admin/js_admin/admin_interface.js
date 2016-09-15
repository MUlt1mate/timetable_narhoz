$(function () {
        $('.shedule').click(function () {
            window.location = '/?action=edit&shedule_id=' + $(this).attr('shedule_id');
        });

        $('.shedule_edit').click(function () {
            $('#shedule_id').val($(this).parent().attr('shedule_id'));
            $('#shedule_name').val($(this).parent().attr('title'));
            $('#shedule_status').val($(this).parent().attr('status_id'));
            $('#shedule_type').val($(this).parent().attr('type_id'));
            $('#shedule_year').val($(this).parent().attr('year'));
            $('#shedule_numterm').val($(this).parent().attr('numterm'));
            $('#shedule_formstudy').val($(this).parent().attr('formstudy_id'));
            $('#shedule_date_begin').val($(this).parent().attr('date_begin').replace(/\./g, '-'));
            $('#shedule_date_end').val($(this).parent().attr('date_end').replace(/\./g, '-'));
            $('#add_edit_shedule_btn').removeClass('btn-primary').addClass('btn-success').attr('value', 'Изменить');
            $('#new_shedule_title').text('Редактирование');
            return false;
        });

        $('#reset_shedule_btn').click(function () {
            $('#add_edit_shedule_btn').removeClass('btn-success').addClass('btn-primary').attr('value', 'Добавить');
            $('#shedule_id').val('');
            $('#new_shedule_title').text('Новое расписание');
        });

        $('.room').click(function () {
            window.location = '/?action=timetable&room=' + $(this).attr('id');
        });

        //Timetable edit begin ============================================

        $('#edit_button').click(function () {
            if ('none' == $('#labels').css('display')) {
                $('#labels').css('display', '');
                $('#selects').css('display', 'none');
            } else {
                $('#labels').css('display', 'none');
                $('#selects').css('display', '');
            }
        });

        $('#PlanTable').on('click', '.plan_table_row', function () {
            $('#labels').css('display', '');
            $('#selects').css('display', 'none');
            $('#timegrid_id').val('');
            $('#button_add').removeClass('btn-success').addClass('btn-primary')
                .html('<i class="icon-plus icon-white"></i> Добавить');

            $('#CodGrup_edit').val($(this).attr('group_flow_id'));
            $('#is_flow').val($(this).attr('is_flow'));
            $('#CodPrep_edit').val($(this).attr('teacher_id'));
            $('#CodSubs_edit').val($(this).attr('lesson_id'));
            $('#TypeLesson_edit').val($(this).attr('lesson_type_id'));

            $('#CodGrup_name').text($($('td', this)[0]).text());
            $('#CodPrep_name').text($($('td', this)[1]).text());
            $('#CodSubs_name').text($($('td', this)[2]).text());
            $('#TypeLesson_name').text($($('td', this)[3]).text());

            if (0 == $(this).attr('is_flow'))
                set_param('group', $(this).attr('group_flow_id'));
            else
                set_param('flow', $(this).attr('group_flow_id'));
            set_param('is_flow', $(this).attr('is_flow'));
            set_param('teacher', $(this).attr('teacher_id'));
            set_param('lesson', $(this).attr('lesson_id'));
            set_param('lesson_type', $(this).attr('lesson_type_id'));
            select_subgroup($(this).attr('subgroup'));
            Load_busy_table();
            Load_rooms_table();
        });

        $('#TimeTable').on('click', '.time_table_row', function () {
            $('#labels').css('display', 'none');
            $('#selects').css('display', '');
            $('#timegrid_id').val($(this).attr('timegrid_id'));
            $('#button_add').removeClass('btn-primary').addClass('btn-success')
                .html('<i class="icon-ok icon-white"></i> Изменить');

            $('#CodGrup_edit').val($(this).attr('group_flow_id'));
            $('#is_flow').val($(this).attr('is_flow'));
            $('#CodPrep_edit').val($(this).attr('teacher_id'));
            $('#CodSubs_edit').val($(this).attr('lesson_id'));
            $('#TypeLesson_edit').val($(this).attr('lesson_type_id'));
            $('#CodRoom_edit').val($(this).attr('room_id'));
            $('#CodTime_begin_edit').val($(this).attr('time_id'));
            $('#lesson_date_begin_edit').val($(this).attr('date_begin'));
            $('#lesson_date_end_edit').val($(this).attr('date_end'));
            if (0 == $(this).attr('is_flow'))
                set_param('group', $(this).attr('group_flow_id'));
            else
                set_param('flow', $(this).attr('group_flow_id'));
            set_param('is_flow', $(this).attr('is_flow'));
            set_param('teacher', $(this).attr('teacher_id'));
            set_param('lesson', $(this).attr('lesson_id'));
            set_param('room', $(this).attr('room_id'));
            set_param('lesson_type', $(this).attr('lesson_type_id'));
            set_param('time', $(this).attr('time_id'));
            select_subgroup($(this).attr('subgroup'));
            select_week($(this).attr('week'));
            select_weekday($(this).attr('weekday_id'));
            Load_busy_table();
            Load_rooms_table();
        });

        function select_subgroup(subgroup) {
            $('.subgroup').removeClass('btn-success').addClass('btn-primary');
            $('#subgroup' + subgroup).button('toggle').removeClass('btn-primary').addClass('btn-success');
            $('#subgroup').val(subgroup);
            set_param('subgroup', subgroup);
        }

        function select_weekday(weekday_id) {
            $('#weekday' + weekday_id).button('toggle');
            $('#weekday_id').val(weekday_id);
            set_param('weekday', weekday_id);
        }

        function select_week(week) {
            $('.week').removeClass('btn-danger').addClass('btn-warning');
            $('#week' + week).button('toggle').removeClass('btn-warning').addClass('btn-danger');
            $('#week').val(week);
            set_param('week_odd', week);
        }

        $('#faculty').change(function () {
            set_param('faculty', $(this).val());
            load_groups_list();
            load_teachers_list();
        });

        $('#TypePlanWork').change(function () {
            set_param('plan_work', $(this).val());
            load_groups_list();
            load_teachers_list();
            Load_plan_table();
        });

        $('#course').change(function () {
            set_param('course', $(this).val());
            load_groups_list();
            load_teachers_list();
        });

        $('#CodGrup').change(function () {
            set_param('group_list', $(this).val());
            load_teachers_list();
            Load_busy_table();
            Load_plan_table();
            Load_rooms_table();
            Load_time_table();
        });

        $('#CodPrep').change(function () {
            set_param('teacher_list', $(this).val());
            load_groups_list();
            Load_busy_table();
            Load_plan_table();
            Load_rooms_table();
            Load_time_table();
        });

        $('#CodGrup_edit').change(function () {
            set_param('group', $(this).val());
        });

        $('#CodPrep_edit').change(function () {
            set_param('teacher', $(this).val());
        });

        $('#CodSubs_edit').change(function () {
            set_param('lesson', $(this).val());
        });

        $('#TypeLesson_edit').change(function () {
            set_param('lesson_type', $(this).val());
        });

        $('#CodRoom_edit').change(function () {
            set_param('room', $(this).val());
        });

        $('#CodTime_begin_edit').change(function () {
            set_param('time', $(this).val());
        });


        $('#copy_left_date').click(function () {
            $('#lesson_date_end_edit').val($('#lesson_date_begin_edit').val());
        });

        $('#copy_right_date').click(function () {
            $('#lesson_date_begin_edit').val($('#lesson_date_end_edit').val());
        });

        $('#remove_left_date').click(function () {
            $('#lesson_date_begin_edit').val('');
        });

        $('#remove_right_date').click(function () {
            $('#lesson_date_end_edit').val('');
        });

        $('#subgroup_button_edit button').click(function () {
            select_subgroup($(this).attr('value'));
            Load_busy_table();
            Load_rooms_table();
        });

        $('#week_button_edit button').click(function () {
            select_week($(this).attr('value'));
            Load_busy_table();
            Load_rooms_table();
        });

        $('#weekday_button_edit button').click(function () {
            select_weekday($(this).attr('value'));
            Load_busy_table();
            Load_rooms_table();
        });

        function set_param(param_name, param_value) {
            $.cookie('param_' + param_name, param_value);
        }

        function load_groups_list() {
            load_list('groups', $('#CodGrup'));
        }

        function load_teachers_list() {
            load_list('teachers', $('#CodPrep'));
        }

        function load_list(list, container) {
            $.get('/?action=list&list=' + list, function (data) {
                container.html(data);
            });
        }

        function Load_busy_table() {
            $.get('/?action=busy_table', function (data) {
                $('#BusyTable').html(data);
                new_lesson_refresh();
            });
        }

        function Load_rooms_table() {
            $.get('/?action=rooms_table', function (data) {
                $('#RoomTable').html(data);
            });
        }

        function Load_time_table() {
            $.get('/?action=time_table', function (data) {
                $('#TimeTable').html(data);
                $('#timetable_hours_label').text($('#timetable_hours_value').val());
            });
        }

        function Load_plan_table() {
            $.get('/?action=plan_table', function (data) {
                $('#PlanTable').html(data);
                $('#planwork_hours_label').text($('#planwork_hours_value').val());
            });
        }

        $('#RoomTable').on('click', '.room_table', function () {
            $('.room_table_active').removeClass('room_table_active');
            $(this).addClass('room_table_active');
            $('#CodRoom_edit').val($(this).attr('number'));
            $('#CodRoom_name').text($(this).find('.room_name').text());
            set_param('room', $(this).attr('number'));
            Load_busy_table();
        });

        $('#BusyTable').on('click', '.overpair, .overhour', function () {
            var time = $(this).text().trim();
            add_new(
                $(this).attr('top'),
                $(this).attr('duration'),
                $(this).parent().parent().attr('weekday_id'),
                time.substr(0, 5),
                time.substr(-5));
            set_param('time_begin', time.substr(0, 5));
            set_param('time_end', time.substr(-5));
            Load_rooms_table();
        });

        $('#button_add').click(function () {
            if ('' == $('#timegrid_id').val()) {
                $.get('/?action=add_new_lesson', function (data) {
                    Load_busy_table();
                    Load_rooms_table();
                    Load_time_table();
                    if ('success' != data)
                        alert('Ошибка при создании');
                });
            } else {
                $.get('/?action=edit_lesson&id=' + $('#timegrid_id').val(), function (data) {
                    Load_busy_table();
                    Load_rooms_table();
                    Load_time_table();
                    $('#labels').css('display', '');
                    $('#selects').css('display', 'none');
                    $('#timegrid_id').val('');
                    $('#button_add').removeClass('btn-success').addClass('btn-primary')
                        .html('<i class="icon-plus icon-white"></i> Добавить');
                    if ('success' != data)
                        alert('Ошибка при редактировании');
                });
            }
        });

        $('#TimeTable').on('click', '.delete_prepare', function () {
            $.to_delete = $(this).attr('timegrid_id');
        });

        $('#delete_button').click(function () {
            $.post('/?action=delete_lesson', 'lesson_id=' + $.to_delete, function (data) {
                if ('success' != data)
                    alert('Ошибка при удалении');
                else {
                    $('#lesson' + $.to_delete).remove();
                    Load_busy_table();
                    Load_rooms_table();
                }
            })
        });

        function add_new(offset, duration, weekday, begin, end) {
            if (undefined != $.new_lesson) {
                $.new_lesson.css('display', 'none');
            }
            $.old_lesson = $.new_lesson;
            if ($.composite_time) {
                if ($.old_lesson.weekday == weekday) {
                    if ($.old_lesson.offset < offset) {
                        duration = (duration - 0) + (offset - 0) - $.old_lesson.offset;
                        offset = $.old_lesson.offset;
                        begin = $.old_lesson.begin;
                    } else {
                        duration = $.old_lesson.duration + $.old_lesson.offset - offset;
                        end = $.old_lesson.end;
                    }
                }
                $.composite_time = false;
            }
            $.new_lesson = $('#new_lesson' + weekday);
            $.new_lesson.html('<div class="time">' + begin + ' - ' + end + '</div>');
            $.new_lesson.css('background', "rgba(50, 50, 50, 0.3)");
            $.new_lesson.css('display', '');
            $.new_lesson.css('top', offset + 'px');
            $.new_lesson.css('height', duration + 'px');
            $.new_lesson.weekday = weekday - 0;
            $.new_lesson.offset = offset - 0;
            $.new_lesson.duration = duration - 0;
            $.new_lesson.begin = begin;
            $.new_lesson.end = end;
            $('#CodTime_begin_name').text(begin + ' - ' + end);
            var time_value = $('#CodTime_begin_edit option[time_value="' + begin + ' - ' + end + '"]').attr('value');
            $('#CodTime_begin_edit').val(time_value);
            check_time_correct();
            select_weekday(weekday);
        }

        function new_lesson_refresh() {
            if (undefined != $.new_lesson) {
                add_new($.new_lesson.offset, $.new_lesson.duration, $.new_lesson.weekday, $.new_lesson.begin, $.new_lesson.end);
            }
        }

        function check_time_correct() {
            if (0 < $('#CodTime_begin_edit').val()) {
                $('#CodTime_begin_name').css('color', 'green');
                set_param('time', $('#CodTime_begin_edit').val());
            } else {
                $('#CodTime_begin_name').css('color', 'red');
            }
        }

        function compose_time() {
            if (undefined != $.new_lesson) {
                $.new_lesson.css('background', "rgba(50, 100, 100, 0.3)");
                $.composite_time = true;
            }
        }

        //быстрый переход между таблицами. Составное занятие
        $(document).keydown(function (e) {
            switch (e.keyCode) {
                case 16:
                    compose_time();
                    break;
                case 49:
                    window.location.href = '#top';
                    break;
                case 50:
                    window.location.href = '#PlanTable';
                    break;
                case 51:
                    window.location.href = '#BusyTable';
                    break;
                case 52:
                    window.location.href = '#RoomTable';
                    break;
            }
        });

        if ($('#shedule_panel').length) {
            Load_busy_table();
            Load_plan_table();
            Load_rooms_table();
            Load_time_table();
            load_groups_list();
            load_teachers_list();
            $.composite_time = false;
        }
    }
);