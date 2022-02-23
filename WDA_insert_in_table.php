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
	
	if(isset($_POST['WDA_insert_table']) && count($WDA_get_TABLE_ALL_LAble)!=0){
		$insert_array=array();
		foreach($WDA_get_TABLE_ALL_LAble as $WDA_LAble){
			$insert_array[$WDA_LAble->COLUMN_NAME]=$_POST[$WDA_LAble->COLUMN_NAME];
		}
		if($wpdb->insert($_GET['WDA_table'],$insert_array)){
			$WDA_success_message="Successfully inserted";
		}else{
			$WDA_error_message=$wpdb->last_error;
			foreach($WDA_get_TABLE_ALL_LAble as $WDA_LAble){
				$empty_array[$WDA_LAble->COLUMN_NAME]=$_POST[$WDA_LAble->COLUMN_NAME];
			}
		}
	}
?>
<a class='WDA_btn-back' href="<?= admin_url( 'admin.php?page=WDA-Administrator' ) ?>"><img src="<?= plugins_url('images/btn-back.png', __FILE__) ?>" style="width:50px;" /></a>
	<?php require_once 'WDA_innerPage_menu.php'; ?>
	<?php if(count($WDA_get_TABLE_ALL_LAble)!=0){ ?>
	
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
			foreach($WDA_get_TABLE_ALL_LAble as $WDA_LAble){
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
				<td><input class="WDA-Default-Button" type="submit" name="WDA_insert_table" value="Go" /></td>
			</tr>
		</form>
	<table>
	<?php }else{ ?>
		<h2>No table found.</h2>
	<?php } ?>
</div>