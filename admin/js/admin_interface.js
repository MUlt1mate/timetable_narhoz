$(function () {
    $('.shedule').click(function () {
        window.location = '/?action=edit&shedule_id=' + $(this).attr('id');
    });

    $('.shedule_edit').click(function () {
//        window.location='/?action=edit&shedule_id='+$(this).attr('id');
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

    Load_busy_table();
    Load_plan_table();
    Load_rooms_table();
    Load_time_table();
    load_groups_list();
    load_teachers_list();
});