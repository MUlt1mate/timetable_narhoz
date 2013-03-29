<?php
/**
 * Список групп и преподавателей
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 1:10
 */
$this->screen('header');?>
    <div class="row-fluid">
        <div class="span10 offset1" style="margin-top:-20px;">
            <ul id="FormStudyTab" class="nav nav-tabs">
                <li><a href="#cf0" data-toggle="tab">Очное</a></li>
                <li><a href="#cf1" data-toggle="tab">Заочное</a></li>
                <li><a href="#cf3" data-toggle="tab">Сокращенное</a></li>
                <li class="active"><a href="#prep" data-toggle="tab">Преподаватели</a></li>
            </ul>
            <div id="FormStudyContent" class="tab-content">

                <?
                foreach ($forms_study as $fs_id):?>
                    <div class="tab-pane fade in<? if ($fs_id == 30) echo ' active'; ?>" id="cf<?= $fs_id ?>">
                        <div class="row-fluid">
                            <div class="span8">
                                <table class="table table-condensed">
                                    <tr>
                                        <? for ($year = 1; $year <= 6; $year++) : ?>
                                            <th><?echo (isset($group_years[$fs_id][$year])) ? 'Курс ' . $year : ''  ?></th>
                                        <? endfor;?>
                                    </tr>
                                    <? if (is_array($groups_all[$fs_id]))
                                        foreach ($groups_all[$fs_id] as $y_groups) :?>
                                            <tr>
                                                <? for ($year = 1; $year <= 6; $year++) : ?>
                                                    <td>
                                                        <ul class="unstyled">
                                                            <? if (isset($y_groups[$year]) && is_array($y_groups[$year]))
                                                                foreach ($y_groups[$year] as $g):?>
                                                                    <li>
                                                                        <a href="/?group=<?= $g['codgrup'] ?>" <?if ($g['count'] == 0) echo 'class="muted"';?>>
                                                                            <?=$g['namegrup']?>
                                                                        </a>
                                                                    </li>
                                                                <? endforeach;?>
                                                        </ul>
                                                    </td>
                                                <? endfor;?>
                                            </tr>
                                        <? endforeach;?>
                                </table>
                            </div>
                            <?if (isset($announce[$fs_id]) && is_array($announce[$fs_id])): ?>
                                <div class="span4">
                                    <table class="table table-bordered">
                                        <?foreach ($announce[$fs_id] as $a): ?>
                                            <tr>
                                                <th><?=$a->name?>:</th>
                                                <td><?=TimeDate::db_to_screen($a->value)?></td>
                                            </tr>
                                        <? endforeach;?>
                                    </table>
                                </div>
                            <? endif;?>
                        </div>
                    </div>
                <? endforeach?>
                <div class="tab-pane fade in active" id="prep">
                    <div class="row-fluid">
                        <div class="span8">
                            <?if (is_array($teachers))
                                foreach ($teachers as $word => $teacher_word):?>
                                    <section id="<?= Text::translate($word) ?>"></section>

                                    <h4><?=$word?></h4>
                                    <ul class="inline">
                                        <?foreach ($teacher_word as $teacher): ?>
                                            <li style="width: 150px;">
                                                <a href="/?teacher=<?= $teacher->id ?>" <?if ($teacher->count == 0) echo 'class="muted"';?>>
                                                    <?=$teacher->shortfio?>
                                                </a>
                                            </li>
                                        <? endforeach;?>
                                    </ul>
                                <? endforeach;?>
                        </div>

                        <div class="span4">
                            <div class="affix">
                                <div class="container">
                                    <div class="span4  teachers-list">
                                        <ul class="nav nav-list inline">
                                            <li class="nav-header">Алфавитный указатель</li>
                                            <br/>
                                            <?foreach ($teachers as $word => $teacher_word): ?>
                                                <li class="alphabetic_list"><a
                                                        href="#<?= Text::translate($word) ?>"><?=$word?></a></li>
                                            <? endforeach;?>
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
<? $this->screen('footer');