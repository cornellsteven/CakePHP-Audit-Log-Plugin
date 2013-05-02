<?php

class AuditDelta extends AuditLogAppModel {
	
	public $name = 'AuditDelta';
	
	public $belongsTo = array(
		'AuditLog.Audit',
	);

}

?>