<?php
/**
 * Класс для экспорта расписания в формат iCal
 * @author: MUlt1mate
 * Date: 23.03.13
 * Time: 14:39
 */
require_once('iCalcreator.class.php');

class iCal_Generator
{
    const ICAL_DIRECTORY = '/ical';
    //@todo пакетное обновление ical
    //@todo ical подгруппы
    //@todo ical воскресенье

    static public function iCalGener($timetable, $remove, $cal_name, $cal_title, $for_group)
    {
        $cal = new vcalendar(array('unique_id' => 'narhoz_timetable_' . $cal_name));
        $cal->setProperty("x-wr-calname", $cal_title . " - Расписание занятий");
        $cal->setProperty("X-WR-TIMEZONE", TimeDate::TIMEZONE);

        foreach ($timetable as $row) {
            $event = new Lesson($row);
            $e = & $cal->newComponent('vevent');
            //если есть удаления, создаем параметр
            if (isset($remove[$row['id']]) && is_array($remove[$row['id']])) {
                $dates = array();
                foreach ($remove[$row['id']] as $r)
                    $dates[] = $event->iCalDateBegin($r);
                $e->setProperty("exdate", $dates);
            }
            $e->setProperty('dtstart', $event->iCalDateBegin());
            $e->setProperty('rrule', array("FREQ" => "WEEKLY",
                "UNTIL" => $event->iCalDate($event->date_end),
                "INTERVAL" => $event->iCalInterval(),
                "BYDAY" => $event->iCalByDay()));
            $e->setProperty('duration', array("min" => $event->duration));
            if ($for_group)
                $e->setProperty('description', $event->teacher);
            else
                $e->setProperty('description', $event->GrupFlowName);

            $e->setProperty('location', $event->room);
            $e->setProperty('summary', $event->iCalSummary($for_group));
            $e->setProperty('uid', $event->iCalUID());
        }

        $cal->setConfig('FILENAME', $cal_name . '.ics');
        $cal->setConfig('directory', self::ICAL_DIRECTORY);

        //вывод на экран
//        echo  $cal->createCalendar();
        echo $_SERVER['SERVER_NAME'] . self::ICAL_DIRECTORY . '/' . $cal_name . '.ics';
        //сохранение на сайт
        return $cal->saveCalendar();

        //сохранение пользователю
        //$cal->returnCalendar();
    }
}