<!-- Start Content Box -->

<div class="content-box-header">
	
	<h3>Комплексы задач</h3>
	
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
				   <th>Название комплекса задач</th>
									<th>Время начала доступа</th>
									<th>Время окончания доступа</th>
									
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
			<label>Название комплекса задач</label>
			<input class="text-input medium-input" type="text" id="title" name="title" value="" />
			</p>
			<p>
			<input type="checkbox" id="block" name="block" value="1" /> <label>Блокирует получение клиентов в Бенто</label>
			</p>
			<p>
			<label>Время начала доступа</label>
			<input class="text-input small-input" type="text" id="dostup_start" name="dostup_start" value="{DATE_NOW}"  readonly="readonly" />
			</p>
			<p>
			<label>Время окончания доступа</label>
			<input class="text-input small-input" type="text" id="dostup_end" name="dostup_end" value="{DATE_NOW}"  readonly="readonly" />
			</p>

				<hr size="1" width="80%" align="left">
				<h2>Формирование комплекса задач</h2>
				<p>
					<span><b>Список задач</b></span>
					<span style="margin-left: 530px;"><b>Задачи в комплексе</b></span>
				<div class="task_panel">
					<section id="tasks" style="margin:15px 0 0 0; ">
						{TASKS_SEL}
					</section>
					<section id="tasks2" style="margin:15px 0 0 15px; ">

					</section>
				</div>
				<button type="button" onclick="choiseBtn();" class="button">Добавить задачу</button>
				<button type="button" onclick="showPause();" class="button">Добавить паузу</button>
				<button type="button" onclick="unchoiseBtn();" class="button" style="margin-left: 390px;">Удалить задачу</button>
				</p>
				<div class="selection hide"></div>
										
			<input  type="hidden" id="item_id" name="item_id" value="0"/>
			
			<p><input type="button" onclick="saveVal();" value="Сохранить" name="edt_s_s" class="button"></p>
				
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->
			
		</form>

		<div id="ComplexPauseDiv" style="display: none;">
			<div class="box-modal" id="pauseModal">
				<div class="box-modal_close arcticmodal-close">закрыть</div>
			<form>
			<h3>Создание паузы между задачами</h3>
			<p>
				<label>Срок паузы в днях</label>
				<input class="text-input small-input" type="text" id="task_pause" name="task_pause" value="0" />
			</p>
			<p><input type="button" onclick="addPause();" value="Создать" class="button"></p>
			</form>
			</div>
		</div>
	</div> <!-- End #tab2 -->        
</div> <!-- End .content-box-content -->

