<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */

$url = $this->getUrl();
$pathChunks = explode('/', $url);

if (!empty($pathChunks) && ($pathChunks[0] != 'admin')) {
	$slug = Slug::getSlugPerUrl($url);
	if (!empty($slug)) {
		$slugType = $slug[SLUG][Slug::Type];
		if (Slug::Type301 == $slugType) {
			$redirectUrl = Slug::parseSlugUrl($slug[Slug::F_MainSlug][Slug::Url]);
			Router::connect("/$url", array(
				'controller' => 'app',
				'action' => 'redirect301',
				$redirectUrl
			));
		} else {
			Router::connect("/$url", am(array(
				'controller' => $slug[SLUG][Slug::Controller],
				'action' => $slug[SLUG][Slug::Action],
				$slug[SLUG][Slug::Fk]
			), $slug));
		}
	}
}

Router::connect('/', array('controller' => 'pages', 'action' => 'home', 'home'));
Router::connect('/contact', array('controller' => 'pages', 'action' => 'contact'));
Router::connect('/report_bad_link', array('controller' => 'pages', 'action' => 'report_bad_link'));
Router::connect('/search/*', array('controller' => 'search', 'action' => 'index'));