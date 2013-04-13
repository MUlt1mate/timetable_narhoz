<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 20:22
 */
$menu = array(
    'lessons' => 'Предметы',
    'times' => 'Время',
    'rooms' => 'Аудитории',
    'teachers' => 'Преподаватели',
    'current' => 'Текущие',
    'timetable' => 'Расписание',
    'exit' => 'Выход');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?echo (isset($title)) ? $title . ' - ' : ''; echo  'АРМ Расписание'?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/custom.css"/>
    <link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/admin_interface.js"></script>
</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <a class="brand" style="padding:4px 20px 0 40px;" href="/">
            <img src="/img/favicon.png" alt="Главная">
        </a>
        <a class="brand" href="/">АРМ Расписание</a>
        <ul class="nav">
            <li<?if ($_SERVER['REQUEST_URI'] == '/') echo ' class="active"'?>>
                <a href="/">Расписания</a>
            </li>
            <? foreach ($menu as $link => $title): ?>
                <li<?if (isset($_GET['action']) && ($_GET['action'] == $link)) echo ' class="active"'?>>
                    <a href="/?action=<?= $link ?>">
                        <?=$title?>
                    </a>
                </li>
            <? endforeach;?>
        </ul>
    </div>
</div>
