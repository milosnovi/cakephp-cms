<?php
class Page extends AppModel {
	const Id = 'Page.id';
	
	const Title = 'title';
	const Content = 'content';
	
	const T_Title = 'Page.title';
	const T_Content = 'Page.content';
	
	public $validate = array(
		self::Title => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Page title can not be empty'
			),
			'siteUnique' => array(
				'rule' => 'checkSiteUniqness',
				'field' => self::Title,
				'message' => 'Same value has already existed. Please try again'
			),
		)
	);
	
	public function checkSiteUniqness($data, $params) {
		$field = $params['field'];
		$value = $data[$field];
		
		$found = $this->find('all', array(
			'conditions' => array("{$this->name}.$field" => $value),
			'fields' => array($this->primaryKey)
		));
		$same = isset($this->id) && $found && (1 == count($found)) && ($found[0][$this->name][$this->primaryKey] == $this->id);
		return !$found || ($found && $same);
	}
	
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
		$allPages = $this->find('all', array('order' => 'Page.modified ASC'));
		$orphanPages = array();
		foreach ($allPages as $page) {
			if (empty($page[MENUITEM])) {
				$orphanPages[] = array(
					'content_type' => PAGE,
					'content_id' => $page[PAGE][ID],
					'text' => $page[PAGE][self::Title],
					'orphan_page' => true,
					'leaf' => true,
					'cls' => 'folder',
					'iconCls' => 'menu-page-node-icon'
				);
			}
		}
		return $orphanPages;
	}
}