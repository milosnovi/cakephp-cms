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
				$modelPage->bindModel(array(
					'hasOne' => array(
						SLUG => array(
							'className' => SLUG,
							'foreignKey' => Slug::Fk,
							'conditions' => array(
								Slug::T_Type => Slug::TypeMain
							)
						)
					)
				));
				$search_result = $modelPage->find('all', array(
					'conditions' => array(
						'or' => array(
							Page::T_Content. " LIKE '%$search_term%'",
							Page::T_Title . " LIKE '%$search_term%'",
						)
					)
				));
				$finalSearchArray = array();
				foreach($search_result as $result) {
					$matches = array();
					preg_match("/.*$search_term.*/i", strip_tags($result[PAGE][Page::Content]), $matches);
					
					if(!empty($matches[0]) || (false !== strripos($result[PAGE][Page::Title], $search_term))) {
						$finalSearchArray[] = array(
							'id' => $result[PAGE][ID],
							'title' => $result[PAGE][Page::Title],
							'matched_string' => isset($matches[0]) ? $matches[0] : substr($result[PAGE][Page::Content],0, 512),
							'link' => Slug::makeRelativeLink($result[SLUG], PAGE, $result[PAGE][ID])
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
