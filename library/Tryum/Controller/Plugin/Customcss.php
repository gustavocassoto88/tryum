<?php

/**
 * Cria o plugin do customcss
 * 
 * @name Tryum_Controller_Plugin_Customcss
 */
class Tryum_Controller_Plugin_Customcss extends Zend_Controller_Plugin_Abstract {
	/**
	 * Método da classe
	 * 
	 * @name includejs
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		// Recupera a requisição
		//$request = Zend_Controller_Front::getInstance()->getRequest();
		
		// Busca o basepath
		$options = Zend_Registry::get("config");
		$basePath = $options->tryum->config->basepath;
		
		// Monta o caminho do 
		$js_filename = $request->getModuleName() . "/" . $request->getControllerName() . "/" . $request->getActionName() . ".css";
		
		// 
		$local_css_file = APPLICATION_PATH . "/../arquivos/application/css/" . $js_filename;
		$http_css_file = $basePath . "/arquivos/application/css/" . $js_filename;
		
		// Verifica se o js da ação existe
		$string = "";
		if(file_exists($local_css_file)) {
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper("viewRenderer");
			$viewRenderer->initView();
			$view = $viewRenderer->view;
			
			$view->headLink()->appendStylesheet($http_css_file);
		}
	}
}