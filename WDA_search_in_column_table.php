<div class="wrap">
<?php
	global $wpdb;
	$WDA_get_TABLE_ALL_LAble=WDA_get_TABLE_ALL_LAble($_GET['WDA_table']);
?>
	<a class='WDA_btn-back' href="<?= admin_url( 'admin.php?page=WDA-Administrator' ) ?>"><img src="<?= plugins_url('images/btn-back.png', __FILE__) ?>" style="width:50px;" /></a>
	<?php require_once 'WDA_innerPage_menu.php'; ?>
	<table class="WDA-tables-list">
		<form method="post" action="<?= admin_url( 'options.php?page=WDA_browse_table' )."&WDA_table=".$_GET['WDA_table'] ?>">
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Type</th>
			<th>Collation</th>
			<th>Operator</th>
			<th>Value</th>
		</tr>
		<?php
		$WDA_col_num=1;
		foreach($WDA_get_TABLE_ALL_LAble as $WDA_LAble){
		?>
		<tr>
			<td><?= $WDA_col_num ?></td>
			<td><?= $WDA_LAble->COLUMN_NAME ?></td>
			<td><?= $WDA_LAble->COLUMN_TYPE ?></td>
			<td><?= $WDA_LAble->COLLATION_NAME ?></td>
			<td>
				<input type="hidden" name="WDA_search_left_Label[<?= $WDA_col_num ?>]" value="<?= $WDA_LAble->COLUMN_NAME ?>" />
				<select data-index="<?= $WDA_col_num ?>" name="WDA_search_oprator[<?= $WDA_col_num ?>]">
					<option value="=">=</option>
					<option value=">">&gt;</option>
					<option value=">=">&gt;=</option>
					<option value="<">&lt;</option>
					<option value="<=">&lt;=</option>
					<option value="!=">!=</option>
					<option value="LIKE">LIKE</option>
					<option value="LIKE %...%">LIKE %...%</option>
					<option value="NOT LIKE">NOT LIKE</option>
					<option value="IN">IN (...)</option>
					<option value="NOT IN">NOT IN (...)</option>
					<option value="BETWEEN">BETWEEN</option>
					<option value="NOT BETWEEN">NOT BETWEEN</option>
					<option value="IS NULL">IS NULL</option>
					<option value="IS NOT NULL">IS NOT NULL</option>
				</select>
			</td>
			<td>
				<input data-index="<?= $WDA_col_num ?>" type="text" name="WDA_search_right_Value[<?= $WDA_col_num ?>]" />
			</td>
		</tr>
		<?php
			$WDA_col_num++;
		}
		?>
		<tr>
		<?php $colspan=count($WDA_get_TABLE_ALL_LAble)+3; ?>
		<td colspan="<?= $colspan ?>" style="text-align:right;">
			<input class="WDA-Default-Button" type="submit" name="WDA_Search_fildes" value="GO" />
		</td>
		</tr>
	</form>
</div>