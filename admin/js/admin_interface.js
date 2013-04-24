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

        $('#faculty').change(function () {
            set_param('faculty', $(this).val());
            load_groups_list();
            load_teachers_list();
        });

        $('#TypePlanWork').change(function () {
            set_param('plan_work', $(this).val());
            load_groups_list();
            load_teachers_list();
        });

        $('#course').change(function () {
            set_param('course', $(this).val());
            load_groups_list();
            load_teachers_list();
        });

        $('#CodGrup').change(function () {
            set_param('group', $(this).val());
            load_teachers_list();
            Load_busy_table();
            Load_plan_table();
            Load_rooms_table();
            Load_time_table();
        });

        $('#CodPrep').change(function () {
            set_param('teacher', $(this).val());
            load_groups_list();
            Load_busy_table();
            Load_plan_table();
            Load_rooms_table();
            Load_time_table();
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
            });
        }

        function Load_plan_table() {
            $.get('/?action=plan_table', function (data) {
                $('#PlanTable').html(data);
            });
        }

        if ($('#shedule_panel').length) {
            Load_busy_table();
            Load_plan_table();
            Load_rooms_table();
            Load_time_table();
            load_groups_list();
            load_teachers_list();
        }
    }
);