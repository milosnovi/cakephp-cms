<?php
	class Slug extends AppModel {
		const Id = 'Slug.id';
		
		const Url = 'url';
		const Type = 'type';
		const Controller = 'controller';
		const Action = 'action';
		const Fk = 'fk';
		
		const T_Url = 'Slug.url';
		const T_Type = 'Slug.type';
		const T_Controller = 'Slug.controller';
		const T_Action = 'Slug.action';
		const T_Fk = 'Slug.fk';
		
		const TypeMain = 'MAIN';
		const TypeCanonical = 'CANONICAL';
		const Type301 = '301';
		
		const F_MainSlug = 'MainSlug';
		const F_MainSlugType = 'MainSlug.type';
		const F_MainSlugController = 'MainSlug.controller';
		const F_MainSlugAction = 'MainSlug.action';
		const F_MainSlugFk = 'MainSlug.fk';

		public static function parseSlugUrl($url) {
			return preg_replace('/\/%$/', '', $url);
		}
		
		public static function createSlugUrl($url) {
			$url = preg_replace('/[\s]/', '-', $url);
			$url = preg_replace('/[\/]+/', '-', $url); // Change "/" with '-'
			$url = preg_replace('/[^a-zA-Z0-9-]/', '', $url); // Change non word chars with empty
			$url = preg_replace('/-{2,}/', '-', $url); // Change multiple '-' into '-'
			$url = trim($url, '-');
			return $url;
		}
		
		public static function getSlugPerUrl($url) {
			$modelSlug = new Slug();
			$slug_data = $modelSlug->find('all', array(
				'fields' => array(SLUG.'.*', self::F_MainSlug.'.*'), 
				'joins' => array(
					array(
						'table' => 'slugs',
						'alias' => self::F_MainSlug,
						'type' => 'left',
						'conditions' => array(
							self::F_MainSlugType => self::TypeMain,
							sprintf('%s = %s', self::T_Controller, self::F_MainSlugController),
							sprintf('%s = %s', self::T_Action, self::F_MainSlugAction),
							sprintf('%s = %s', self::T_Fk, self::F_MainSlugFk)
						)
					)
				),
				'conditions' => array("'{$url}/' LIKE Slug.url")
			));
			$slugData = !empty($slug_data) ? $slug_data[0] : false; 
			return 	$slugData;	
		}
		
		public static function getSlugsPerRawUrl($controller, $action, $fk) {
			$modelSlug = new Slug();
			$slug_data = $modelSlug->find('first', array(
				'conditions' => array(
					Slug::T_Controller => $controller,
					Slug::T_Action => $action,
					Slug::T_Fk => $fk,
					Slug::T_Type => Slug::TypeMain
				)
			));
			return $slug_data;
		}
		
		public function setSlug($model, $fk, $url) {
			$url = Slug::createSlugUrl($url);
			 switch ($model){
				case PAGE: {
					$controller = 'pages';
					$action = 'view';
					break;
				}
			}
			
			$success = $this->save(array(
				Slug::Controller => $controller,
				Slug::Action => $action,
				Slug::Fk => $fk,
				Slug::Url => "$url/%", 
				Slug::Type => Slug::TypeMain
			));
			return $success;
		}
	}
?>
