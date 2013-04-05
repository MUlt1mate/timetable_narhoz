<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:36
 */
$title = 'Время занятий';
$this->screen(self::A_HEADER, array('title' => $title));?>
    <div class="span4">
        <h3><?=$title?></h3>
        <table class="table table-bordered table-condensed">
            <tr>
                <th>Начало</th>
                <th>Конец</th>
                <th>Минуты</th>
                <th>Часы</th>
            </tr>
            <? if (is_array($times))
                foreach ($times as $t):?>
                    <tr>
                        <td><?=TimeDate::db_timedate_to_screen_time($t->time_begin)?></td>
                        <td><?=TimeDate::db_timedate_to_screen_time($t->time_end)?></td>
                        <td><?=$t->duration?></td>
                        <td><?=$t->hours?></td>
                    </tr>
                <? endforeach;?>
        </table>
    </div>
    <div class="span4">
        <h3>Добавить:</h3>

        <form class="form-horizontal" method="post">
            <div class="control-group">
                <label class="control-label" for="time_begin">Дата начала</label>

                <div class="controls">
                    <input type="time" class="input-small" required="required" id="time_begin" name="time_begin">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="time_end">Дата окончания</label>

                <div class="controls">
                    <input type="time" class="input-small" required="required" id="time_end" name="time_end">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="hours">Количество акад. часов</label>

                <div class="controls">
                    <input type="number" class="input-small" required="required" id="hours" name="hours">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" value="Добавить" class="btn btn-primary">
                </div>
            </div>
        </form>


    </div>
<? $this->screen(self::A_FOOTER);