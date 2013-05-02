<div class="page-header">
	<h1>Audit Log</h1>
</div>
<table class="table table-striped table-bordered">
	<tr>
		<th>Event</th>
		<th style="width: 140px; text-align: center;"><?php echo $this->Paginator->sort('created', 'Timestamp'); ?></th>
	</tr>
<?php foreach ($audits as $audit): ?>
	<tr>
		<td><?php echo $audit['Audit']['log']; ?></td>
		<td style="text-align: center;"><?php echo date('m-d-Y h:i A', strtotime($audit['Audit']['created'])); ?></td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('pagination'); ?>