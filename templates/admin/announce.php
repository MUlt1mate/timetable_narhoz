<?php
/**
 * @author: MUlt1mate
 * Date: 16.06.13
 * Time: 23:43
 *
 * @var View $this
 */
$title = 'Ближайшие события';
$this->screen(View::A_HEADER, array('title' => $title));
$this->screen(View::A_TABLE_HEADER);
?>
    <h3><?=$title?></h3>
    <div class="span6">
        <table class="table table-bordered table-condensed">
            <tr>
                <th>Событие</th>
                <th>Дата</th>
                <th></th>
            </tr>
            <?
            foreach (FormStudy::$all as $cod => $form_study) {
                ?>
                <tr>
                    <th colspan="3"><?=$form_study?></th>
                </tr>
                <?
                if (isset($dates[$cod]))
                    foreach ($dates[$cod] as $d) :?>
                        <tr>
                            <td><?=$d->name?></td>
                            <td><?=TimeDate::db_to_screen($d->value)?></td>
                            <td><a href="/?action=announce&delete_item=<?= $d->id ?>"><label
                                        class="icon-remove"></label></a></td>
                        </tr>

                    <? endforeach;
            }
            ?>
        </table>
    </div>
    <div class="span6">
        <form method="post" action="">
            <input type="text" class="input-large" name="event" placeholder="Событие" required="required"
                   value="<? if (isset($_POST['event'])) echo $_POST['event'] ?>">
            <br>
            <input type="date" class="input-medium" name="date" required="required"
                   value="<? if (isset($_POST['date'])) echo $_POST['date'] ?>">
            <br>
            <select class="input-medium" name="form_study" required="required">
                <?foreach (FormStudy::$all as $cod => $form_study) : ?>
                    <option value="<?= $cod ?>"<?
                        if (isset($_POST['form_study']) && $_POST['form_study'] == $cod) echo ' selected';
                        ?>>
                        <?=$form_study?>
                    </option>
                <? endforeach;?>
            </select>
            <br>
            <input type="submit" class="btn btn-primary inline" value="Добавить">
        </form>
    </div>
<? $this->screen(View::A_FOOTER);