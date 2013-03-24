<?php
/**
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 2:35
 */
?>
<div class="modal" id="ExportModal" style="display:none;">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3>Экспорт расписания</h3>
    </div>
    <div class="modal-body">
        <p style="font-weight: bold;">Расписание может быть экспортировано, как для всей группы, так и для подгрупп.</p>

        <p>Ссылка на расписание</p>
        <input class="input span5 focused" id="ExportLinkInput" type="text" value=""/>
        <ul id="ExportList" class="nav nav-tabs">
            <li class="active"><a href="#ex1" data-toggle="tab">Google Calendar</a></li>
            <li><a href="#ex2" data-toggle="tab">Яндекс</a></li>
            <li><a href="#ex3" data-toggle="tab">Outlook</a></li>
        </ul>
        <div id="ExportListContent" class="tab-content">
            <div class="tab-pane fade in active" id="ex1">
                <h3>Экспорт в Google Calendar</h3>
                <ol>
                    <li>Откройте <a href="http://www.google.com/calendar" target="_blank">Google Calendar</a></li>
                    <li>Авторизуйтесь</li>
                    <li>Откройте настройки</li>
                    <li>Календари</li>
                    <li>Просмотреть интересные календари</li>
                    <li>Добавить по URL</li>
                    <li>Вставьте адрес календаря</li>
                </ol>
            </div>
            <div class="tab-pane fade" id="ex2">
                <h3>Экспорт в Яндекс.Календарь</h3>
                <ol>
                    <li>Откройте <a href="https://calendar.yandex.ru/" target="_blank">Яндекс.Календарь</a></li>
                    <li>Авторизуйтесь</li>
                    <li>В левой панели нажмите "импорт"</li>
                    <li>Вставьте адрес календаря</li>
                </ol>
            </div>
            <div class="tab-pane fade" id="ex3">
                <h3>Экспорт в Microsoft Outlook</h3>
                <ol>
                    <li>Откройте Microsoft Outlook</li>
                    <li>В левой панели нажмите "календарь"</li>
                    <li>В верхней панели нажмите "Открыть календарь"</li>
                    <li>Выберите "Из интернета"</li>
                    <li>Вставьте адрес календаря</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-primary" data-dismiss="modal">Закрыть</a>
    </div>
</div>