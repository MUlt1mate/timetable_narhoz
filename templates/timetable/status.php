<?php
/**
 * Вывод статуса и ошибок
 * @author: MUlt1mate
 * Date: 28.03.13
 * Time: 1:02
 *
 * @var View $this
 * @var string $error
 */
$this->screen(View::TT_HEADER);?>
    <div class="row-fluid">
        <div class="span6 offset3">
            <div class="alert alert-danger">
                <h4>Ошибка!</h4>
                <?
                switch ($error) {
                    case Timetable_Controller::ERROR_IE6:
                    case Timetable_Controller::ERROR_IE7:
                        ?>
                        Ваш браузер устарел и не поддерживается данным сайтом.<br/>
                        <a href="http://www.google.ru/intl/ru/chrome/browser/">
                            Скачайте Google Chrome - быстрый бесплатный браузер
                        </a>
                        <?
                        break;
                    default:
                        ?>
                            Неизвестная ошибка.
                        <?
                }?>
            </div>
        </div>
    </div>
<? $this->screen(View::TT_FOOTER);