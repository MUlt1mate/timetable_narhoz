<?php
/**
 * Класс для экспорта расписания в формат iCal
 * @author: MUlt1mate
 * Date: 23.03.13
 * Time: 14:39
 */

class iCal_Generator
{
    const ICAL_DIRECTORY_PATH = '../timetable/';
    const ICAL_DIRECTORY = 'ical';

    const DOT_ICS = '.ics';

    /**
     * Создаёт файл формата .ics
     * @param array $timetable
     * @param array $remove
     * @param string $cal_name
     * @param string $cal_title
     * @param bool $for_group
     * @param bool $rewrite true, если принудительная перезапись
     * @return bool
     */
    static public function iCalGener($timetable, $remove, $cal_name, $cal_title, $for_group, $rewrite = false)
    {
        echo 'http://' . $_SERVER['SERVER_NAME'] . '/' . self::ICAL_DIRECTORY . '/' . $cal_name . self::DOT_ICS;
        if ($rewrite OR !file_exists(self::ICAL_DIRECTORY_PATH . self::ICAL_DIRECTORY . '/' . $cal_name . self::DOT_ICS)) {
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
                    $e->setProperty('description', $event->grupflowname);

                $e->setProperty('location', $event->room);
                $e->setProperty('summary', $event->iCalSummary($for_group));
                $e->setProperty('uid', $event->iCalUID());
            }

            $cal->setConfig('FILENAME', $cal_name . self::DOT_ICS);
            $cal->setConfig('directory', self::ICAL_DIRECTORY_PATH . self::ICAL_DIRECTORY);

            //вывод на экран
            //echo  $cal->createCalendar();

            //сохранение на сайт
            return $cal->saveCalendar();

            //сохранение пользователю
            //$cal->returnCalendar();
        }
        return true;
    }
}