<?php
class Page extends AppModel {
	const Id = 'Page.id';
	
	const Title = 'title';
	const Content = 'content';
	
	const T_Title = 'Page.title';
	const T_Content = 'Page.content';
	
	public function getOrphanPages() {
		$this->bindModel(array(
			'hasMany' => array(
				MENUITEM => array(
					'className' => MENUITEM,
					'foreignKey' => Menuitem::ContentId,
					'conditions' => array(Menuitem::ContentType => PAGE)
				)
			)
		));
		$allPages = $this->find('all', array(
			'order' => 'Page.modified ASC'
		));
		$orphanPages = array();
		foreach ($allPages as $page) {
			if (empty($page[MENUITEM])) {
				$orphanPages[] = array(
					'content_type' => PAGE,
					'content_id' => $page[PAGE][ID],
					'text' => $page[PAGE][Page::Title],
					'orhpan_page' => true,
					'leaf' => true,
					'cls' => 'folder',
					'iconCls' => 'menu-page-node-icon'
				);
			}
		}
		return $orphanPages;
	}
}