<tr>
	<td>{F_NAME}</td>
	<td class="grey">
	<input type="text" value="{F_NAME}" name="ff_name[{F_NUM}]" class="text-input medium-input" />
	<input type="hidden" name="f_name[{F_NUM}]" value="{F_NAME}" />
	</td>
	<td class="grey">
	<select name="f_type[{F_NUM}]" class="small-input">
	<option value="text">Текст</option>
	<option value="textarea">Статья</option>
	<option value="date">Дата</option>
	<option value="file">Файл (картинка)</option>
	<option value="checkbox">Логическое</option>
	</select>
	</td>
	<td class="red">
	<input type="checkbox" name="f_add[{F_NUM}]" checked="checked" class="mc" value="{F_NUM}" />
	</td>
</tr>