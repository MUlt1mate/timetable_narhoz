<?php
/**
 * @author: MUlt1mate
 * Date: 06.04.13
 * Time: 19:19
 *
 * @var View $this
 * @var array $faculty
 * @var array $params
 * @var array $types_plan_work
 * @var array $groups
 * @var array $teachers
 * @var array $lessons
 * @var array $types_lessons
 * @var array $rooms
 * @var array $times
 */
$this->screen('header');
?>
    <div class="top2" id="shedule_panel">
        <form name="planwork" class="form-inline top2form">
            <table>
                <tr>
                    <td class="param_list">
                        <select class="span3" id="faculty">
                            <option></option>
                            <?if (is_array($faculty))
                                foreach ($faculty as $f_cod => $f_name):?>
                                    <option<?if ($params[Shedule_params::PARAM_FACULTY] == $f_cod) echo ' selected';?>
                                        value="<?= $f_cod ?>">
                                        <?= $f_name ?>
                                    </option>
                                <? endforeach;?>
                        </select>
                    </td>

                    <td class="param_list">
                        <select class="span3" id="TypePlanWork">
                            <option></option>
                            <?if (is_array($types_plan_work))
                                foreach ($types_plan_work as $cod_pw => $name_pw):?>
                                    <option<?if ($params[Shedule_params::PARAM_PLAN_WORK] == $cod_pw) echo ' selected';?>
                                        value="<?= $cod_pw ?>">
                                        <?= $name_pw ?>
                                    </option>
                                <? endforeach;?>
                        </select>
                    </td>

                    <td class="param_list">
                        <select class="span1" id="course">
                            <option></option>
                            <? for ($course = 1; $course <= 6; $course++): ?>
                                <option<?if ($params[Shedule_params::PARAM_COURSE] == $course) echo ' selected';?>>
                                    <?= $course ?>
                                </option>
                            <? endfor;?>
                        </select>
                    </td>

                    <td class="param_list">
                        <select class="span2" id="CodGrup"></select>
                    </td>

                    <td class="param_list">
                        <select class="span3" id="CodPrep"></select>
                    </td>

                    <td class="hours_label">
                        Факт: <span id="timetable_hours_label">0</span>
                    </td>
                    <td class="hours_label">
                        План: <span id="planwork_hours_label">0</span>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div id="TimeTable"></div>
    <div id="PlanTable"></div>
    <div id="BusyTable"></div>
    <div id="RoomTable"></div>
    <div class="navbar navbar-fixed-bottom">
        <div class="navbar-inner">
            <div class="container" style="width:100%;">
                <table id="shed_grid_add" class="table-condensed" style="margin:auto;">
                    <tr id="labels">
                        <td>
                            <div id="CodGrup_name"
                                 style="max-height:34px; overflow:hidden; max-width:400px; padding:4px 5px;">
                                Группа
                            </div>
                        </td>
                        <td id="CodPrep_name">Преподаватель</td>
                        <td id="CodSubs_name">Предмет</td>
                        <td id="TypeLesson_name">Занятие</td>
                        <td id="CodRoom_name">Аудитория</td>
                        <td id="CodTime_begin_name">Время</td>
                    </tr>
                    <tr id="selects" style="display: none">
                        <td>
                            <select id="CodGrup_edit" class="span2" style="margin: 5px 0 0;">
                                <option></option>
                                <?if (is_array($groups))
                                    foreach ($groups as $g):?>
                                        <option value="<?= $g['codgrup'] ?>"><?=$g['namegrup']?></option>
                                    <? endforeach; ?>
                            </select>
                            <input type="hidden" name="is_flow" id="is_flow">
                        </td>


                        <td>
                            <select id="CodPrep_edit" class="span2" style="margin: 5px 0 0;">
                                <option></option>
                                <?if (is_array($teachers))
                                    foreach ($teachers as $t):?>
                                        <option value="<?= $t->id ?>"><?=$t->fio?></option>
                                    <? endforeach; ?>
                            </select>
                        </td>


                        <td>
                            <select id="CodSubs_edit" class="span2"
                                    style="margin: 5px 0 0;">
                                <option></option>
                                <?if (is_array($lessons))
                                    foreach ($lessons as $l):?>
                                        <option value="<?= $l->codsub ?>"><?=$l->namesub?></option>
                                    <? endforeach; ?>
                            </select>
                        </td>


                        <td>
                            <select id="TypeLesson_edit" class="span2"
                                    style="margin: 5px 0 0;">
                                <option></option>
                                <?if (is_array($types_lessons))
                                    foreach ($types_lessons as $tl_cod => $tl_name):?>
                                        <option value="<?= $tl_cod ?>"><?=$tl_name?></option>
                                    <? endforeach; ?>
                            </select>
                        </td>


                        <td>
                            <select id="CodRoom_edit" class="span2"
                                    style="margin: 5px 0 0;">
                                <option></option>
                                <?if (is_array($rooms))
                                    foreach ($rooms as $r):?>
                                        <option value="<?= $r->codroom ?>">
                                            <?=Rooms::$build_aliases[$r->numbuilding] . $r->number?>
                                            (<?=$r->placecount?>)
                                        </option>
                                    <? endforeach; ?>
                            </select>
                        </td>


                        <td>
                            <select id="CodTime_begin_edit" class="span2"
                                    style="margin: 5px 0 0;">
                                <option></option>
                                <?if (is_array($times))
                                    foreach ($times as $t):?>
                                        <option
                                            value="<?= $t->id ?>"
                                            time_value="<?=TimeDate::db_timedate_to_screen_time($t->time_begin) . ' - ' .
                                                TimeDate::db_timedate_to_screen_time($t->time_end)?>">
                                            <?=TimeDate::db_timedate_to_screen_time($t->time_begin)?> -
                                            <?=TimeDate::db_timedate_to_screen_time($t->time_end)?>
                                        </option>
                                    <? endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <table style="margin:auto;" class="table-condensed">
                    <tr>
                        <td id="date_begin_picker" style="">
                            <input style="margin:5px 0 0;" class="inputDate span2" id="lesson_date_begin_edit"
                                   type="date">
                        </td>
                        <td style="width: 32px;">
                            <div>
                                <span id="copy_left_date"><label class="icon-arrow-right"></label></span>
                                <span id="copy_right_date"><label class="icon-arrow-left"></label></span>
                            </div>
                            <div>
                                <span id="remove_left_date"><label class="icon-remove"></label></span>
                                <span id="remove_right_date"><label class="icon-remove"></label></span>
                            </div>
                        </td>
                        <td id="date_end_picker">
                            <input style="margin:5px 0 0;" class="inputDate span2" id="lesson_date_end_edit"
                                   type="date">
                        </td>
                        <td>
                            <div id="subgroup_button_edit" class="btn-group" data-toggle="buttons-radio">
                                <button id="subgroup0" class="subgroup btn btn-success active" value="0">
                                    Группа
                                </button>
                                <button id="subgroup1" class="subgroup btn btn-primary" value="1">
                                    П 1
                                </button>
                                <button id="subgroup2" class="subgroup btn btn-primary" value="2">
                                    П 2
                                </button>
								<button id="subgroup3" class="subgroup btn btn-primary" value="3">
                                    П 3
                                </button>
                            </div>
                        </td>
                        <td>
                            <div id="week_button_edit" class="btn-group" data-toggle="buttons-radio">
                                <button id="week0" class="week btn btn-danger active" value="0">
                                    Обе недели
                                </button>
                                <button id="week1" class="week btn btn-warning" value="1">
                                    Верх.
                                </button>
                                <button id="week2" class="week btn btn-warning" value="2">
                                    Нижн.
                                </button>
                            </div>
                        </td>
                        <td>
                            <div id="weekday_button_edit" class="btn-group" data-toggle="buttons-radio">
                                <button id="weekday1" class="btn btn-info" value="1">Пн</button>
                                <button id="weekday2" class="btn btn-info" value="2">Вт</button>
                                <button id="weekday3" class="btn btn-info" value="3">Ср</button>
                                <button id="weekday4" class="btn btn-info" value="4">Чт</button>
                                <button id="weekday5" class="btn btn-info" value="5">Пт</button>
                                <button id="weekday6" class="btn btn-info" value="6">Сб</button>
                                <button id="weekday7" class="btn btn-info" value="7">Вс</button>
                            </div>
                        </td>
                        <td>
                            <button class="btn" id="edit_button" type="button">
                                <i class="icon-pencil"></i>
                            </button>
                        </td>
                        <td>
                            <input type="hidden" name="subgroup" id="subgroup">
                            <input type="hidden" name="weekday_id" id="weekday_id">
                            <input type="hidden" name="week" id="week">
                            <input type="hidden" name="timegrid_id" id="timegrid_id">
                            <button class="btn btn-primary" id="button_add" type="button">
                                <i class="icon-plus icon-white"></i> Добавить
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="modal" id="DeleteModal" style="display: none; ">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>

            <h3>Удаление</h3>
        </div>
        <div class="modal-body">
            <p>Вы действительно хотите удалить занятие?</p>
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn btn-danger" id="delete_button">
                <i class="icon-trash icon-white"></i> Удалить</a>
            <a href="#" data-dismiss="modal" class="btn">Отмена</a>
        </div>
    </div>
<? $this->screen('footer');