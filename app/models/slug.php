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
		const F_MainSlugSiteId = 'MainSlug.site_id';
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
			return strtolower($url);
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
		
		private function __generateNextIndex($url) {
			$slugsWithSameValue = ($this->find('all', array(
				'conditions' => array(
					Slug::T_Url . " REGEXP ". "'$url(/\%|-[[:alnum:]]/\%)'",
					Slug::T_Type => array(Slug::TypeMain, Slug::TypeCanonical)
				),
				'order' => array('Slug.modified DESC')
			)));
//debug($slugsWithSameValue);
			if (!empty($slugsWithSameValue)) {
				$urlValues = Set::extract($slugsWithSameValue, '{n}.'.Slug::T_Url);
				if (in_array("$url/%", $urlValues)) {
					$lastModifed = $slugsWithSameValue[0][SLUG][Slug::Url];
					$searchable_url = str_replace('/','\/', $url);
					preg_match("/({$searchable_url})\-([0-9]*)\/\%/", $lastModifed, $matches);
//					debug($matches);
					$lastNumber = isset($matches[2])? $matches[2] : 0;
					$lastNumber++;
					$url = sprintf("%s-%s", $url, $lastNumber);
				}
			}
			return "$url/%";
		}
		
/*		KREIRANJE								
		Ispitaj da li takav vec postoji, ako postoji i 301 je onda samo preuzmi url				
		Dodaj index ako postoji						
		Sacuvaj										
		AKCIJE: CREATE PAGE							
*/
		public function createSlug($model, $fk, $url, $type = Slug::TypeMain) {
			$url = Slug::createSlugUrl($url);
			$url_params = Slug::__convertModel2ControllerAction($model);
			
			$url = $this->__generateNextIndex("{$url_params[Slug::Controller]}/$url");
			$existingSlug = $this->find('first', array(
				'conditions' => array(
					Slug::T_Url => "$url"
				)	
			));

			$this->id = null;
			if (!empty($existingSlug) && (Slug::Type301 == $existingSlug[SLUG][Slug::Type])) {
				$this->id = $existingSlug[SLUG][ID];
			}

			$success = $this->save(array(
				Slug::Controller => $url_params[Slug::Controller],
				Slug::Action => $url_params[Slug::Action],
				Slug::Fk => $fk,
				Slug::Type => $type,
				Slug::Url => strtolower($url)
			));
			return $success;
		}
		
/*
		EDITOVANJE
			Konvertuj sve stare slagove na 301 (da nisu canonical)
			Da li takav vec postoji, a da ne pripada istom contentu.
				Postoji, da li je 301
					onda ga preuzmi
					dodaj odgovorauci index na kraj slug url-a
				Postoji i pripada istom contentu
	 				promeniti tip sluga koji je nadjen na main EXIT
			Snimi novo kreirani
														
														
		AKCIJE: EDIT PAGE TITLE
				EDIT SLUG VALUE
*/		
		public function editSlug($id, $url) {
			$slugData = $this->find('first', array('conditions' => array(ID => $id)));

			$controller = $slugData[SLUG][Slug::Controller];
			$action = $slugData[SLUG][Slug::Action];
			$fk = $slugData[SLUG][Slug::Fk];
			
			$updateAllSlugsto301 = "
				Update slugs set type = '301'
				where controller = '$controller'
				and action = '$action' 
				and fk = $fk
			";
			$success = $this->query($updateAllSlugsto301);
			
			if (!$success) {
				return false;
			}
			
			return $this->createSlug(PAGE, $fk, $url);
		}
		
		private static function __convertModel2ControllerAction($model) {
			switch ($model) {
				case PAGE: {
					$controller = 'pages';
					$action = 'view';
					break;
				}
			}
			return array(Slug::Controller => $controller, Slug::Action => $action);
		}
		
		public static function makeRelativeLink($data, $model, $fk) {
			if (!empty($data[Slug::Url])) {
				$link = array(Slug::parseSlugUrl($data[Slug::Url]));
			} else {
				$link = am(self::__convertModel2ControllerAction($model), array($fk));
				
			}
			$link = implode('/', $link);
			return '/'.$link;
		}
	}
?>








