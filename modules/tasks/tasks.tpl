<!-- Start Content Box -->

<div class="content-box-header">
	
	<h3>Задачи</h3>
	
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
				   <th>Название задачи</th>
					<th>Время на прохождение (мин)</th>
									
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
	
		<form method="post" enctype="multipart/form-data" name="s_s">
			<fieldset>
			<p>
			<label>Название задачи</label>
			<input class="text-input medium-input" type="text" id="title" name="title" value="" />
			</p>
			<p>
			<label>Время на прохождение (мин)</label>
			<input class="text-input medium-input" type="text" id="time_on_task" name="time_on_task" value="" />
			</p>

				<hr size="1" width="80%" align="left">
				<h2>Формирование задачи</h2>
				<p>
					<label>Раздел статей</label>
					<select name="ch_id" id="ch_id" class="small-input" onchange="ArtListChoise(this.value);">
						<option value="0">Все</option>
						{ART_CH}
					</select>
				</p>
				<p>
				<span><b>Список статей</b></span>
				<span style="margin-left: 530px;"><b>Статьи в задаче</b></span>
				<div class="task_panel">
					<section id="tasks" style="margin:15px 0 0 0; ">
						{ART_SEL}
					</section>
					<section id="tasks2" style="margin:15px 0 0 15px; ">

					</section>
				</div>
				<button type="button" onclick="choiseBtn();" class="button">Добавить в задачу</button>
				<button type="button" onclick="unchoiseBtn();" class="button" style="margin-left: 500px;">Удалить из задачи</button>
				</p>
				<div class="selection hide"></div>
										
			<input  type="hidden" id="item_id" name="item_id" value="0"/>
			
			<p style="margin-top: 40px;"><input type="button" onclick="saveVal();" value="Сохранить" name="edt_s_s" class="button"></p>
				
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->
			
		</form>
	</div> <!-- End #tab2 -->        
</div> <!-- End .content-box-content -->