<?php
/**
 * @author: MUlt1mate
 * Date: 19.06.13
 * Time: 10:13
 * @var View $this
 */
$title = 'Настройки';
$this->screen(View::A_HEADER, array('title' => $title));?>
    <h3><?=$title?></h3>
    <h4>Обновить файлы для экспорта</h4>
    Обновлять после публикации расписания, после изменения расписания.<br>
    <a href="#" class="btn btn-primary" id="ical_refresh" data-loading-text="Обновление...">
        Обновить iCal файлы
    </a>
    <div id="ical_info"></div>

    <h4>Создать файл JSON для сторонних приложений</h4>
    <a href="#" class="btn btn-primary disabled">
        Создать JSON файл
    </a>

    <h4>Сменить пароль</h4>
    <form method="post" action="">
        <input type="text" name="pass" placeholder="Введите новый пароль" required="required"><br>
        <input type="submit" disabled="disabled" class="btn btn-primary disabled" value="Сменить">
    </form>

    <script>
        $(function () {
            $('#ical_refresh').click(function () {
                $(this).button('loading').attr('disabled', 'disabled');
                $.get('/?action=ical_refresh', function (data) {
                    $('#ical_info').html(data);
                    $('#ical_refresh').button('reset').attr('disabled', '');
                });
                return false;
            })
        });
    </script>
<? $this->screen(View::A_FOOTER);
