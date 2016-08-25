<!-- Start Content Box -->

<div class="content-box-header">
	
	<h3>Строковые константы</h3>
	
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
				   <th>Метка</th>
				   <th>Надпись</th>
				   <th>Операции</th>
				</tr>
			</thead>
			<tbody>
				{VAR_ROWS}
			</tbody>
			
		</table>
		<p><input type="button" value="Добавить" class="button" onclick="addVal();"></p>
	</div> <!-- End #tab1 -->
	
	<div class="tab-content" id="tab2">
	
		<form method="post" enctype="multipart/form-data" name="s_s">
			<fieldset>
			<p>
			<label>Метка</label>
			<input class="text-input medium-input" type="text" id="name" name="name" value="" />
			</p>
			<p>
			<label>Надпись</label>
			<input class="text-input medium-input" type="text" id="val" name="val" value='' />
			</p>
			<input  type="hidden" id="val_id" name="val_id" value="0"/>
			
			<p><input type="Submit" value="Сохранить" name="edt_s_s" class="button"></p>
				
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->
			
		</form>
		
	</div> <!-- End #tab2 -->        
	
</div> <!-- End .content-box-content -->