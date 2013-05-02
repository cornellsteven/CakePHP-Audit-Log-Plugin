<?php

class Audit extends AuditLogAppModel {
	
	public $name = 'Audit';
	
	public $hasMany = array(
		'AuditLog.AuditDelta',
	);
	
}

?>