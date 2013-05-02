<?php

class AuditsController extends AuditLogAppController {
	
	public $uses = array(
		'AuditLog.Audit',
		'AuditLog.AuditDelta',
		'User',
	);
	
	public $paginate = array(
		'limit' => 100,
		'order' => array(
			'Audit.created' => 'desc',
		),
	);
	
	public function admin_index() {
		$audits = $this->getAudits();
		
		$this->set(compact('audits'));
		$this->set('title_for_layout', 'Audit Logs');
	}
	
	public function getAudits($method = 'paginate') {
		if ($method == 'find') {
			$audits = $this->Audit->find('all');
		} else {
			$audits = $this->paginate();
		}
		
		$this->User->recursive = -1;
		foreach ($audits as $key => $value) {
			$audits[$key]['Model'] = json_decode( $value['Audit']['json_object'], true )[$value['Audit']['model']];
			$audits[$key]['User'] = $this->_getSource($value['Audit']);
			$audits[$key]['Audit']['log'] = $this->_formatLog($audits[$key]);
		}
		
		return $audits;
	}
	
	private function _getSource($data) {
		$user = $this->User->read(null, $data['source_id']);
		if ( ! $user) {
			$user = array('User' => json_decode( $data['source_object'], true ));
		}
		
		return $user['User'];
	}
	
	private function _formatLog($data) {
		
		$message = '<a href="' . Router::url('/admin/users/view/' . $data['User']['id']) . '">' . $data['User']['name'] . '</a>';
		
		switch ($data['Audit']['event']) {
			case 'CREATE': $message .= ' added '; break;
			case 'EDIT': $message .= ' updated '; break;
			case 'DELETE': $message .= ' deleted '; break;
			default: $message .= ' changed '; break;
		}
		
		$message .= $this->_inflect($data['Audit']['model']);
		
		if ($data['Audit']['event'] == 'DELETE') {
			$message .= ' <strong>' . $this->_modelName($data) . '</strong>';
		} else {
			$message .= ' <a href="' . Router::url('/admin/' . Inflector::tableize($data['Audit']['model']) . '/view/' . $data['Model']['id']) . '"><strong>' . $this->_modelName($data) . '</strong></a>';
		}
		
				
		return $message;
		
	}
	
	private function _modelName($data) {
		if (isset($data['Model']['name'])) {
			return $data['Model']['name'];
		}
		
		if (isset($data['Model']['title'])) {
			return $data['Model']['title'];
		}
		
		return '';
	}
	
	private function _inflect($model) {
		return Configure::read("AuditLog.inflections.$model") ? Configure::read("AuditLog.inflections.$model") : $model;
	}
	
}

?>