<?php
/**
 * Сетка расаписания
 * @author: MUlt1mate
 * Date: 18.03.13
 * Time: 2:41
 *
 * @var View $this
 * @var string $mode
 * @var string $body_title
 * @var string $start_date
 * @var string $finish_date
 * @var int $week
 * @var int $month
 * @var bool $show_subgroup
 */
$this->screen('header', array('mode' => $mode, 'title' => $title)); ?>
    <div class="content">
        <div class="control">
            <div class="intop"><p class="title"><?= $body_title ?></p></div>
            <? if ($show_subgroup): ?>
                <div class="intop" style="padding: 5px 0 0 0;">
                    <div class="btn-group">
                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="" id="SubgroupSelector">
                            <span id="subgroup_name">Вся группа</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a sub_id="0" class="set_subgroup">Вся группа</a></li>
                            <li><a sub_id="1" class="set_subgroup">Подгруппа 1</a></li>
                            <li><a sub_id="2" class="set_subgroup">Подгруппа 2</a></li>
                            <li><a sub_id="3" class="set_subgroup">Подгруппа 3</a></li>
                        </ul>
                    </div>
                </div>
            <? endif; ?>
            <div class="intop" style="float:right;">
                <table>
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button id="GoLeft" class="btn btn-primary" style="width:50px;" href="#MainTable">
                                    <i class="icon-arrow-left icon-white"></i>
                                </button>
                                <button id="GoRight" class="btn btn-primary" style="width:50px;" href="#MainTable">
                                    <i class="icon-arrow-right icon-white"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-primary" id="LoadCurrent">Сегодня</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="intop" style="float:right;">
                <p class="title" id="date_interval"><?= $start_date ?> - <?= $finish_date ?></p>
            </div>
        </div>
        <input type="hidden" id="week_id" value="<?= $week ?>">
        <input type="hidden" id="month_id" value="<?= $month ?>">

        <div id="MainTable" class="carousel slide" data-interval="0">
            <div class="carousel-inner" id="grid_carousel">
                <div class="item active" original>
                    <div id="grid0" interval="0"></div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div id="footer">
            <div class="pull-left">
                <a class="btn page-button" data-toggle="modal" href="#about">О программе</a>
            </div>
            <div class="pull-right" id="export">
                <a class="btn btn-primary page-button" data-toggle="modal" href="#ExportModal"
                   id="GetExport">Экспорт</a>
            </div>
        </div>
        <? $this->screen('export_modal') ?>
        <? $this->screen('about') ?>
    </div>
<? $this->screen('footer');