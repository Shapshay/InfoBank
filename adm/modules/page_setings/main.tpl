<div class="content-box-content">
<form method="post" enctype="multipart/form-data" name="s_s">
<fieldset>
<p>
<input type="checkbox" name="view" value="1" {VIEW_CHECK} /> <label>Отображать страницу на сайте</label>
</p>
<p>
<input type="checkbox" name="start" value="1" {START_CHECK} /> <label>Сделать страницу стартовой</label>
</p>
<p>
<label>Тип содержимого</label>
<select name="type" class="small-input">
{TABLE_ROWS}
</select>
</p>
<p>
<label>Название страницы</label>
<input class="text-input medium-input" type="text" id="title" name="title" value="{EDT_TITLE}" />
</p>
<p>
<label>Краткое описание</label>
<input class="text-input medium-input" type="text" id="description" name="description" value="{EDT_DESC}" />
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
<p><input type="Submit" value="Сохранить" name="edt_page" class="button"></p>
</fieldset>
</form>
</div>
