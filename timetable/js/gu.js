(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-11597546-6', 'narhoz-chita.ru');
ga('send', 'pageview');

$(function () {
    $('.page-tab,.page-button').click(function () {
        ga('send', 'pageview', {
            'page': '/'+$(this).attr('href'),
            'title': $(this).text()
        });
    });

    $('#vuzov_app').click(function(){
        ga('send', 'event', 'ext_link', 'Приложение Расписание вузов');
    });

    $('#timetable_main_site').click(function(){
        ga('send', 'event', 'ext_link', 'Расписание осн. сайта');
    });

    $('#disclaimer_close').click(function () {
        ga('send', 'event', 'page_action', 'disclaimer close');
    });

    $('#GoRight').click(function () {
        ga('send', 'event', 'page_action', 'next period');
    });

    $('#GoLeft').click(function () {
        ga('send', 'event', 'page_action', 'prev period');
    });

    $('#LoadCurrent').click(function () {
        ga('send', 'event', 'page_action', 'current period');
    });
});