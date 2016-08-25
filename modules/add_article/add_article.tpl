<!-- Start Content Box -->

<div class="content-box-content">

	<form method="post" enctype="multipart/form-data" name="s_s" id="ArtForm">
		<fieldset>
			<button type="button" class="acord_btn" onclick="ShowArt();">СТАТЬЯ</button>
			<div id="ArtDiv">

				<p>
					<label>Раздел</label>
					<select name="ch_id" id="ch_id" class="small-input">
						{ART_CH}
					</select>
				</p>
				<p>
					<label>Дата</label>
					<input class="text-input medium-input" type="text" id="date" name="date" value="{DATE_NOW}"  readonly="readonly" />
				</p>
				<p>
					<label>Заголовок</label>
					<input class="text-input medium-input" type="text" id="title" name="title" value="{ITEM_TITLE}" />
				</p>
				<p>
					<label>Статья</label>
				<textarea name="content" id="content">{ITEM_CONT}
					  </textarea>
				</p>
			</div>
			<button type="button" class="acord_btn" onclick="ShowQuest();">ВОПРОСЫ</button>
			<div id="Questions">
				<div id="QuestDiv">
					{ITEM_Q_DIV}
				</div>


				<hr size="1" width="80%" align="left">

				<input  type="hidden" id="item_id" name="item_id" value="{ITEM_ID}"/>
				<p><input type="button" value="Добавить вопрос" class="button" onclick="addQuestion();"></p>
			</div>
			<hr size="1" width="80%" align="left">
			<!--<p><input type="Submit" value="Сохранить" name="edt_s_s" class="button"></p>-->
			<p><input type="button" value="Сохранить" name="edt_s_s" class="button" onclick="SubmitForm();"></p>
		</fieldset>

		<div class="clear"></div><!-- End .clear -->

	</form>
</div> <!-- End .content-box-content -->