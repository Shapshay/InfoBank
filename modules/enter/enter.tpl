
<div id="login-content">

    <form method="post">

        <div class="notification error png_bg"{USR_NOTIF}>
        <div>
            Пароль или логин некорректны !!!
        </div>
</div>

<p>
    <label>Логин</label>
    <input class="text-input" type="text" name="lgn" value="{USR_LOGIN}" />
</p>
<div class="clear"></div>
<p>
    <label>Пароль</label>
    <input class="text-input" type="password" name="psw" value="{USR_PASSW}" />
</p>
<div class="clear"></div>
<p id="remember-password">
    <input type="checkbox" name="member" {USR_SAVE} />Запомнить меня
</p>
<div class="clear"></div>
<p>
    <input class="button" type="submit"  name="send" value="Вход" />
</p>

</form>
</div> <!-- End #login-content -->