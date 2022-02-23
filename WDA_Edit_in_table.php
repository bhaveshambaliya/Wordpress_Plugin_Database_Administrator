<div class="wrap">
<?php
	global $wpdb;
	$WDA_success_message="";
	$WDA_error_message="";
	$WDA_get_TABLE_ALL_LAble=WDA_get_TABLE_ALL_LAble($_GET['WDA_table']);
	
	$empty_array=array();
	foreach($WDA_get_TABLE_ALL_LAble as $WDA_LAble){
		$empty_array[$WDA_LAble->COLUMN_NAME]='';
	}
	
	$WDA_get_Primery_Key=$wpdb->get_row("SHOW INDEX FROM ".$_GET['WDA_table']." WHERE Key_name = 'PRIMARY'");		
	$WDA_Primery_key_name=$WDA_get_Primery_Key->Column_name;
	
	if(isset($_POST['WDA_update_table']) && count($WDA_get_TABLE_ALL_LAble)!=0){
		$update_arrAy=$_POST;
		unset($update_arrAy['WDA_update_table']);
		$update_Where_arrAy=array();
		$update_Where_arrAy[$_GET["WDA_Primery_key_name"]]=$_GET['WDA_Primery_key_value'];
		if($wpdb->update($_GET['WDA_table'],$update_arrAy,$update_Where_arrAy)){
			$WDA_success_message="Successfully Updated";
		}else{
			$WDA_error_message=$wpdb->last_error;
		}
	}
	
	
?>
<a class='WDA_btn-back' href="<?= admin_url( 'admin.php?page=WDA-Administrator' ) ?>"><img src="<?= plugins_url('images/btn-back.png', __FILE__) ?>" style="width:50px;" /></a>
	<?php require_once 'WDA_innerPage_menu.php'; ?>
	<?php if(count($WDA_get_TABLE_ALL_LAble)!=0 && isset($_GET['WDA_Primery_key_name']) && $WDA_Primery_key_name==$_GET['WDA_Primery_key_name'] && isset($_GET['WDA_Primery_key_value'])){ ?>
	
	<?php
		if(!empty($WDA_success_message)){
			echo"<div class='WDA_success_message'>".$WDA_success_message."</div>";
		}
		
		if(!empty($WDA_error_message)){
			echo"<div class='WDA_error_message'>".$WDA_error_message."</div>";
		}
	?>
	<table class="WDA-tables-list">
		<form method="post">
			<tr>
				<th>Name</th>
				<th>Type</th>
				<th>Null</th>
				<th></th>
			</tr>
			<?php
			$WDA_col_num=1;
			
			$WDA_Edit_Column_row=$wpdb->get_row('SELECT * FROM '.$_GET['WDA_table'].' WHERE '.$_GET['WDA_Primery_key_name'].'="'.$_GET['WDA_Primery_key_value'].'"');
			
			foreach($WDA_get_TABLE_ALL_LAble as $WDA_LAble){
				$WDA_lb=$WDA_LAble->COLUMN_NAME;
				$empty_array[$WDA_LAble->COLUMN_NAME]=$WDA_Edit_Column_row->$WDA_lb;
			?>
			<tr>
				
				<td><?= $WDA_LAble->COLUMN_NAME ?></td>
				<td><?= $WDA_LAble->COLUMN_TYPE ?></td>			
				<td><?= $WDA_LAble->IS_NULLABLE ?></td>
				<td><input name="<?= $WDA_LAble->COLUMN_NAME ?>" value='<?= $empty_array[$WDA_LAble->COLUMN_NAME] ?>' /></td>
			</tr>
			<?php
				$WDA_col_num++;
			}
			?>
			<tr>
				<td colspan="3"></td>
				<td><input class="WDA-Default-Button" type="submit" name="WDA_update_table" value="Go" /></td>
			</tr>
		</form>
	<table>
	<?php }else{ ?>
		<h2>No table found.</h2>
	<?php } ?>
</div>