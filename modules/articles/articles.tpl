<!-- Start Content Box -->

<div class="content-box-header">
	
	<h3>Статьи</h3>
	
	<ul class="content-box-tabs">
		<li><a href="#tab1" class="default-tab">Список</a></li> <!-- href must be unique and match the id of target div -->
		<li><a href="#tab2" id="tab2_link">Форма</a></li>
	</ul>
	
	<div class="clear"></div>
	
</div> <!-- End .content-box-header -->
<div class="content-box-content">
	
	<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
		<p><input type="button" value="Добавить" class="button" onclick="addVal();"></p>
		<table id="stat_table" class="display">
			
			<thead>
				<tr>
					<th>ID</th>
					<th>Дата</th>
					<th>Раздел</th>
					<th>Заголовок</th>
					<th>Операции</th>
				</tr>
			</thead>
			<tbody>
				{ITEM_ROWS}
			</tbody>
			
		</table>
		<p><input type="button" value="Добавить" class="button" onclick="addVal();"></p>
	</div> <!-- End #tab1 -->
	
	<div class="tab-content" id="tab2">
	
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
			<input class="text-input medium-input" type="text" id="title" name="title" value="" />
			</p>
			<p>
				<label>Статья</label>
				<textarea name="content" id="content">
					  </textarea>
			</p>
			</div>
				<button type="button" class="acord_btn" onclick="ShowQuest();">ВОПРОСЫ</button>
				<div id="Questions">
				<div id="QuestDiv">

				</div>


				<hr size="1" width="80%" align="left">

				<input  type="hidden" id="item_id" name="item_id" value="0"/>
				<p><input type="button" value="Добавить вопрос" class="button" onclick="addQuestion();"></p>
				</div>
				<hr size="1" width="80%" align="left">
			<!--<p><input type="Submit" value="Сохранить" name="edt_s_s" class="button"></p>-->
				<p><input type="button" value="Сохранить" name="edt_s_s" class="button" onclick="SubmitForm();"></p>
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->
			
		</form>
	</div> <!-- End #tab2 -->        
</div> <!-- End .content-box-content -->