<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>Artist Name</th>
		<th>Plans</th>
		<th>Price</th>
		<th>Start Date</th>
		<th>Expire Date</th>
	</tr>
<?php

	foreach ($exportData as $export) {
		?>
		<tr>
			<td>{!! $export->name !!}</td>
			<td>{!! $export->plan_name !!}</td>
			<td>{!! $export->price !!}</td>
			<td>{!! date("d-m-Y", strtotime($export->start_date))!!}</td>
			<td>{!! date("d-m-Y", strtotime($export->expire_date))!!}</td>
		</tr>
	<?php }
?>
</table>