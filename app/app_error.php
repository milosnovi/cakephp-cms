<?php
class AppError extends ErrorHandler {
	
	function error404($params) {
		$referer_link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$this->controller->set('bad_link', $_SERVER['REQUEST_URI']);
		$this->controller->set('referer_link', $referer_link);
		$this->_outputMessage('error404');
	}
	
	function missingAction($params) {
		$referer_link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$this->controller->set('bad_link', $_SERVER['REQUEST_URI']);
		$this->controller->set('referer_link', $referer_link);
		$this->_outputMessage('error404');
	}
	
	function missingController($params) {
		$referer_link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$this->controller->set('bad_link', $_SERVER['REQUEST_URI']);
		$this->controller->set('referer_link', $referer_link);
		$this->_outputMessage('error404');
	}
}	
?>
