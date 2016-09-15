<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:36
 *
 * @var View $this
 * @var array $lessons
 */
$title = 'Текущие';
$this->screen('header', array('title' => $title));?>
    <div class="row-fluid">
        <div class="span12">
            <?if (is_array($lessons))
                foreach ($lessons as $cod_f => $faculty) {
                    echo '<h3>' . Lists::$faculty[$cod_f] . '</h3>';
                    foreach ($faculty as $num_course => $course) {
                        echo '<h4>Курс ' . $num_course . ':</h4>';
                        foreach ($course as $lesson) {
                            $this->screen('lesson_current', array(
                                'lesson' => $lesson,
                            ));
                        }
                    }
                }
            ?>
        </div>
    </div>
<? $this->screen('footer');