<div class="wrap">
	<?php require_once 'home-menu.php'; ?>
	<table class="WDA-tables-list">
		<tr>
			<th>Table</th>
			<th>Action</th>
			<th>Rows</th>
			<th>Type</th>
			<th>Collation</th>
			<th>Size (MB)</th>
		</tr>
	<?php
	global $wpdb;
		$WDA_database_table_list=$wpdb->get_results("show table status");
		$WDA_total_table=$wpdb->num_rows;
		$WDA_total_rows=0;
		$WDA_total_mb_size=0;
		foreach($WDA_database_table_list as $WDA_database_table){
			echo"<tr>";
				echo"<td>".$WDA_database_table->Name."</td>";
				echo"<td> <a class='table-action-a' href='".admin_url( 'options.php?page=WDA_browse_table' )."&WDA_table=".$WDA_database_table->Name."'>Browse</a> <a class='table-action-a' href='".admin_url( 'options.php?page=WDA_structure_table' )."&WDA_table=".$WDA_database_table->Name."'>Structure</a> <a class='table-action-a' href='".admin_url( 'options.php?page=WDA_search_in_column_table' )."&WDA_table=".$WDA_database_table->Name."'>Search</a> <a class='table-action-a' href='".admin_url( 'options.php?page=WDA_insert_in_table' )."&WDA_table=".$WDA_database_table->Name."'>Insert</a> <a class='table-action-a WDA_truncate_database_table' data-table_name='".$WDA_database_table->Name."' href='#'>Empty</a> <a class='table-action-a WDA_delete_database_table' data-table_name='".$WDA_database_table->Name."' href='#'>Drop</a></td>";
				$WDA_count_row_of_table=$wpdb->get_row("SELECT COUNT(*) as cnt FROM ".$WDA_database_table->Name);
				$WDA_total_rows+=$WDA_count_row_of_table->cnt;
				echo"<td>".$WDA_count_row_of_table->cnt."</td>";
				echo"<td>".$WDA_database_table->Engine."</td>";
				echo"<td>".$WDA_database_table->Collation."</td>";
				$WDA_database_table_size=$wpdb->get_row("SELECT TABLE_NAME AS `Table`,ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024),2) AS `Size` FROM information_schema.TABLES WHERE  TABLE_NAME = '".$WDA_database_table->Name."'");
				$WDA_total_mb_size+=$WDA_database_table_size->Size;
				echo"<td>".$WDA_database_table_size->Size."</td>";
			echo"</tr>";
		}
	?>
		<tr>
			<th><?= $WDA_total_table ?> tables</th>
			<th>Sum</th>
			<th><?= $WDA_total_rows ?></th>
			<th>Type</th>
			<th>Collation</th>
			<th><?= $WDA_total_mb_size ?></th>
		</tr>
	</table>
</div>