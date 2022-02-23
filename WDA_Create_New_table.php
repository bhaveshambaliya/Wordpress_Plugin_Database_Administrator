<div class="wrap">
	<?php require_once 'home-menu.php'; ?>
	<?php global $wpdb; ?>
	<div>
		Table name: <input type="text" name="WDA_table_name" required /> Add <input type="number" min="1" name="WDA_add_row_number" value="1" /> column(s) <button class="WDA-Default-Button" id="WDA_add_row_number_btn">Go</button>
		
		<h3>Structure</h3>
		<table class="WDA-tables-list" id="WDA-insert-new-field">
			<tr>
				<th>Name</th>
				<th>Type</th>
				<th>Length/Values</th>
				<th>Default</th>
				<th>Null</th>
				<th>Index</th>
				<th>Auto Increment</th>
			</tr>
			<?php WDA_create_table_rows(5); ?>
		</table>
		<div>
			<button class="WDA-Default-Button" id="WDA_create_new_table_schema">Save</button>
		</div>
	</div>
</div>