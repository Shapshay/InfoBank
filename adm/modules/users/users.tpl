<!-- Start Content Box -->

<div class="content-box-header">
	
	<h3>Пользователи</h3>
	
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
				   <th>Имя</th>
					<th>Логин</th>
					<th>Стартовая страница</th>
					<th>Внутренний номер</th>
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
					<label>Офис</label>
					<select name="office_id" id="office_id" class="small-input">
						{USER_OFFICE_SEL}
					</select>
				</p>
				<p>
					<input type="checkbox" name="prod" id="prod" value="1" /> <label>Учитывать в статистике как продажника</label>
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
			<p>
				<label>Код 1С</label>
				<input class="text-input medium-input" type="text" id="login_1C" name="login_1C" value="" />
			</p>
			<p>
				<label>Внутренний номер</label>
				<input class="text-input medium-input" type="text" id="phone" name="phone" value="" />
			</p>
			<p>
			<label>Стартовая страница</label>
			<select name="page_id" id="page_id" class="small-input">
			{U_PAGE_PAGE_ROWS}
			</select>
			</p>
										
			<input  type="hidden" id="item_id" name="item_id" value="0"/>
			
			<p><input type="Submit" value="Сохранить" name="edt_s_s" class="button"></p>
				
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->
			
		</form>
		
		<hr>
		<h3>Роли пользователя</h3>
		<form method="post" enctype="multipart/form-data" name="s_s">
			<fieldset>
			{USER_ROLES_ROWS}
										
			<input  type="hidden" id="item2_id" name="item2_id" value="0"/>
			<input  type="hidden" id="u_r" name="u_r" value="1"/>
			
			<p><input type="Submit" value="Сохранить" name="edt_s_s" class="button"></p>
				
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->
			
		</form>
		
		
		
	</div> <!-- End #tab2 -->        
</div> <!-- End .content-box-content -->