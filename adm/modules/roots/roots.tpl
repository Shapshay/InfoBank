<!-- Start Content Box -->

<div class="content-box-header">
	
	<h3>Администраторы</h3>
	
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
				   <th>Дата регистрации</th>
									<th>Имя</th>
									<th>Логин</th>
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
										<label>Дата регистрации</label>
										<input class="text-input medium-input" type="text" id="reg_date" name="reg_date" value="{DATE_NOW}" />
										</p>
										<p>
										<label>Имя</label>
										<input class="text-input medium-input" type="text" id="name" name="name" value="" />
										</p>
										<p>
										<label>Логин</label>
										<input class="text-input medium-input" type="text" id="login" name="login" value="" />
										</p>
										<p>
										<label>Пароль</label>
										<input class="text-input medium-input" type="text" id="password" name="password" value="" />
										</p>
										
			<input  type="hidden" id="item_id" name="item_id" value="0"/>
			
			<p><input type="Submit" value="Сохранить" name="edt_s_s" class="button"></p>
				
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->
			
		</form>
	</div> <!-- End #tab2 -->        
</div> <!-- End .content-box-content -->