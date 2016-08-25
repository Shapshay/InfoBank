<div class="content-box-content">
<form method="post" enctype="multipart/form-data" name="s_s">
<fieldset>
<p>
<label>Название страницы</label>
<input class="text-input medium-input" type="text" id="title" name="title" />
</p>
<p>
<label>Краткое описание</label>
<input class="text-input medium-input" type="text" id="description" name="description" />
</p>
<p>
<label>Страница дизайна</label>
<select name="stemplate" class="small-input">
{TPL_ROWS}
</select>
</p>
<p>
<label>Модуль отображения</label>
<select name="content" class="small-input">
{CONTENT_ROWS}
</select>
</p>

<p><input type="Submit" value="Сохранить" name="add_page" class="button"></p>
</fieldset>
</form>


</div>
