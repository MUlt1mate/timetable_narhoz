<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 20:22
 */
$menu = array(
    'rooms' => 'Аудитории',
    'teachers' => 'Преподаватели',
    'current' => 'Текущие',
    'timetable' => 'Расписание',
    'settings' => 'Настройки',
    'exit' => 'Выход');

$sub_menu = array(
    'announce' => 'Ближайшие события',
    'times' => 'Время занятий',
    'lessons' => 'Предметы',
);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?echo (isset($title)) ? $title . ' - ' : ''; echo  'АРМ Расписание'?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/custom.css"/>
    <link href="/js/components/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet" media="screen">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="/js/components/jquery/jquery.min.js"></script>
    <script src="/js/components/jquery.cookie/jquery.cookie.js"></script>
    <script src="/js/components/bootstrap/docs/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js_admin/admin_interface.js"></script>
    <script type="text/javascript" src="/js_admin/gu_admin.js"></script>
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
            <li class="dropdown<?
            if (isset($_GET['action']) && array_key_exists($_GET['action'], $sub_menu)) echo ' active';
            ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Таблицы <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <? foreach ($sub_menu as $link => $title): ?>
                        <li<?if (isset($_GET['action']) && ($_GET['action'] == $link)) echo ' class="active"'?>>
                            <a href="/?action=<?= $link ?>">
                                <?=$title?>
                            </a>
                        </li>
                    <? endforeach;?>
                </ul>
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
