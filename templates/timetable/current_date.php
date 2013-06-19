<?php
/**
 * Информация о текущей дате
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 12:35
 *
 * @var View $this
 * @var string $mode
 * @var string $data
 * @var int $week
 * @var int $month
 */
$this->screen(View::TT_HEADER, array('mode' => $mode));?>
    <div class="span6">
        <form method="get" class="">
            <table class="table">
                <tr>
                    <td>
                        <label for="month">Месяц</label>
                    </td>
                    <td>
                        <label for="week">Неделя</label>
                    </td>
                    <td>
                        <a href="/?action=current_date" class="btn">
                            Сегодня
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="action" value="current_date">
                        <input name="month" type="number" value="<?= $month ?>" class="input-small" id="month">
                    </td>
                    <td>
                        <input name="week" type="number" value="<?= $week ?>" class="input-small" id="week">
                    </td>
                    <td><input type="submit" value="Обновить" class="btn btn-primary"></td>
                </tr>
                <tr>
                </tr>
            </table>
        </form>
    </div>
    <div class="clearfix"></div>
    <pre><?=$data?></pre>
<? $this->screen(View::TT_FOOTER);