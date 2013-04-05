<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 20:22
 */
$title = 'Расписания';
$this->screen(self::A_HEADER, array('title' => $title));?>
    <div class="row-fluid">
        <div class="span8">
            <h3><?=$title?></h3>
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Название</th>
                    <th>Статус</th>
                    <th>Тип</th>
                    <th>Форма обучения</th>
                    <th>Дата начала</th>
                    <th>Дата конца</th>
                    <th></th>
                </tr>
                <? if (is_array($shedules))
                    foreach ($shedules as $s):
                        switch ($s->status_id) {
                            case SheduleStatus::STATUS_PUBLIC:
                                $tr_class = 'success';
                                break;
                            case SheduleStatus::STATUS_EDIT:
                                $tr_class = 'info';
                                break;
                            case SheduleStatus::STATUS_RETIRED:
                                $tr_class = '';
                                break;
                        }
                        ?>
                        <tr class="<?= $tr_class ?>">
                            <td><a href="/?action=edit&tt_id=<?= $s->id ?>"><?=$s->name?></a></td>
                            <td><?=$s->status?></td>
                            <td><?=$s->type?></td>
                            <td><?=$s->formstudy?></td>
                            <td><?=TimeDate::db_to_screen($s->date_begin)?></td>
                            <td><?=TimeDate::db_to_screen($s->date_end)?></td>
                            <td><label class="icon-edit"></label></td>
                        </tr>
                    <? endforeach;?>
            </table>
        </div>

        <div class="span4">
            <h3>Новое расписание</h3>

            <form method="POST">
                <table class="table table-condensed">
                    <tr>
                        <td>Название</td>
                        <td>
                            <input type="text" name="name" required="required" autofocus="autofocus">
                        </td>
                    </tr>
                    <tr>
                        <td>Статус</td>
                        <td>
                            <select name="status">
                                <? foreach ($shedule_status as $status): ?>
                                    <option value="<?= $status->id ?>"><?=$status->name?></option>
                                <? endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Тип</td>
                        <td>
                            <select name="type">
                                <? foreach ($shedule_types as $type): ?>
                                    <option value="<?= $type->id ?>"><?=$type->name?></option>
                                <? endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Форма обучения</td>
                        <td>
                            <select name="formstudy">
                                <? foreach ($form_study as $form): ?>
                                    <option value="<?= $form->codformstudy ?>"><?=$form->formstudy?></option>
                                <? endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Год</td>
                        <td>
                            <input type="number" name="year" value="<?= $study_year ?>" min="2010">
                        </td>
                    </tr>
                    <tr>
                        <td>Семестр</td>
                        <td>
                            <select name="numterm">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Дата начала</td>
                        <td>
                            <input class="inputDate span6" type="date" name="date_begin" id="date_begin"
                                   required="required">
                        </td>
                    </tr>
                    <tr>
                        <td>Дата окончания</td>
                        <td>
                            <input class="inputDate span6" type="date" name="date_end" id="date_end"
                                   required="required">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="btn" type="reset" value="Сброс">
                        </td>
                        <td>
                            <input type="hidden" name="id"/>
                            <input class="btn btn-primary" type="submit" value="Добавить">
                        </td>
                    </tr>
                </table>
            </form>
        </div>

    </div>
<? $this->screen(self::A_FOOTER);