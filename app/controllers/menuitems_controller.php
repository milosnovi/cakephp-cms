<?php
class MenuitemsController extends AppController {
	var $uses = array(PAGE, MENUITEM);
	
	public function admin_get_nodes() {
/*		$this->Menuitem->id = 36;
		$success = $this->Menuitem->delete();
		
*/		$finalArray = array();
		$mainMenu = $this->Menuitem->generateExtMenuitems(1, Menuitem::TypeMain);
		$finalArray[] = array(
			'id' => 1,
		'menu_type' => Menuitem::TypeMain,
			'text' => 'Main menu',
			'rootMenu' => true,
			'content_type' => 'ROOT',
			'leaf' => false,
			'allowDrag' => false,
			'expanded' => true,
			'children' => $mainMenu 
		);
		
		$sideMenuitems = $this->Menuitem->generateExtMenuitems(2, Menuitem::TypeSide);

		$finalArray[] = array(
			'id' => 6,
			'menu_type' => Menuitem::TypeSide, 
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
		//debug($this->params['form']['dropPoint']);
		$dropPoint			= $this->params['form']['dropPoint'];  			
		
		$dropPageId 		= isset($this->params['form']['dropPageId']) ?  $this->params['form']['dropPageId'] : false;
		$dropMenuitemId 	= isset($this->params['form']['dropMenuitemId']) ? $this->params['form']['dropMenuitemId'] : false;
		$dropMenuType 		= isset($this->params['form']['dropMenuType']) ? $this->params['form']['dropMenuType'] : false;
		$dropParentId 		= isset($this->params['form']['dropParentId']) ? $this->params['form']['dropParentId'] : false;
		 
		$targetPageId 		= isset($this->params['form']['targetPageId']) ? $this->params['form']['targetPageId'] : false;
		$targetMenuitemId 	= (isset($this->params['form']['targetMenuitemId']) && ($this->params['form']['targetMenuitemId'] != 'Orphan_menu')) ? $this->params['form']['targetMenuitemId'] : false;
		$targerMenuType		= isset($this->params['form']['targetMenuType']) ? $this->params['form']['targetMenuType'] : false;
		$targerParentId 	= isset($this->params['form']['targetParentId']) ? $this->params['form']['targetParentId'] : false;

		$dropAndTargetBelongsToSameMenu = $targerMenuType == $dropMenuType;
		$dropAndTargetAreRelatives = $targerParentId == $dropParentId;
		
		$targerParentId = ('append' == $dropPoint) ? $targetMenuitemId : $targerParentId;
		 
		$moveToOrphan = !$targetMenuitemId && !$targerMenuType && !empty($dropMenuitemId);
		if ($moveToOrphan) { // MOVE TO ORPHAN => DELETE MENUITEM
			$this->Menuitem->id = $dropMenuitemId;
			$success = $this->Menuitem->delete();
			$this->returnJsonData(array('success' => $success));
		} else {
			if ($targerMenuType && $dropMenuType) { //REORDER INSIDE THE MENU
				$this->Menuitem->id = $dropMenuitemId;
				$data[MENUITEM][Menuitem::Type] 	= $targerMenuType;
				$data[MENUITEM][Menuitem::ParantId] = $targerParentId;
				$success = $this->Menuitem->save($data);
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