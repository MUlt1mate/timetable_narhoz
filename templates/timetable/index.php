<?php
/**
 * Список групп и преподавателей
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 1:10
 *
 * @var View $this
 * @var array $teachers
 * @var array $forms_study
 * @var array $groups_all
 */
$this->screen(View::TT_HEADER); ?>
    <div class="row-fluid">
        <div class="span10 offset1" style="margin-top:-20px;">
            <ul id="FormStudyTab" class="nav nav-tabs">
                <li><a href="#cf0" data-toggle="tab" class="page-tab">Очное</a></li>
                <li><a href="#cf1" data-toggle="tab" class="page-tab">Заочное</a></li>
                <li><a href="#cf3" data-toggle="tab" class="page-tab">Сокращенное</a></li>
                <li class="active"><a href="#prep" data-toggle="tab" class="page-tab">Преподаватели</a></li>
            </ul>
            <div id="FormStudyContent" class="tab-content">

                <?
                foreach ($forms_study as $fs_id):?>
                    <div class="tab-pane fade in" id="cf<?= $fs_id ?>">
                        <div class="row-fluid">
                            <div class="span8" style="min-width: 350px;">
                                <? if (0 != $fs_id): ?>
                                    <div class="alert">
                                        <a href="http://narhoz-chita.ru/students/rasp" id="timetable_main_site">Расписание
                                            находится на основном
                                            сайте </a>
                                    </div>
                                <? endif ?>
                                <table class="table table-condensed">
                                    <tr>
                                        <? for ($year = 1; $year <= 5; $year++) : ?>
                                            <th><? echo (isset($group_years[$fs_id][$year])) ? 'Курс ' . $year : '' ?></th>
                                        <? endfor; ?>
                                    </tr>
                                    <? if (isset($groups_all[$fs_id]) && is_array($groups_all[$fs_id]))
                                        foreach ($groups_all[$fs_id] as $y_groups) :?>
                                            <tr>
                                                <? for ($year = 1; $year <= 5; $year++) : ?>
                                                    <td>
                                                        <ul class="unstyled">
                                                            <? if (isset($y_groups[$year]) && is_array($y_groups[$year]))
                                                                foreach ($y_groups[$year] as $g):?>
                                                                    <li>
                                                                        <a <? echo ($g['count'] == 0)
                                                                            ? 'class="muted" title="нет расписания"'
                                                                            : 'href="/?group=' . $g['codgrup'] . '"';; ?>>
                                                                            <?= $g['namegrup'] ?>
                                                                        </a>
                                                                    </li>
                                                                <? endforeach; ?>
                                                        </ul>
                                                    </td>
                                                <? endfor; ?>
                                            </tr>
                                        <? endforeach; ?>
                                </table>
                            </div>
                            <div class="span4">
                                <? if (isset($announce[$fs_id]) && is_array($announce[$fs_id])): ?>
                                    <table class="table table-bordered">
                                        <? foreach ($announce[$fs_id] as $a): ?>
                                            <tr>
                                                <th><?= $a->name ?>:</th>
                                                <td><?= TimeDate::db_to_screen($a->value) ?></td>
                                            </tr>
                                        <? endforeach; ?>
                                    </table>
                                <? endif; ?>
                                <? if (0 == $fs_id): ?>
                                    <a href="http://raspisaniye-vuzov.ru/" target="_blank" id="vuzov_app">
                                        <div class="alert alert-info">
                                            <div>
                                                <img src="/img/android_app_icon.png" id="android_app_icon">
                                            </div>
                                            <div id="android_app_text">
                                                Приложение для устройств<br> на Android и iOS
                                            </div>
                                        </div>
                                    </a>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                <? endforeach ?>
                <div class="tab-pane fade in active" id="prep">
                    <div class="row-fluid">
                        <div class="span8">
                            <? if (is_array($teachers))
                                foreach ($teachers as $word => $teacher_word):?>
                                    <section id="<?= Text::translate($word) ?>"></section>

                                    <h4><?= $word ?></h4>
                                    <ul class="inline">
                                        <? foreach ($teacher_word as $teacher):
                                            /**
                                             * @var Teachers $teacher
                                             */
                                            ?>
                                            <li style="width: 150px;">
                                                <a <? echo ($teacher->count == 0)
                                                    ? 'class="muted" title="нет расписания"'
                                                    : 'href="/?teacher=' . $teacher->id . '"';; ?>>
                                                    <?= $teacher->shortfio ?>
                                                </a>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                <? endforeach; ?>
                        </div>

                        <div class="span4">
                            <div class="affix">
                                <div class="container">
                                    <div class="span4  teachers-list">
                                        <ul class="nav nav-list inline">
                                            <li class="nav-header">Алфавитный указатель</li>
                                            <br/>
                                            <? foreach ($teachers as $word => $teacher_word): ?>
                                                <li class="alphabetic_list"><a
                                                        href="#<?= Text::translate($word) ?>"><?= $word ?></a></li>
                                            <? endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span8">
                    <div class="alert alert-info">
                        Группы, выделенные серым цветом, не содержат ни одного добавленного занятия.
                    </div>
                    <div class="alert alert-error" id="disclaimer">
                        <button id="disclaimer_close" type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4 class="alert-heading">Внимание!</h4>
                        Система находится в режиме рабочего тестирования. Предоставленная информация может не
                        соответствовать
                        действительности.
                    </div>
                    <div class="alert alert-block alert-info" id="disclaimer_info" style="display: none">
                        Данное сообщение больше не появится. Пользуйтесь расписанием на свой риск =)
                    </div>
                </div>
            </div>
        </div>
    </div>
<? $this->screen(View::TT_FOOTER);