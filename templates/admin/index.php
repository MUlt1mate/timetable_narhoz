<?php
/**
 * @author: MUlt1mate
 * Date: 18.06.13
 * Time: 0:20
 *
 * @var View $this
 * @var array $forms_study
 * @var array $groups_all
 * @var array $teachers
 */
$this->screen('header');?>
    <script type="text/javascript" src="/js_admin/navigation.js"></script>
    <div class="row-fluid" style="margin-top: 30px;">
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
                    <div class="tab-pane fade in" id="cf<?= $fs_id ?>">
                        <div class="row-fluid">
                            <div class="span8" style="min-width: 350px;">
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
                                                                        <a href="/?action=timetable&group=<?= $g['codgrup'] ?>" <?
                                                                            if ($g['count'] == 0) echo 'class="muted" title="нет расписания"';
                                                                            ?>>
                                                                            <?=$g['namegrup']?> (<?=$g['count']?>)
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
                                                <a href="/?action=timetable&teacher=<?= $teacher->id ?>"<?
                                                    if ($teacher->count == 0) echo 'class="muted" title="нет расписания"';
                                                    ?>>
                                                    <?=$teacher->shortfio?> (<?=$teacher->count?>)
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
        </div>
    </div>
<? $this->screen('footer');