<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 12:04
 *
 * @var View $this
 */
$this->screen(View::TT_HEADER);?>
    <div class="row-fluid">
        <div class="span8 offset2">
            <div class="alert alert-danger">
                <h3>Страница не найдена</h3>
                Запрошенная вами страница не найдена, либо выбранное расписание не заполнено.<br>
                <a href="/">Вернуться на главную</a>
            </div>
        </div>
    </div>
<? $this->screen(View::TT_FOOTER);
