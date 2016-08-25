<!-- Start Content Box -->
<div class="content-box-content">
	<form method="post" enctype="multipart/form-data" name="s_s">
		<fieldset>
		<p>
		<label>Кому:</label>
			<select name="from_id" id="from_id" class="small-input">
				{ITEM_ROWS}
			</select>
		</p>
		<p>
		<label>Тема</label>
		<input class="text-input medium-input" type="text" id="theme" name="theme" value="" />
		</p>
		<p>
			<label>Текст сообщения</label>
			<textarea name="content" id="content" rows="10" cols="80">
				  </textarea>
		</p>


		<input  type="hidden" id="item_id" name="item_id" value="0"/>

		<p><input type="Submit" value="Отправить" name="edt_s_s" class="button"></p>

		</fieldset>

		<div class="clear"></div><!-- End .clear -->

	</form>
</div> <!-- End .content-box-content -->