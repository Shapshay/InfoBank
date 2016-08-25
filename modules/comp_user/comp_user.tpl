<div class="content-box-header">

	<h3>Пользователи</h3>

	<ul class="content-box-tabs">
		<li><a href="#tab1" class="default-tab">Список</a></li> <!-- href must be unique and match the id of target div -->
		<li><a href="#tab2" id="tab2_link">Форма</a></li>
	</ul>

	<div class="clear"></div>

</div> <!-- End .content-box-header -->
<!-- Start Content Box -->
<div class="content-box-content">
    <div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
		<table id="stat_table" class="display">

			<thead>
			<tr>
				<th>ID</th>
				<th>Имя</th>
				<th>Логин</th>
				<th>Редактировать</th>
			</tr>
			</thead>
			<tbody id="u_table">
			{USER_ROWS}
			</tbody>

		</table>
		<div class="clear"></div><!-- End .clear -->
    </div> <!-- End #tab1 -->

    <div class="tab-content" id="tab2">

        <form method="post" enctype="multipart/form-data" name="s_s">
            <fieldset>
                <p>
                    <label>Имя</label>
                    <span id="name"></span>
                </p>
                <p>
                    <label>Логин</label>
                    <span id="login"></span>
                </p>
                <p>
                    <label>Комплексы</label>
                    <div id="UserComplex"></div>
                </p>

                <input  type="hidden" id="item_id" name="item_id" value="0"/>

                <p><input type="button" value="Сохранить" name="edt_s_s" class="button" onclick="saveComplex();"></p>

            </fieldset>

            <div class="clear"></div><!-- End .clear -->

        </form>


    </div> <!-- End #tab2 -->
</div> <!-- End .content-box-content -->