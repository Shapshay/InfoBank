<!-- Start Content Box -->

<div class="content-box-header">

	<h3>Сообщения</h3>

	<ul class="content-box-tabs">
		<li><a href="#tab1" class="default-tab">Список</a></li> <!-- href must be unique and match the id of target div -->
		<li><a href="#tab2" id="tab2_link">Сообщение</a></li>
	</ul>

	<div class="clear"></div>

</div> <!-- End .content-box-header -->
<div class="content-box-content">

	<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
		<table id="stat_table" class="display">

			<thead>
			<tr>
				<th>ID</th>
				<th>Дата</th>
				<th>Отправитель</th>
				<th>Тема</th>
				<th>Операции</th>
			</tr>
			</thead>
			<tbody>
			{ITEM_ROWS}
			</tbody>

		</table>
	</div> <!-- End #tab1 -->

	<div class="tab-content" id="tab2">

		<form method="post" enctype="multipart/form-data" name="s_s">
			<fieldset>
				<p>
					<label>Дата:</label>
					<span id="date"></span>
				</p>
				<p>
					<label>Отправитель:</label>
					<span id="sender_id"></span>
				</p>
				<p>
					<label>Тема:</label>
					<span id="theme"></span>
				</p>
				<hr size="1" width="80%">
				<p>
					<label>Содержимое:</label>

				<div id="content"></div>
				</p>

				<input  type="hidden" id="item_id" name="item_id" value="0"/>



			</fieldset>

			<div class="clear"></div><!-- End .clear -->

		</form>
	</div> <!-- End #tab2 -->
</div> <!-- End .content-box-content -->