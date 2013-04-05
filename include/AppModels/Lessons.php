<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:38
 */

class Lessons extends ActiveRecord\Model
{
    const MIN_COLOR = 150;
    const MAX_COLOR = 250;

    static $table = 'subs';
    static $primary_key = 'codsub';

    /**
     * Перевод rgb в hex
     * @param int $r red
     * @param int $g green
     * @param int $b blue
     * @return string hex
     */
    static private function  rgb2hex($r, $g, $b)
    {
        $r = dechex($r < 0 ? 0 : ($r > 255 ? 255 : $r));
        $g = dechex($g < 0 ? 0 : ($g > 255 ? 255 : $g));
        $b = dechex($b < 0 ? 0 : ($b > 255 ? 255 : $b));

        $color = (strlen($r) < 2 ? '0' : '') . $r;
        $color .= (strlen($g) < 2 ? '0' : '') . $g;
        $color .= (strlen($b) < 2 ? '0' : '') . $b;
        return $color;
    }

    /**
     * меняет цвета для всех предметов
     */
    static public function change_all_colors()
    {
        $lessons = self::all();
        foreach ($lessons as $l) {
            $l->color = self::rgb2hex(
                rand(self::MIN_COLOR, self::MAX_COLOR),
                rand(self::MIN_COLOR, self::MAX_COLOR),
                rand(self::MIN_COLOR, self::MAX_COLOR)
            );
            $l->readonly(false);
            $l->save();
        }
    }
}