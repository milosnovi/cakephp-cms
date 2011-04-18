<?php
	class PagesController extends AppController {
		var $uses = array('Page', 'Menuitem');
	
		public function home() {
			$this->set('title_for_layout', 'Inkoplan d.o.o.');
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
					Page::Id => 4
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

			if (isset($this->data[MENUITEM][Menuitem::A_MainMenu])) {
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
					PAGE => $pageData
				));
			} else {
				$this->returnJsonData(array(
					'success' => false,
					'message' => __('ERROR ERROR!!!!!')
				));
			}
		}
		
		public function contact() {
			$this->set('title_for_layout', 'Inkoplan d.o.o. :: Contact');
			$menuitem = $this->Menuitem->find('first', array('conditions' => array('menuitem.url' =>'/contact')));
			$this->set('active_main_menuitem', $menuitem['Menuitem']['id']);
			if ($this->RequestHandler->isPost()) {
				
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
			if (RequestHandlerComponent::isPost()) {
				$this->Page->id = $this->data[PAGE][ID];
				$this->Page->save(array(
					Page::Content => $this->data[PAGE][Page::Content],
					Page::Title => $this->data[PAGE][Page::Title]
				));
				$id = $this->Page->id;
			}
			$pageData = $this->__getPageData($id);
			$this->returnJsonData(array(
				'success' => true,
				PAGE => $pageData['Page']
			));
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
	}