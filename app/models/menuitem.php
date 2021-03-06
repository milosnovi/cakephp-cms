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
	
	const ContentTypePage = 'PAGE';
	const ContentTypeCustom = 'CUSTOM';
	const ContentTypeRoot = 'ROOT';
	
	var $actsAs = array('Tree');
 
	var $belongsTo = array(
		'Page' => array(
			'className' => 'Page',
			'foreignKey' => Menuitem::ContentId,
			'conditions' => array(Menuitem::T_ContentType => 'PAGE')
		)
	);
	
	public function getRootNodeId($menu_type) {
		$pageData = $this->find('first', array(
			'fields' => array(ID),
			'conditions' => array(
				self::T_ContentType => self::ContentTypeRoot,
				self::T_Type => $menu_type
			),
			'recursive' => -1
		));
		return $pageData[MENUITEM][ID];
	}
	
	public function getMenuChildren($menuid) {
		$sideMenuItems = $this->find('all', array(
			'fields' => array(MENUITEM.'.*', SLUG.'.*'),
			'joins' => array(
				array(
					'table' => 'slugs',
					'alias' => SLUG,
					'type' => 'left',
					'conditions' => array(
						Slug::T_Type => Slug::TypeMain,
						sprintf('%s = %s', Slug::T_Fk, self::T_ContentId),
						Slug::T_Controller => 'pages',
						Slug::T_Action => 'view'
					)
				)
			),
			'conditions' => array(Menuitem::ParantId => $menuid)
		));
		//debug($sideMenuItems);
		$levelItems = array();
		foreach($sideMenuItems as $index => $sideMenuItem) {
			$sideMenuItem[MENUITEM]['slug_url'] = isset($sideMenuItem[SLUG][Slug::Url]) ? "/".Slug::parseSlugUrl($sideMenuItem[SLUG][Slug::Url]) : $sideMenuItem[MENUITEM][Menuitem::Url];
			if (1 < ($sideMenuItem['Menuitem'][Menuitem::Rght] - $sideMenuItem['Menuitem'][Menuitem::Lft])) {
				$levelItems[$index] = am($sideMenuItem,	array(Menuitem::A_Children => $this->getMenuChildren($sideMenuItem['Menuitem']['id'])));
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
				'menuitem_title' => $menuitem[MENUITEM][Menuitem::Title],
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