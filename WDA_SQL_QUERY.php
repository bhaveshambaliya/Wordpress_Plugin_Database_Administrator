<div class="wrap">
	<?php require_once 'home-menu.php'; ?>
	<?php
	global $wpdb;
	$WDA_sql_box="";
	if(isset($_POST['WDA_sql_box']))
		$WDA_sql_box=stripslashes($_POST['WDA_sql_box']);
	
	
	?>
	<form method="post">
		<div class='sql-box'>
			<textarea style="width:100%;" rows="14" name="WDA_sql_box" required ><?= $WDA_sql_box ?></textarea>
		</div>
		<div class='sql-box-footer' style="text-align: right;">
			<input class='WDA-Default-Button' type="submit" name="WDA_submit_sql" value="Go" />
		</div>
	</form>
	
	<?php
	if(!empty($WDA_sql_box)){
		if($result=$wpdb->get_results("$WDA_sql_box")){
			if($wpdb->num_rows>0){
			echo'<table class="WDA-tables-list">';
				echo'<tr>';
					foreach($result[0] as $label=>$value){
						echo "<th>".$label."</th>";
					}
				echo'</tr>';
				
				foreach($result as $row){
					echo'<tr>';
						foreach($row as $label=>$value){
							echo "<td>".htmlspecialchars($value)."</td>";
						}
					echo'</tr>';
				}			
			echo'</table>';
			}
		}else{
			if(!empty($wpdb->last_error)){
				echo"<div class='WDA_error_message'>".$wpdb->last_error."</div>";
			}else{
				echo"<div class='WDA_success_message'>Your Query execute successfully</div>";
			}
		}
	}
	?>
</div>