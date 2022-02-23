<?php
//minimize content for show
function WDA_minimize_text_Content($content,$size){
	if(strlen($content) < $size){
		return $content;
	}
	return substr($content,0,$size)."...";			
}
//update value of column for edit
add_action('wp_ajax_nopriv_WDA_update_Column_of_Table', 'WDA_update_Column_of_Table' );
add_action('wp_ajax_WDA_update_Column_of_Table', 'WDA_update_Column_of_Table' );
function WDA_update_Column_of_Table(){  
    global $wpdb;
	$request=$_POST;
	$update_array=array();
	$update_array[$request[WDA_operation_label]]=stripslashes($request['WDA_new_content']);
	$where_update_array=array();
	$where_update_array[$request['WDA_primary_label']]=$request['WDA_primary_value'];
	$result=array('status'=>false);
	if($wpdb->update($request['WDA_table_name'],$update_array,$where_update_array)){
		$result=array('status'=>true,'content'=>WDA_minimize_text_Content($update_array[$request[WDA_operation_label]],50));
	}else{
		$result['error_message']=$wpdb->last_error;
	}
	echo json_encode($result);
	die();
}
//get value for textarea for edit
add_action('wp_ajax_nopriv_WDA_Get_Column_of_Table', 'WDA_Get_Column_of_Table' );
add_action('wp_ajax_WDA_Get_Column_of_Table', 'WDA_Get_Column_of_Table' );
function WDA_Get_Column_of_Table(){
	global $wpdb;
	$request=$_POST;
	$result=array('status'=>false);
	$WDA_operation_label=$request['WDA_operation_label'];
	$content=$wpdb->get_row("SELECT ".$request['WDA_operation_label']." FROM ".$request['WDA_table_name']." where ".$request['WDA_primary_label']."='".$request['WDA_primary_value']."'");
	if($wpdb->num_rows==1){
		$result=array('status'=>true,'content'=>$content->$WDA_operation_label);
	}
	echo json_encode($result);
	die();
}

//truncate tabel
add_action('wp_ajax_nopriv_WDA_truncate_database_table', 'WDA_truncate_database_table' );
add_action('wp_ajax_WDA_truncate_database_table', 'WDA_truncate_database_table' );
function WDA_truncate_database_table(){
	global $wpdb;	
	if($wpdb->query('TRUNCATE TABLE '.$_POST['table'])){
		$array=array('status'=>true,"message"=>"Table truncate successfully.");
	}else{
		$array=array('status'=>false,"message"=>"Something wrong.");
	}
	echo json_encode($array);
	die();
}

//delete tabel
add_action('wp_ajax_nopriv_WDA_delete_database_table', 'WDA_delete_database_table' );
add_action('wp_ajax_WDA_delete_database_table', 'WDA_delete_database_table' );
function WDA_delete_database_table(){
	global $wpdb;	
	if($wpdb->query('DROP TABLE '.$_POST['table'])){
		$array=array('status'=>true,"message"=>"Table delete successfully.");
	}else{
		$array=array('status'=>false,"message"=>"Something wrong.");
	}
	echo json_encode($array);
	die();
}

//for update parameters of url
function WDA_addOrUpdateUrlParam($name, $value,$sort_type=""){
	$params = $_GET;
	unset($params[$name]);
	$params[$name] = $value;
	if($sort_type!=""){
		$params['sort_type'] = $sort_type;
	}
	return basename($_SERVER['PHP_SELF']).'?'.http_build_query($params);
}

//for get all labels of table
function WDA_get_TABLE_ALL_LAble($WDA_table_name){
	global $wpdb;
	$WDA_Coulumn_Label_Array=array();
	$WDA_Columns_Labels=$wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='".$WDA_table_name."' GROUP BY COLUMN_NAME ORDER BY ORDINAL_POSITION");
	foreach($WDA_Columns_Labels as $WDA_Columns_Label){
		array_push($WDA_Coulumn_Label_Array,$WDA_Columns_Label);
	}
	return $WDA_Coulumn_Label_Array;
}
//for delete record
add_action('wp_ajax_nopriv_WDA_Delete_Column', 'WDA_Delete_Column' );
add_action('wp_ajax_WDA_Delete_Column', 'WDA_Delete_Column' );
function WDA_Delete_Column(){
	global $wpdb;
	$WDA_table_Name=$_POST['WDA_table_Name'];
	$WDA_primery_key_name=$_POST['WDA_primery_key_name'];
	$WDA_primery_key_value=$_POST['WDA_primery_key_value'];
	$WDA_Delete_Where=array();
	$WDA_Delete_Where[$_POST['WDA_primery_key_name']]=$WDA_primery_key_value;
	if($wpdb->delete($WDA_table_Name,$WDA_Delete_Where)){
		echo json_encode(array('status'=>true));
	}else{
		echo json_encode(array('status'=>false,"message"=>$wpdb->last_error));
	}
	die();
}
//for create new table
add_action('wp_ajax_nopriv_WDA_create_table_shcema', 'WDA_create_table_shcema' );
add_action('wp_ajax_WDA_create_table_shcema', 'WDA_create_table_shcema' );
function WDA_create_table_shcema(){
	global $wpdb;
	$WDA_create_Query_Index="";
	$WDA_create_Query="CREATE TABLE ".$_POST['table_name']." ( ";
		$counter_field=1;
		foreach($_POST['WDA_row_Collection'] as $WDA_row_Collection){
			if($counter_field!=1){
				$WDA_create_Query.=" , ";
			}
			$WDA_create_Query.=" ".$WDA_row_Collection['WDA_column_name']." ".$WDA_row_Collection['WDA_data_type'];
			if(!empty($WDA_row_Collection['WDA_value_length'])){
				$WDA_create_Query.="(".$WDA_row_Collection['WDA_value_length'].") ";
			}		
			
			if($WDA_row_Collection['WDA_allow_null'] == 'NO'){
				$WDA_create_Query.=" NOT NULL ";
			}
			
			if(!empty($WDA_row_Collection['WDA_Default_value'])){
				if($WDA_row_Collection['WDA_Default_value']=='As Defined'){
					if(!empty($WDA_row_Collection['WDA_Default_as_Define_value'])){
						$WDA_create_Query.=" DEFAULT '".$WDA_row_Collection['WDA_Default_as_Define_value']."' ";
					}
				}else if($WDA_row_Collection['WDA_Default_value']=='NULL' || $WDA_row_Collection['WDA_Default_value']=='CURRENT_TIMESTAMP'){
					$WDA_create_Query.=" DEFAULT ".$WDA_row_Collection['WDA_Default_value']." ";
				}
			}
			
			if($WDA_row_Collection['WDA_auto_increment']=="YES"){
				$WDA_create_Query.=" AUTO_INCREMENT ";
			}
			
			if(!empty($WDA_row_Collection['WDA_index'])){
				$WDA_create_Query_Index.=" ,".$WDA_row_Collection['WDA_index']." (".$WDA_row_Collection['WDA_column_name'].") ";
			}
			
			$counter_field++;
		}
		$WDA_create_Query.=$WDA_create_Query_Index;
	$WDA_create_Query.=" );";
	$wpdb->query($WDA_create_Query);
	$error=$wpdb->last_error;
	if($error == ""){
		echo json_encode(array("status"=>"success","title"=>"Successfully Created","message"=>"Table '".$_POST['table_name']."' Successfully created."));
	}else{
		echo json_encode(array("status"=>"error","title"=>"Fail to create table","message"=>$error));
	}
	die();
}
//Drop column
add_action('wp_ajax_nopriv_WDA_Drop_Column_table', 'WDA_Drop_Column_table' );
add_action('wp_ajax_WDA_Drop_Column_table', 'WDA_Drop_Column_table' );
function WDA_Drop_Column_table(){
	global $wpdb;
	if($wpdb->query('ALTER TABLE '.$_POST['WDA_table_Name'].' DROP COLUMN '.$_POST['WDA_column_Name'])){
		echo json_encode(array('status'=>true));
	}else{
		echo json_encode(array('status'=>false,'message'=>$wpdb->last_error));
	}
	die();
}
//HTML for create table
add_action('wp_ajax_nopriv_WDA_create_table_rows', 'WDA_create_table_rows' );
add_action('wp_ajax_WDA_create_table_rows', 'WDA_create_table_rows' );
function WDA_create_table_rows($count=1){
	if(isset($_POST['num_rows'])){
		$count=$_POST['num_rows'];
	}
	for($i=1;$i<=$count;$i++){
?>
<tr class='WDA_rows-for-create-table'>
	<td><input type="text" name="WDA_column_name" /></td>
	<td>
		<select name="WDA_data_type">
			<option value="INT">INT</option>
			<option value="VARCHAR">VARCHAR</option>
			<option value="TEXT">TEXT</option>
			<option value="DATE">DATE</option>
			<option value="DATETIME">DATETIME</option>
			<option value="FLOAT">FLOAT</option>
			<option value="TINYINT">TINYINT</option>
			<option value="SMALLINT">SMALLINT</option>
			<option value="MEDIUMINT">MEDIUMINT</option>
			<option value="BIGINT">BIGINT</option>
			<option value="DOUBLE">DOUBLE</option>
			<option value="REAL">REAL</option>
			<option value="TIMESTAMP">TIMESTAMP</option>
			<option value="TIME">TIME</option>
			<option value="YEAR">YEAR</option>
			<option value="CHAR">CHAR</option>
			<option value="TINYTEXT">TINYTEXT</option>
			<option value="MEDIUMTEXT">MEDIUMTEXT</option>
			<option value="LONGTEXT">LONGTEXT</option>
		</select>
	</td>
	<td><input type="text" name="WDA_value_length" /></td>
	<td>
		<select name="WDA_Default_value">
			<option value="">None</option>
			<option value="As Defined">As Defined:</option>
			<option value="NULL">NULL</option>
			<option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
		</select>
		<input type='text' class='WDA_Default_as_Define_value' name='WDA_Default_as_Define_value' style="display:none;" />
	</td>
	<td>
		<select name="WDA_allow_null">
			<option value="NO">NO</option>
			<option value="YES">YES</option>
		</select>
	</td>
	<td>
		<select name="WDA_index">
			<option value="">---</option>
			<option value="PRIMARY KEY">PRIMARY</option>
			<option value="UNIQUE">UNIQUE</option>
			<option value="INDEX">INDEX</option>
			<option value="FULLTEXT">FULLTEXT</option>
			<option value="SPATIAL">SPATIAL</option>
		</select>
	</td>
	<td>
		<select name="WDA_auto_increment">
			<option value="NO">NO</option>
			<option value="YES">YES</option>
		</select>
	</td>
</tr>
<?php
	}
	if(isset($_POST['num_rows'])){
		die();
	}
}
?>