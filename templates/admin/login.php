<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 20:51
 *
 * @var View $this
 */
$this->screen(View::A_HEADER);?>
    <div class="row-fluid">
        <div class="span4 offset4">
            <div style="padding-top:40%;text-align: center; ">
                <img src="/img/security.png">

                <h2>Защита паролем</h2>

                <form method="post" class="form-inline">
                    <input type="password" required="required" autofocus="autofocus" name="password"
                           placeholder="Введите пароль">
                    <input type="submit" value="Вход" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
<? $this->screen(View::A_FOOTER);