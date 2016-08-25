<style>
#jstree_check_role ul{
	list-style-type: square !important;
}
</style>
<script>
$(function () {
  $("#jstree_check_role").jstree({
    "checkbox" : {
      "keep_selected_style" : false,
      "real_checkboxes": true,
      "real_checkboxes_names": function (n) {
                 var nid = 0;
                 $(n).each(function (data) {
                    nid = $(this).attr("nodeid");
                 });
                 return (["check_" + nid, nid]);
              },
        "two_state": true
    },
    "plugins" : [ "checkbox" ],
   });
   
   $('#jstree_check_role').jstree('open_all');
   
});
 function SaveForm() {   
	var checked_ids = $('#jstree_check_role').jstree(true).get_selected();
	var ch_id;
	for(var i =0; i<checked_ids.length;i++){
		ch_id = $('#'+checked_ids[i]).find('a').attr("href").replace('system.php?menu=', '');
		$('#formRolePage').append('<input type="hidden" name="r_p[]" value="'+ch_id+'">');
		//alert(ch_id);
	}
	$('#formRolePage').submit();
	
  }
</script>
<div class="content-box-header">
	
	<h3>Настройка прав доступа для ролей пользователей</h3>
	
	<div class="clear"></div>
	
</div> <!-- End .content-box-header -->
<div class="content-box-content">
<form id="formRoleSel" method="post" enctype="multipart/form-data" name="s_s">
<fieldset>
<p>
<label>Роль</label>
<select name="role_id" class="small-input">
{ROLE_ROWS}
</select>
<input type="submit" value="Выбрать" name="s_role" class="button">
</p>
</fieldset>
</form>
<form id="formRolePage" method="post" enctype="multipart/form-data" name="s_s">
<fieldset>
<input type="hidden" name="inp_role_id" id="inp_role_id" value="{TREE_ROLE_ID}"/>
<p><div id="jstree_check_role">{ROLE_PAGES}</div></p>
<p><input type="button" value="Сохранить" name="save_role_page" class="button" onclick="SaveForm();"></p>
</fieldset>
</form>
</div> <!-- End .content-box-content -->