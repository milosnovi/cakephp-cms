<?php
class SearchController extends AppController {
	public $uses = array();
	
	public function index() {
		if ($this->RequestHandler->isPost()) {
			$search_term = $this->data['Site']['SearchTxt'];
			$success = true;
			if (0 >= strlen($search_term)) {
				$message = __('Upišite frazu koju tražite', true);
				$success = false;
			}
			
			if ($success && (2 >= strlen($search_term))) {
				$message =__('Fraza mora biti duža od 2 karaktera', true);
				$success = false;
			}
			if ($success) {
				require_once(MODELS. 'page.php');
				$modelPage = new Page();
				$search_result = $modelPage->find('all', array(
					'conditions' => array(
						Page::T_Content. " LIKE '% $search_term%'"
					)
				));
				$finalSearchArray = array();
					
				foreach($search_result as $result) {
					$matches = array();
					preg_match("/.*$search_term.*/i", strip_tags($result[PAGE][Page::Content]), $matches);
					
					if(!empty($matches[0])) {
						$finalSearchArray[] = array(
							'id' => $result[PAGE][ID],
							'title' => $result[PAGE][Page::Title],
							'matched_string' => $matches[0] 
						);
					}
				}
				$this->set('search_term', $search_term);
				$this->set('finalSearchArray', $finalSearchArray);
			} else {
				 $this->Session->setFlash($message);
			}
		}
	}
}
