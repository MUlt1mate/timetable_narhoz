<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?echo (isset($title)) ? $title . ' - ' : ''; echo  'Электронное расписание ЧИ БГУЭП'?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/custom.css"/>
    <link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="Электронное расписание ЧИ БГУЭП"/>
    <meta name="Keywords" content="чи бгуэп, чита, нархоз, расписание, электронное"/>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/navigation.js"></script>
</head>
<body data-spy="scroll" data-target=".teachers-list" data-offset="85">
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <div class="intop"><a href="/"><img src="/img/favicon.png" alt="Главная"></a></div>
            <a class="brand" href="/">Электронное расписание ЧИ БГУЭП</a>
            <? if (isset($mode)): ?>
                <div class="intop" style="position: fixed; right: 5px;">
                    <div class="btn-group" data-toggle="buttons-radio" style="margin-top:2px; padding: 0;">
                        <button mode="week"
                                class="mode_change btn btn-success <? if ($mode == 'week') echo 'active'; ?>">
                            <i class="icon-th-large icon-white"></i> Неделя
                        </button>
                        <button mode="month"
                                class="mode_change btn btn-success <? if ($mode == 'month') echo 'active'; ?>">
                            <i class="icon-calendar icon-white"></i> Месяц
                        </button>
                        <button mode="agenda"
                                class="mode_change btn btn-success <? if ($mode == 'agenda') echo 'active'; ?>">
                            <i class="icon-th-list icon-white"></i> Ближайшие
                        </button>
                    </div>
                </div>
            <? endif;?>
        </div>
    </div>
</div>
