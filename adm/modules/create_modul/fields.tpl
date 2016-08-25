<div class="content-box-content">
<form method="post">
<fieldset>
<p>
<label>Название модуля</label>
<input class="text-input medium-input" type="text" id="modul" name="modul" value="{F_TABLE}" />
<br /><small>Только латинские буквы от 'a' до 'z' и символ '_'</small>
<input type="hidden" name="f_table" value="{F_TABLE}"> 
</p>
<p>
<label>Описание модуля</label>
<input class="text-input medium-input" type="text" id="title" name="title" value="{F_TABLE}" />
<br /><small>Название модуля в настройках страницы</small>
</p>
<hr>
<h3>Выберите поля и их типы для форм модуля</h3>
<p>
<table class="features-table" id="carsTable">
	<thead>
		<tr>
			<th>Название поля</th>
			<th class="grey">Заголовок в форме</th>
			<th class="grey">Тип поля ввода</th>
			<th class="red">
				Включить в форму<br />
				<input type="checkbox" name="maincheck" id="maincheck" checked="checked"/>
			</th>
		</tr>
	</thead>
	<tbody>
		{TABLE_ROWS}
	</tbody>
</table>
</p>

<input type="hidden" name="action" value="sel_rows" />
<p><input type="Submit" value="Создать" name="sel_rows" class="button"></p>
</fieldset>
</form>
</div>
