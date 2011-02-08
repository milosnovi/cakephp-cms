<?php
	App::import('Model', 'Menuitem');
	
	class AppController extends Controller{
		var $helpers = array('Html', 'Form', 'Javascript', 'Ajax', 'Session');
		var $components = array('Session', 'RequestHandler', 'Cookie');
		
		function beforeRender() {
			$modelMenuitems = new menuitem();
			$rootMainMenu = $modelMenuitems->find('first', array(
				'conditions' => array(
					'Menuitem.type' => 'MAIN',
					'Menuitem.Content_type' => 'ROOT'
				),
				'recursive' => -1
			));
			$menuitemsData = $modelMenuitems->children($rootMainMenu['Menuitem']['id']);
//			debug($menuitemsData);
			$this->set('menuitemsData', $menuitemsData);
			
			if ($this->action == 'view') {
				$menuitem = $modelMenuitems->find('first', array(
					'conditions' => array(
						Menuitem::ContentType =>  'ROOT',
						'Menuitem.type' => 'SIDE'
					),
					'recursive' => -1
				));
				
				if (!empty($menuitem)) {
					$finalSideMenuitems = $this->Menuitem->getMenuChildren($menuitem['Menuitem']['id']);
					$this->set('sideMenuitems', $finalSideMenuitems);
				}
			}
		}
		
		 public function returnJsonData($data) {
		 	Configure::write('debug', 0);
			if (RequestHandlerComponent::isAjax()) {
				  header('Content-Type: text/javascript; charset="utf-8"');
				  $debugLogEntities = Configure::read('debugLogEntities');
			}
//			$debug = ob_get_contents();
//				if ($sendDebugData && $debug) {
//					if (Configure::read('log')) {
//						logDebug($debug);
//					}
//					$data[JSON_DEBUG] = (isset($data[JSON_DEBUG]) && !empty($data[JSON_DEBUG])) ? "{$data[JSON_DEBUG]}<br/>$debug" : $debug;
//					if (!CustomLog::useFlexiweb()) {
//						unset($data[JSON_DEBUG]);
//					}
//				}
//			ob_end_clean();
		  	echo json_encode($data); 
		  	exit(0);
		 } 
	}