<?php
	class PagesController extends AppController {
		var $uses = array(PAGE, MENUITEM, SLUG);
	
		public function home() {
			$this->set('title_for_layout', 'ADR - polaganje ispita i izdavanje sertifikata');
			$this->set('active_main_menuitem', 3);
			
			$pageData = $this->Page->find('first', array(
				'conditions' => array(
					Page::Id => 3
				)
			));
			$html_reg = "/<+\s*\/*\s*([A-Z][A-Z0-9]*)\b[^>]*\/*\s*>+/i";
			//$pageData[PAGE][Page::Content] = preg_replace($html_reg, '', mb_substr($pageData[PAGE][Page::Content], 0, 800));
			$pageContent = substr(strip_tags($pageData[PAGE][Page::Content]), 0, 700);
			$pageData[PAGE][Page::Content] = sprintf('%s...', $pageContent);
			$this->set('adr_info', $pageData);
			
			$page2 = $this->Page->find('first', array(
				'conditions' => array(
					Page::Id => 16
				)
			));
//			$page2[PAGE][Page::Content] = htmlentities(preg_replace($html_reg, '', mb_substr($page2[PAGE][Page::Content], 0, 800)), ENT_COMPAT, 'UTF-8');
			$pageContent = substr(strip_tags($page2[PAGE][Page::Content]), 0, 700);
			$page2[PAGE][Page::Content] = sprintf('%s...', $pageContent);
			$this->set('procena_rizika', $page2);
		}
		
		public function admin_add_page() {
			$data[MENUITEM]['parent_id'] = 1;
			$data[MENUITEM]['name'] = 'Skating';
			$this->Menuitem->save($data);
		}
		
		public function admin_move_down() {
			$this->Menuitem->id = 17;
			$this->Menuitem->moveDown($this->Menuitem->id,1);
		}
		
		public function admin_move_up() {
			$this->Menuitem->id = 17;
			$this->Menuitem->moveup($this->Menuitem->id,1);
		}
		
		public function admin_delete_page($pageId) {
			$success = $this->Page->delete($pageId);
			debug($success);
			$menuitems = $this->Menuitem->find('all', array(
				'conditions' => array(Menuitem::T_ContentId => $pageId)
				)
			);
			$menuitemIds = Set::extract($menuitems, '{n}.'. MENUITEM . '.' .ID);
			if (!empty($menuitemIds)) {
				foreach($menuitemIds as $menuitemId) {
					$this->Menuitem->id = $menuitemId;
					$success &= $this->Menuitem->delete();
				}
			}
			
			$this->returnJsonData(array(
				'success' => $success,
				'message' => $success ? 'super' : 'loso' 
			));
		}
		
		public function admin_create_page() {
			if (!isset($this->data[PAGE][Page::Title])) {
				$this->returnJsonData(array(
					'success' => false,
					'message' => __('Missing data')
				));
			}
			
			$success = $this->Page->save($this->data[PAGE]);
			$success &= $this->Slug->setSlug(PAGE, $this->Page->id, $this->data[PAGE][Page::Title]);
			
			if ($success && isset($this->data[MENUITEM][Menuitem::A_MainMenu])) {
				$isMainMenu = $this->data[MENUITEM][Menuitem::A_MainMenu];
				$data[MENUITEM] = array(
					Menuitem::ParantId => $isMainMenu ? 1 : 2,
					Menuitem::Title => $this->data[PAGE][Page::Title],
					Menuitem::Type => $isMainMenu ? Menuitem::TypeMain : Menuitem::TypeSide,
					Menuitem::ContentType => PAGE,
					Menuitem::ContentId => $this->Page->id,
					Menuitem::Visible => !empty($this->data[MENUITEM][Menuitem::Visible]),
					Menuitem::Url => "/pages/view/{$this->Page->id}"
				);

				$success &= $this->Menuitem->save($data[MENUITEM]);
			}
			
			if ($success) {
				$pageData = $this->__getPageData($this->Page->id);
				$this->returnJsonData(array(
					'success' => true,
					'message' => 'Page is succesfully created',
					PAGE => $pageData
				));
			} else {
				$this->returnJsonData(array(
					'success' => false,
					'message' => $this->Page->validationErrors
				));
			}
		}
		
		public function contact() {
			$this->set('title_for_layout', 'Inkoplan d.o.o. :: Contact');
			$menuitem = $this->Menuitem->find('first', array('conditions' => array('menuitem.url' =>'/contact')));
			$this->set('active_main_menuitem', $menuitem['Menuitem']['id']);
			
			if ($this->RequestHandler->isPost()) {
				require_once(MODELS . 'usercontact.php');
				$modelUserContact = new UserContact();
			    $modelUserContact->set($this->data['Usercontact']);
				$errors = $modelUserContact->invalidFields();
				
				if ($errors) {
					$validationError = '';
					foreach($errors as $field => $error_message) {
						$validationError .= sprintf('%s: %s<br/>', $field, $error_message);
					}
					$this->Session->setFlash($validationError);
				} else {
					$fromName = $this->data['Usercontact'][UserContact::Name];
					$fromEmail = $this->data['Usercontact'][UserContact::Email];
					$message = $this->data['Usercontact'][UserContact::Message];
					
					$this->Email->to = 'm.novicevic@gmail.com';
					$this->Email->subject = 'Inkoplan:: Kontakt strana';
					$this->Email->replyTo = sprintf('%s <%s>', $fromName, $fromEmail);
					$this->Email->from = sprintf('%s', $fromName);
					$this->Email->template = 'contact';
					$this->Email->layout = 'default';
					$this->Email->sendAs = 'both';
					
					// Set view variables as normal
					$this->set('fromName', $fromName);
					$this->set('fromEmail', $fromEmail);
					$this->set('message', $message);
					
					// Do not pass any args to send()
					$sent = $this->Email->send();
					if (!$sent) {
						$result = sprintf( __("SMTP Error. %s", true), $this->Email->smtpError);
					}
				}				
			}
		}
		
		public function view($id) {
			$activeMenuitem = $this->Menuitem->find('first', array(
				'conditions' => array(
					Menuitem::T_ContentId => $id,
					Menuitem::T_ContentType => 'PAGE'
				))
			);
			$this->set('title_for_layout', "Inkoplan :: {$activeMenuitem[PAGE][Page::Title]}");
			$this->set('active_main_menuitem', $activeMenuitem['Menuitem']['id']);
			$this->set('page', $this->Page->find('first', array('conditions' => array('Page.id' => $id))));
		}
		
		public function admin_form($id = null) {
			$success = true;
			$returnData = array();
			if (RequestHandlerComponent::isPost()) {
				$id = $this->data[PAGE][ID];
				$this->Page->bindModel(array(
					'hasOne' => array(
						MENUITEM => array(
							'className' => MENUITEM,
							'foreignKey' => Menuitem::ContentId,
							'conditions' => array(Menuitem::ContentType => PAGE)
						)
					)
				));
				$pageData = $this->Page->find('first', array(
					'conditions' => array(
						Page::Id => $id,
						Menuitem::T_ContentId => $id 
					)
				));
				$menuitemTitleIsSync = isset($pageData[MENUITEM][Menuitem::Title]) && ($pageData[MENUITEM][Menuitem::Title] == $pageData[PAGE][Page::Title]);
				$pageTitleIsChanged =  $this->data[PAGE][Page::Title] != $pageData[PAGE][Page::Title];
				
				$this->Page->id = $id;
				$success = $this->Page->save(array(
					Page::Content => $this->data[PAGE][Page::Content],
					Page::Title => $this->data[PAGE][Page::Title]
				));
				
				if ($pageTitleIsChanged && $success) {
					// kreiraj novi slug, stari na 301
					if ($menuitemTitleIsSync) {
						$this->Menuitem->id = $pageData[MENUITEM][ID];
						$success = $this->Menuitem->save(array(Menuitem::Title => $this->data[PAGE][Page::Title]));
					}
				}
				
				$returnData = array(
					'success' => $success ? true : false,
					'message' => $success ? 'Page successfully saved' : $this->Page->validationErrors
				);
			}
			
			$pageData = $this->__getPageData($id);
			$this->returnJsonData(am($returnData, array(
				PAGE => $pageData['Page']
			)));
		}
		
		private function __getPageData($pageId) {
			$this->Menuitem->bindModel(array(
				'belongsTo' => array(
					'Page' => array(
						'className' => 'Page',
						'foreignKey' => Menuitem::ContentId,
						'conditions' => array(Menuitem::T_ContentType => 'PAGE')
					)
				)
			));
			
			$pageData = $this->Page->find('first', array('conditions' => array(Page::Id => $pageId)));
			return $pageData;
		}
		
		public function test_search() {
			//SELECT `Page`.`id`, `Page`.`title`, `Page`.`content`, `Page`.`created`, `Page`.`modified` FROM `pages` AS `Page`   WHERE `Page`.`content` LIKE %osnovan%
			$search_result = $this->Page->find('all', array(
				'conditions' => array(
					Page::T_Content. " LIKE '%opasne materije%'"
				)
			));
			$matches = array();
			preg_match('/opasne materije./i', $search_result[0][PAGE][Page::Content], $matches);
			debug($matches);
			debug($search_result);
			debug(strpos($search_result[0][PAGE][Page::Content], 'osnovan'));
		}
	}