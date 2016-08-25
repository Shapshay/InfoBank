<div class="content-box-content">
<form method="post">
<fieldset>
<p>
<label>Выберите раздел</label>
<select name="ch" class="small-input">
{SORT_PAGE_ROWS}
</select>
</p>
<p><input type="Submit" value="Выбрать" name="change_ch" class="button"></p>
</fieldset>
</form>
<hr>
<h2>Сортировка</h2>

{SORT_MENU}
<form id="changeOrder" method="post" action="modules/sort/changeorder.php">
<fieldset>
<p><input type="Submit" value="Сохранить" name="edt_page" class="button"></p>
</fieldset>
</form>
</div>
