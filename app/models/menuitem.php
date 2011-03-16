<?php
class Menuitem extends AppModel {
	const Id = 'Menuitem.id';

	const Title = 'title';
	const Type = 'type';
	const ContentType = 'content_type';
	const ParantId = 'parent_id';
	const Lft = 'lft';
	const Rght = 'rght';
	const ContentId = 'content_id';
	const Url = 'url';
	const Visible = 'visible';
	
	const T_Title = 'Menuitem.title';
	const T_Type = 'Menuitem.type';
	const T_ContentType = 'Menuitem.content_type';
	const T_ParantId = 'Menuitem.parent_id';
	const T_Lft = 'Menuitem.lft';
	const T_Rght = 'Menuitem.rght';
	const T_ContentId = 'Menuitem.content_id';
	const T_Url = 'Menuitem.url';
	const T_Visible = 'Menuitem.visible';
	
	const TypeMain = 'MAIN';
	const TypeSide = 'SIDE';
	
	const A_Children = 'Children';
	
	const A_MainMenu = 'mainMenu';
	const A_T_MainMenu = 'Menuitem.mainMenu';
	
	var $actsAs = array('Tree');
 
	var $belongsTo = array(
		'Page' => array(
			'className' => 'Page',
			'foreignKey' => Menuitem::ContentId,
			'conditions' => array(Menuitem::T_ContentType => 'PAGE')
		)
	);
	
	public function getMenuChildren($menuid) {
		$sideMenuItems = $this->children($menuid, true);
	
		$levelItems = array();
		foreach($sideMenuItems as $index => $sideMenuItem) {
			if (1 < ($sideMenuItem['Menuitem'][Menuitem::Rght] - $sideMenuItem['Menuitem'][Menuitem::Lft])) {
				$levelItems[$index] = am($sideMenuItem,
					array(Menuitem::A_Children => $this->getMenuChildren($sideMenuItem['Menuitem']['id']))
				);
			} else {
				$levelItems[$index] = $sideMenuItem;		
			}                                               
		}
		return $levelItems;
	}
	
	public function generateExtMenuitems($menuItemId, $menuType = Menuitem::TypeMain) {
		$sideMenuItems = $this->children($menuItemId, true);
		$finalMenuitemsArray = array();
		foreach($sideMenuItems as $index => $menuitem) {
			$pom = $menuitem['Menuitem'][Menuitem::Rght] - $menuitem['Menuitem'][Menuitem::Lft];
			$isLeaf = (1 == $pom);
			$finalMenuitemsArray[$index] = array(
				'id' => $menuitem['Menuitem']['id'],
				'menu_type' => $menuType,
				'children' => array(),
				'content_type' => $menuitem[MENUITEM][Menuitem::ContentType],
				'content_id' => $menuitem[MENUITEM][Menuitem::ContentId],
				'parent_id' => $menuitem[MENUITEM][Menuitem::ParantId],
				'url' => $menuitem[MENUITEM][Menuitem::Url],
				'text' => $menuitem[MENUITEM][Menuitem::Title],
				/*'leaf' => $isLeaf,*/
//				'leaf' => false,
//				'expanded' => $isLeaf ? true : false,				
				'expanded' => true,				
				'cls' => 'folder',
				'iconCls' => 'menu-page-node-icon'
			);
			
			if (!$isLeaf) {
				$finalMenuitemsArray[$index]['children'] = $this->generateExtMenuitems($menuitem['Menuitem']['id']);		
			}
		}
		return $finalMenuitemsArray;
	}
	
}