<!-- Start Content Box -->
<div class="content-box-content">
	<form method="post" enctype="multipart/form-data" name="s_s">
		<fieldset>
			<p>
				<label>Имя</label>
				<span id="name">{EDT_NAME}</span>
			</p>
			<p>
				<label>Логин</label>
				<span id="login">{EDT_LOGIN}</span>
			</p>
			<p>
				<label>Набранный опыт</label>
				<span id="xp">{EDT_XP} XP</span>
			</p>
			<input  type="hidden" id="item_id" name="item_id" value="{ITEM_ID}"/>


		</fieldset>

		<p>
			<div style="width: 300px;">
			<label>Аватарка</label>
			{R_AV}<br />
		<form method="post" enctype="multipart/form-data" name="s_s">
		<p>
		<div class="file_upload">
			<button type="button">Выбрать</button>
			<div>Файл фото не выбран</div>
			<input type="file" name="av">
		</div>
		<p><button type="submit" class="button" name="edt_av">Загрузить фото</button></p>
		</form>
		</div>
	<script>
		$(function(){
			var wrapper = $( ".file_upload" ),
					inp = wrapper.find( "input" ),
					btn = wrapper.find( "button" ),
					lbl = wrapper.find( "div" );

			btn.focus(function(){
				inp.focus()
			});
			// Crutches for the :focus style:
			inp.focus(function(){
				wrapper.addClass( "focus" );
			}).blur(function(){
				wrapper.removeClass( "focus" );
			});

			var file_api = ( window.File && window.FileReader && window.FileList && window.Blob ) ? true : false;

			inp.change(function(){
				var file_name;
				if( file_api && inp[ 0 ].files[ 0 ] ) {
					file_name = inp[0].files[0].name;
				}
				else {
					//file_name = inp.val().replace("C:\\fakepath\\", '');
					file_name = inp.val();
				}
				if( !file_name.length )
					return;

				if( lbl.is( ":visible" ) ){
					lbl.text( file_name );
					btn.text( "Выбрать" );
				}else
					btn.text( file_name );
			}).change();

		});
		$(window).resize(function(){
			$('.file_upload input').triggerHandler("change");
		});
	</script>
			</p>



		<div class="clear"></div><!-- End .clear -->

	</form>
</div> <!-- End .content-box-content -->