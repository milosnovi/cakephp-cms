<?php
class MenuitemsController extends AppController {
	var $uses = array(PAGE, MENUITEM);
	
	public function admin_get_nodes() {
/*		$this->Menuitem->id = 36;
		$success = $this->Menuitem->delete();
		
*/		$finalArray = array();
		$mainMenu = $this->Menuitem->generateExtMenuitems(1, Menuitem::TypeMain);
		$finalArray[] = array(
			'id' => 'Main_menu',
			'text' => 'Main menu',
			'rootMenu' => true,
			'content_type' => 'ROOT',
			'leaf' => false,
			'allowDrag' => false,
			'expanded' => true,
			'children' => $mainMenu 
		);
		
		$sideMenuitems = $this->Menuitem->generateExtMenuitems(6, Menuitem::TypeSide);

		$finalArray[] = array(
			'id' => 'Side_menu',
			'text' => 'Side menu',
			'rootMenu' => true,
			'content_type' => 'ROOT',
			'leaf' => false,
			'allowDrag' => false,
			'expanded' => true,
			'children' => $sideMenuitems 
		);

		$finalArray[] = array(
			'id' => 'Orphan_menu',
			'text' => 'Orphan pages',
			'rootMenu' => true,
			'content_type' => 'ROOT',
			'leaf' => false,
			'allowDrag' => false,
			'expanded' => true,
			'children' => $this->Page->getOrphanPages() 
		);
		$this->returnJsonData($finalArray);
	}
	
	public function admin_delete_node($menuitemId) {
			if (!$menuitemId) {
				$this->returnJsonData(array(
					'success' => false,
					'message' => __('Los Id menuitem-a', true) 
				));		
			}
			$this->Menuitem->id = $menuitemId;
			$success = $this->Menuitem->delete();
			$this->returnJsonData(array(
				'success' => $success,
				'message' => 'supecool' 
			));
	}
	
	public function admin_reorder_nodes() {
		$this->data = $this->params['form'];
		debug($this->params['form']);
		
		$dropPageId 		= isset($this->data['dropPageId']) ?  $this->data['dropPageId'] : false;
		$dropMenuitemId 	= isset($this->data['dropMenuitemId']) ? $this->data['dropMenuitemId'] : false;
		$dropMenuType 		= isset($this->data['dropMenuType']) ? $this->data['dropMenuType'] : false;
		$dropParentId 		= isset($this->data['dropParentId']) ? $this->data['dropParentId'] : false;
		 
		$targetPageId 		= isset($this->data['targetPageId']) ? $this->data['targetPageId'] : false;
		$targetMenuitemId 	= isset($this->data['targetMenuitemId']) ? $this->data['targetMenuitemId'] : false;
		$targerMenuType		= isset($this->data['targetMenuType']) ? $this->data['targetMenuType'] : false;
		$targerParentId 	= isset($this->data['targetParentId']) ? $this->data['targetParentId'] : false;

		$dropAndTargetBelongsToSameMenu = $targerMenuType == $dropMenuType;
		$dropAndTargetAreRelatives = $targerParentId == $dropParentId;
		
		$moveToOrphan = !$targetMenuitemId && !$targerMenuType && !empty($dropMenuitemId);
		debug($moveToOrphan); 
		if ($moveToOrphan) { // MOVE TO ORPHAN => DELETE MENUITEM
			$this->Menuitem->id = $dropMenuitemId;
			$success = $this->Menuitem->delete();
			$this->returnJsonData(array('success' => $success));
		} else {
			if (!$dropAndTargetBelongsToSameMenu && $targerMenuType && $dropMenuType) { //REORDER INSIDE THE MENU
				$this->Menuitem->id = $dropMenuitemId;
				$data[MENUITEM][Menuitem::Type] 	= $targerMenuType;
				$data[MENUITEM][Menuitem::ParantId] = $targerParentId;
				$success = $this->Menuitem->save($data);
				debug($success);
				if(!$success) {
					$this->returnJsonData(array('success' => false));
				}
			}
	
			if (!$dropMenuitemId && !empty($targetMenuitemId)) { // MOVE FROM ORPHAN TO MENU
				$pageData = $this->Page->find('first', array(
					'conditions' => array(Page::Id => $dropPageId),
					'recursive' => -1
				));
				
				$data[MENUITEM] = array(
					Menuitem::ParantId => $targerParentId,
					Menuitem::Title => $pageData[PAGE][Page::Title],
					Menuitem::Type => $targerMenuType,
					Menuitem::ContentType => PAGE,
					Menuitem::ContentId => $pageData[PAGE][ID],
					Menuitem::Visible => 1,
					Menuitem::Url => "/pages/view/{$pageData[PAGE][ID]}"
				);
	
				$success = $this->Menuitem->save($data[MENUITEM]);
				if(!$success) {
					$this->returnJsonData(array('success' => false));
				} else {
					$dropMenuitemId = $this->Menuitem->id;
				}
			}
			
			$menuitems = $this->Menuitem->find('all', array(
				'conditions' => array(Menuitem::T_ParantId => $targerParentId),
				'order' => Menuitem::T_Lft . ' ASC',
				'fields' => Menuitem::Id,
				'recursive' => -1
			));
			$menuitemIds = Set::extract($menuitems, '{n}.' . Menuitem::Id);			
			
			$sourcePosition = array_search($dropMenuitemId, $menuitemIds);
			$targetPosition = array_search($targetMenuitemId, $menuitemIds);
	
			$moveup = $sourcePosition > $targetPosition;
	
			$this->Menuitem->id = $dropMenuitemId;
			if ($moveup) {
				if (($sourcePosition - $targetPosition) > 0) {
					$this->Menuitem->moveUp($this->Menuitem->id, abs($sourcePosition - $targetPosition));
				}
			} else {
				if (($targetPosition - $sourcePosition) > 0) {
					$this->Menuitem->moveDown($this->Menuitem->id, abs($targetPosition - $sourcePosition));
				}
			}
		}
		$this->returnJsonData(array('success' => true));
	}
}