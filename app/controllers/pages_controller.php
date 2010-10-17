<?php
class PagesController extends AppController {
	
	public function display() {
		
	}
	
	public function contact() {
		if ($this->RequestHandler->isPost()) {
			
		}
	}
	
	public function view($id) {
		$pageData = $this->Page->find('first', array(
			'conditions' => array(
				'Page.id' => $id
			)
		));
		$this->set('page', $pageData);
	}
}