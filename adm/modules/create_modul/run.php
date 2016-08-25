<?php
	# SETTINGS #############################################################################
	$moduleName = "create_modul";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "fields" => $prefix . "fields.tpl",
			$moduleName . "t_row" => $prefix . "t_row.tpl",
			$moduleName . "result" => $prefix . "result.tpl",
	));
	# MAIN #################################################################################
	error_reporting (E_ALL);
	ini_set("display_errors", 1);
	if(isset($_POST['action'])){
		switch($_POST['action']){
			// выбор полей таблицы
			case 'sel_table':
				$tpl->parse("META_LINK", ".".$moduleName."html");
				$i = 1;
				$rows = $dbc->db_free_query("SHOW COLUMNS FROM ".$_POST['t_name']);
				foreach($rows as $row2){
					if($row2[0]!='id'){
						$tpl->assign("F_NAME", $row2[0]);
						$tpl->assign("F_NUM", $i);
						$tpl->assign("F_TABLE", $_POST['t_name']);
						
						$tpl->parse("TABLE_ROWS", ".".$moduleName."t_row");
						$i++;
					}
				}
				
				$tpl->parse(strtoupper($moduleName), ".".$moduleName."fields");
			break;
			// создание папки и файлов модуля
			case 'sel_rows':
				$tpl->parse("META_LINK", ".".$moduleName."html");
				
				$modulename = SuperSaveGETStr($_POST['modul']);

				if (file_exists('modules/'.$modulename)) {
					$tpl->assign("M_RESULT_ERR", "Модуль '".$modulename."' уже существует !");
					$tpl->assign("ERR_DISPLAY", '');
					$tpl->assign("OK_DISPLAY", ' style="display: none;"');
					$tpl->assign("M_RESULT_OK", '');
					
				} else {
					if($modulename==''){
						$tpl->assign("M_RESULT_ERR", "Название модуля неможет быть пустым !");
						$tpl->assign("ERR_DISPLAY", '');
						$tpl->assign("OK_DISPLAY", ' style="display: none;"');
						$tpl->assign("M_RESULT_OK", '');
					}
					else{
						mkdir('modules/'.$modulename, 0777);
						
						$run = file_get_contents('modules/create_modul/tpl_run.tpl', true);
						$run = str_replace('{MODUL_NAME}',$modulename,$run);
						$run = str_replace('{MODUL_TABLE_NAME}',$_POST['f_table'],$run);
						
						$modul_table_fields = '';
						$modul_edt_fields = '';
						$modul_row_fields1 = '';
						$modul_row_fields2 = '';
						$modul_js_fields1 = '';
						$modul_js_fields2 = '';
						$modul_item_php_fields = '';
						$add_ff_fields = '';
						$edt_ff_fields = '';
						$html_area = '';
						$checkbox_update = '';
						
						foreach($_POST['f_add'] as $num){
							
							if($_POST['f_type'][$num]=='date'){
								$modul_table_fields.= '"'.$_POST['f_name'][$num].'" => date("Y-m-d H:i",strtotime($_POST[\''.$_POST['f_name'][$num].'\'])), 
								';
							}
							elseif($_POST['f_type'][$num]=='checkbox'){
								$checkbox_update.= 'if(isset($_POST[\''.$_POST['f_name'][$num].'\'])){
										$'.$_POST['f_name'][$num].' = 1;
									}
									else{
										$'.$_POST['f_name'][$num].' = 0;
									}
									';
								$modul_table_fields.= '"'.$_POST['f_name'][$num].'" => $'.$_POST['f_name'][$num].', 
								';
							}
							else{
								$modul_table_fields.= '"'.$_POST['f_name'][$num].'" => $_POST[\''.$_POST['f_name'][$num].'\'], 
								';
							}
							
							if($_POST['f_type'][$num]!='textarea'){
								$modul_js_fields2.= '$(\'#'.$_POST['f_name'][$num].'\').val(obj.'.$_POST['f_name'][$num].');
								';
								$modul_js_fields1.= '$(\'#'.$_POST['f_name'][$num].'\').val(\'\');
								';
							}
							else{
								$modul_js_fields2.= 'CKEDITOR.instances[\''.$_POST['f_name'][$num].'\'].setData(obj.'.$_POST['f_name'][$num].');
								';
								$modul_js_fields1.= 'CKEDITOR.instances[\''.$_POST['f_name'][$num].'\'].setData("");
								';
							}
							
							if($_POST['f_type'][$num]!='date'){
								$modul_item_php_fields.= '$out_row[\''.$_POST['f_name'][$num].'\'] = $row[\''.$_POST['f_name'][$num].'\'];
								';
							}
							else{
								$modul_item_php_fields.= '$out_row[\''.$_POST['f_name'][$num].'\'] = date("d-m-Y H:i",strtotime($row[\''.$_POST['f_name'][$num].'\']));
								';
							}
							
							if($_POST['f_type'][$num]!='checkbox'){
								$modul_edt_fields.= '$tpl->assign("EDT_'.strtoupper($_POST['f_name'][$num]).'", $row[\''.$_POST['f_name'][$num].'\']);
									';
							}
							else{
								$modul_edt_fields.= 'if($row[\''.$_POST['f_name'][$num].'\']==1){
											$tpl->assign("'.strtoupper($_POST['f_name'][$num]).'_CHECK", \' checked="checked"\');
										}
										else{
											$tpl->assign("'.strtoupper($_POST['f_name'][$num]).'_CHECK", \'\');
										}
									';
							}
							switch($_POST['f_type'][$num]){
								case 'text':
									$edt_ff_fields.= '<p>
										<label>'.$_POST['ff_name'][$num].'</label>
										<input class="text-input medium-input" type="text" id="'.$_POST['f_name'][$num].'" name="'.$_POST['f_name'][$num].'" value="" />
										</p>
										';
									$modul_row_fields1.= '<td>{EDT_'.strtoupper($_POST['f_name'][$num]).'}</td>
									';
									$modul_row_fields2.= '<th>'.$_POST['ff_name'][$num].'</th>
									';
								break;
								case 'textarea':
									$edt_ff_fields.= '<p>
											<label>'.$_POST['ff_name'][$num].'</label>
											<textarea name="'.$_POST['f_name'][$num].'" id="'.$_POST['f_name'][$num].'" rows="10" cols="80">
									              </textarea>
										</p>
										';
									$html_area.= '<script>
										$(document).ready(function() {
											CKEDITOR.replace( \''.$_POST['f_name'][$num].'\', {
												filebrowserBrowseUrl: \'inc/ckfinder/ckfinder.html\',
												filebrowserUploadUrl: \'inc/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files\'
											} );
										} );
										</script>
										';
								break;
								case 'date':
									$edt_ff_fields.= '<p>
										<label>'.$_POST['ff_name'][$num].'</label>
										<input class="text-input medium-input" type="text" id="'.$_POST['f_name'][$num].'" name="'.$_POST['f_name'][$num].'" value="{DATE_NOW}"  readonly="readonly" />
										</p>
										';
									$html_area.= '<script type="text/javascript">
										  $(function(){
											  $(\'#'.$_POST['f_name'][$num].'\').will_pickdate({ 
												format: \'d-m-Y H:i\', 
												inputOutputFormat: \'d-m-Y H:i\',
												days: [\'Понедельник\', \'Вторник\', \'Среда\', \'Четверг\',\'Пятница\', \'Суббота\', \'Воскресенье\'],
												months: [\'Январь\', \'Февраль\', \'Март\', \'Апрель\', \'Май\', \'Июнь\', \'Июль\', \'Август\', \'Сентябрь\', \'Октябрь\', \'Ноябрь\', \'Декабрь\'],
												timePicker: true,
												timePickerOnly: false,
												militaryTime: true,
												allowEmpty:true ,
												yearsPerPage:10,
												allowEmpty:true
											  });
										  });
										</script>
										';
									$modul_row_fields1.= '<td>{EDT_'.strtoupper($_POST['f_name'][$num]).'}</td>
									';
									$modul_row_fields2.= '<th>'.$_POST['ff_name'][$num].'</th>
									';
								break;
								case 'file':
									$edt_ff_fields.= '<p>
										<label>'.$_POST['ff_name'][$num].'</label>
										<input class="text-input medium-input" type="text" id="'.$_POST['f_name'][$num].'" name="'.$_POST['f_name'][$num].'" value="" />
										<button type="button" onclick="openPopup(\''.$_POST['f_name'][$num].'\')">Выберите файл</button>
										</p>
										';
								break;
								case 'checkbox':
									$edt_ff_fields.= '<p>
										<input type="checkbox" name="'.$_POST['f_name'][$num].'" value="1" /> <label>'.$_POST['ff_name'][$num].'</label>
										</p>
										';
								break;
								default:
									$edt_ff_fields.= '<p>
										<label>'.$_POST['ff_name'][$num].'</label>
										<input class="text-input medium-input" type="text" id="'.$_POST['f_name'][$num].'" name="'.$_POST['f_name'][$num].'" value="" />
										</p>
										';
									$modul_row_fields1.= '<td>{EDT_'.strtoupper($_POST['f_name'][$num]).'}</td>
									';
									$modul_row_fields2.= '<th>'.$_POST['ff_name'][$num].'</th>
									';
								break;
							}
							$add_ff_fields.= '';
						}
						
						
						$modul_table_fields = substr($modul_table_fields, 0, -11);
						$modul_assign_metka = strtoupper($modulename);
						$run = str_replace('{MODUL_TABLE_FIELDS}',$modul_table_fields,$run);
						$run = str_replace('{MODUL_EDT_FIELDS_VALUES}',$modul_edt_fields,$run);
						$run = str_replace('{MODUL_ASSIGN_METKA}',$modul_assign_metka,$run);
						$run = str_replace('{MODUL_CHEKBOX_UPDATE}',$checkbox_update,$run);
						$fp = fopen('modules/'.$modulename.'/run.php', "w");
						fwrite($fp, $run);
						fclose($fp);
						chmod('modules/'.$modulename.'/run.php', 0777);
						
						$info_tpl = file_get_contents('modules/create_modul/tpl_info.tpl', true);
						$info_tpl = str_replace('{MODUL_TABLE_NAME}',$_POST['f_table'],$info_tpl);
						$info_tpl = str_replace('{MODUL_TITLE}',$_POST['title'],$info_tpl);
						$fp = fopen('modules/'.$modulename.'/info.xml', "w");
						fwrite($fp, $info_tpl);
						fclose($fp);
						chmod('modules/'.$modulename.'/info.xml', 0777);
						
						$main_tpl = file_get_contents('modules/create_modul/tpl_main.tpl', true);
						$main_tpl = str_replace('{MODUL_TITLE}',$_POST['title'],$main_tpl);
						$main_tpl = str_replace('{MODUL_EDT_FF_FIELDS}',$edt_ff_fields,$main_tpl);
						$main_tpl = str_replace('{MODUL_EDT_TH}',$modul_row_fields2,$main_tpl);
						$fp = fopen('modules/'.$modulename.'/'.$modulename.'.tpl', "w");
						fwrite($fp, $main_tpl);
						fclose($fp);
						chmod('modules/'.$modulename.'/'.$modulename.'.tpl', 0777);
						
						
						$row_tpl = file_get_contents('modules/create_modul/tpl_item_row.tpl', true);
						$row_tpl = str_replace('{MODUL_TABLE_TR}',$modul_row_fields1,$row_tpl);
						$fp = fopen('modules/'.$modulename.'/item_row.tpl', "w");
						fwrite($fp, $row_tpl);
						fclose($fp);
						chmod('modules/'.$modulename.'/item_row.tpl', 0777);
						
						$tpl_html = file_get_contents('modules/create_modul/tpl_html.tpl', true);
						$tpl_html = str_replace('{MODUL_HTML_AREA}',$html_area,$tpl_html);
						$tpl_html = str_replace('{MODUL_TABLE_NAME}',$_POST['f_table'],$tpl_html);
						$tpl_html = str_replace('{MODUL_JS_FIELD1}',$modul_js_fields1,$tpl_html);
						$tpl_html = str_replace('{MODUL_JS_FIELD2}',$modul_js_fields2,$tpl_html);
						$fp = fopen('modules/'.$modulename.'/html.tpl', "w");
						fwrite($fp, $tpl_html);
						fclose($fp);
						chmod('modules/'.$modulename.'/html.tpl', 0777);
						
						
						$tpl_item = file_get_contents('modules/create_modul/tpl_item_php.tpl', true);
						$tpl_item = str_replace('{MODUL_TABLE_NAME}',$_POST['f_table'],$tpl_item);
						$tpl_item = str_replace('{MODUL_PHP_FIELDS}',$modul_item_php_fields,$tpl_item);
						$fp = fopen('modules/'.$modulename.'/item.php', "w");
						fwrite($fp, $tpl_item);
						fclose($fp);
						chmod('modules/'.$modulename.'/item.php', 0777);
						
						$tpl_del = file_get_contents('modules/create_modul/tpl_del.tpl', true);
						$tpl_del = str_replace('{MODUL_TABLE_NAME}',$_POST['f_table'],$tpl_del);
						$fp = fopen('modules/'.$modulename.'/del.php', "w");
						fwrite($fp, $tpl_del);
						fclose($fp);
						chmod('modules/'.$modulename.'/del.php', 0777);
						
						
						
						$tpl->assign("M_RESULT_ERR", "");
						$tpl->assign("ERR_DISPLAY", ' style="display: none;"');
						$tpl->assign("OK_DISPLAY", '');
						$tpl->assign("M_RESULT_OK", 'Модуль "'.$modulename.'" успешно создан.');
					}
				}
				
				
				$tpl->parse(strtoupper($moduleName), ".".$moduleName."result");
			break;
			default:
				$tpl->parse("META_LINK", ".".$moduleName."html");
				
				$rows = $dbc->db_free_query("SHOW TABLES");
				$numRows = $dbc->count; 
				if ($numRows > 0) {
					$t_row = '';
					foreach($rows as $row){
						$t_row.='<option value="'.$row[0].'">'.$row[0].'</option>';
					}
					$tpl->assign("TABLE_ROWS", $t_row);
				}
				else{
					$tpl->assign("TABLE_ROWS", '');
				}
				$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
			break;
		}
	}
	else{
		$tpl->parse("META_LINK", ".".$moduleName."html");
		
		$rows = $dbc->db_free_query("SHOW TABLES");
		$numRows = $dbc->count; 
		if ($numRows > 0) {
			$t_row = '';
			foreach($rows as $row){
				$t_row.='<option value="'.$row[0].'">'.$row[0].'</option>';
			}
			$tpl->assign("TABLE_ROWS", $t_row);
		}
		else{
			$tpl->assign("TABLE_ROWS", '');
		}
		
		$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
	}
?>