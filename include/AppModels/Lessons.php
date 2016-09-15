<?php

/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:38
 *
 * @property string $color
 * @property string $namesub
 * @property string $shortnamesub
 */
class Lessons extends ActiveRecord\Model
{
    const MIN_COLOR = 150;
    const MAX_COLOR = 250;
    public static $table = 'subs';
    public static $primary_key = 'codsub';

    /**
     * Перевод rgb в hex
     * @param int $r red
     * @param int $g green
     * @param int $b blue
     * @return string hex
     */
    private static function rgb2hex($r, $g, $b)
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
    public static function change_all_colors()
    {
        $lessons = self::all();
        foreach ($lessons as $l) {
            $l->change_color(self::rgb2hex(
                rand(self::MIN_COLOR, self::MAX_COLOR),
                rand(self::MIN_COLOR, self::MAX_COLOR),
                rand(self::MIN_COLOR, self::MAX_COLOR)
            ));
        }
    }

    /**
     * Устанавливает выбранный цвет для предмета
     * @param string $color
     */
    private function change_color($color)
    {
        $this->color = $color;
        $this->readonly(false);
        $this->save();
    }
}
