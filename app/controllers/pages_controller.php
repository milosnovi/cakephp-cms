<?php
	class PagesController extends AppController {
		var $uses = array(PAGE, MENUITEM, SLUG);
	
		public function home() {
			$this->set('title_for_layout', 'ADR - polaganje ispita i izdavanje sertifikata');
			$this->set('active_main_menuitem', 3);
			
			$pageData = $this->Page->find('first', array('conditions' => array(Page::Id => 3)));
			
			$html_reg = "/<+\s*\/*\s*([A-Z][A-Z0-9]*)\b[^>]*\/*\s*>+/i";
			//$pageData[PAGE][Page::Content] = preg_replace($html_reg, '', mb_substr($pageData[PAGE][Page::Content], 0, 800));
			$pageContent = substr(strip_tags($pageData[PAGE][Page::Content]), 0, 700);
			$pageData[PAGE][Page::Content] = sprintf('%s...', $pageContent);
			$this->set('adr_info', $pageData);
			
			$page2 = $this->Page->find('first', array('conditions' => array(Page::Id => 2)));
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
			$dataSource = $this->Page->getDataSource();
			$dataSource->begin($this->Page);
			
			$success = $this->Page->delete($pageId);
			
			$menuitems = $this->Menuitem->find('all', array(
				'conditions' => array(Menuitem::T_ContentId => $pageId)
				)
			);
			$menuitemIds = Set::extract($menuitems, '{n}.'. MENUITEM . '.' . ID);
			if (!empty($menuitemIds)) {
				foreach($menuitemIds as $menuitemId) {
					$this->Menuitem->id = $menuitemId;
					$success &= $this->Menuitem->delete();
				}
			}
			
			if ($success){
				$dataSource->commit($this->Page);
				$message = __('Page is successfully deleted.', true);
			} else {
				$dataSource->rollback($this->Page);
				$message = __('Page is not successfully deleted.', true);
			}
			
			$this->returnJsonData(array(
				'success' => $success,
				'message' => $message 
			));
		}
		
		public function admin_create_page() {
			$dataSource = $this->Page->getDataSource();
			$dataSource->begin($this->Page);
			
			if (!isset($this->data[PAGE][Page::Title])) {
				$this->returnJsonData(array(
					'success' => false,
					'message' => __('Missing data')
				));
			}
			
			$success = $this->Page->save($this->data[PAGE]);
			if ($success) {
				$success = $this->Slug->createSlug(PAGE, $this->Page->id, $this->data[PAGE][Page::Title]);
			}
			
			if ($success && isset($this->data[MENUITEM][Menuitem::A_MainMenu])) {
				$isMainMenu = $this->data[MENUITEM][Menuitem::A_MainMenu];
				$data[MENUITEM] = array(
					Menuitem::ParantId => ($isMainMenu ? $this->Menuitem->getRootNodeId(Menuitem::TypeMain) : $this->Menuitem->getRootNodeId(Menuitem::TypeSide)),
					Menuitem::Title => $this->data[PAGE][Page::Title],
					Menuitem::Type => $isMainMenu ? Menuitem::TypeMain : Menuitem::TypeSide,
					Menuitem::ContentType => PAGE,
					Menuitem::ContentId => $this->Page->id,
					Menuitem::Visible => !empty($this->data[MENUITEM][Menuitem::Visible]),
					Menuitem::Url => "/pages/view/{$this->Page->id}"
				);

				$success = $this->Menuitem->save($data[MENUITEM]);
			}
			
			if ($success) {
				$dataSource->commit($this->Page);
				$pageData = $this->__getPageData($this->Page->id);
				$this->returnJsonData(array(
					'success' => true,
					'message' => __('Page is succesfully created', true),
					 PAGE => $pageData
				));
			} else {
				$dataSource->rollback($this->Page);
				$this->returnJsonData(array(
					'success' => false,
					'message' => am($this->Page->validationErrors, $this->Slug->validationErrors) 
				));
			}
		}
		
		public function report_bad_link() {
			$this->autoRender = false;
			$this->redirect('/');
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
			$page = $this->Page->find('first', array('conditions' => array('Page.id' => $id)));
			if (empty($page)) {
				/*
			 	* echo $this->requestAction(array('controller' => 'pages', 'action' => 'contact'), array('bare'));
			 	* A u funkciji koja handluje gresku tj. ona koja je zaduzena za prikaz greske neophodne je:
				* $this->autoLayout = true;
				* Po Defaultu requestAction radi samo rendering elementa
				* return $this->render('contact', 'default');
 				*/
				$this->cakeError('error404');
				exit(0);
			}
			
			$activeMenuitem = $this->Menuitem->find('first', array(
				'conditions' => array(
					Menuitem::T_ContentId => $id,
					Menuitem::T_ContentType => 'PAGE'
				))
			);
			$page = $this->Page->find('first', array('conditions' => array('Page.id' => $id)));
			
			$this->set('title_for_layout', "Inkoplan :: {$activeMenuitem[PAGE][Page::Title]}");
			$this->set('active_main_menuitem', $activeMenuitem['Menuitem']['id']);
			$this->set('page', $page);
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
						),
						SLUG => array(
							'classname' => SLUG,
							'foreignKey' => Slug::Fk,
							'conditions' => array(
								Slug::T_Type => Slug::TypeMain,
								Slug::T_Controller => 'pages',
								Slug::T_Action => 'view'
							)
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
				$pageNameIsChanged =  $this->data[PAGE][Page::Title] != $pageData[PAGE][Page::Title];
				$pageSlugIsChanged = $pageData[SLUG][Slug::Url] != 'pages/'.Slug::createSlugUrl($this->data[SLUG][Slug::Url]).'/%';
				
				$dataSource = $this->Page->getDataSource();
				$dataSource->begin($this->Page);
			
				$this->Page->id = $id;
				$success = $this->Page->save($this->data[PAGE]);

				if ($success && ($pageNameIsChanged || $pageSlugIsChanged)) {
					$newUrl = $pageNameIsChanged ? $this->data[PAGE][Page::Title] : $this->data[SLUG][Slug::Url];
					$success = $this->Slug->editSlug($this->data[SLUG][ID], $newUrl);
				}
				
				if ($pageNameIsChanged && $success) {
					if ($menuitemTitleIsSync) {
						$this->Menuitem->id = $pageData[MENUITEM][ID];
						$success = $this->Menuitem->save(array(Menuitem::Title => $this->data[PAGE][Page::Title]));
					}
				}

				if ($success) {
					$dataSource->commit($this->Page);
					$message = __('Page is successfully saved', true); 
				} else {
					$dataSource->rollback($this->Page);
					$message = __('Page is not successfully saved', true);
				}
				
				$returnData = array(
					'success' => $success ? true : false,
					'message' => $message,
					'errors' => am($this->Page->validationErrors, $this->Slug->validationErrors, $this->Menuitem->validationErrors)
				);
			}
			$this->returnJsonData(am($returnData, $this->__getPageData($id)));
		}
		
		private function __getPageData($pageId) {
			$this->Page->bindModel(array(
				'hasMany' => array(
					MENUITEM => array(
						'className' => 'Menuitem',
						'foreignKey' => Menuitem::ContentId,
						'conditions' => array(Menuitem::T_ContentType => 'PAGE')
					)
				),
				'hasOne' => array(
					SLUG => array(
						'classname' => SLUG,
						'foreignKey' => Slug::Fk,
						'conditions' => array(
							Slug::T_Type => Slug::TypeMain,
							Slug::T_Controller => 'pages',
							Slug::T_Action => 'view'
						)
					)
				)
			));
			
			
			$pageData = $this->Page->find('first', array(
				'conditions' => array(
					Page::Id => $pageId
				)	
			));
			$pageData[SLUG][Slug::Url] = Slug::parseSlugUrl($pageData[SLUG][Slug::Url]);
			return array(
				PAGE => $pageData[PAGE], 
				SLUG => $pageData[SLUG] 
			);
		}
		
		public function test_search() {
			$url ="pages\/milod-1";
			$matches = array();
			preg_match("/({$url})\-([0-9]*)\/\%/", "pages/milod-1-1/%", $matches);
			debug($matches);
			exit();
			debug($matches);
			debug($search_result);
			debug(strpos($search_result[0][PAGE][Page::Content], 'osnovan'));
		}
	}