<?php

/**
 * Cria o plugin do customjs
 * 
 * @name Tryum_Controller_Plugin_Customjs
 */
class Tryum_Controller_Plugin_Customjs extends Zend_Controller_Plugin_Abstract {
	/**
	 * Método da classe
	 * 
	 * @name includejs
	 */
	public function preDispatch (Zend_Controller_Request_Abstract $request) {
		// Recupera a requisição
		//$request = Zend_Controller_Front::getInstance()->getRequest();
		
		// Busca o basepath
		$options = Zend_Registry::get("config");
		$basePath = $options->tryum->config->basepath;
		
		// Monta o caminho do 
		$js_filename = $request->getModuleName() . "/" . $request->getControllerName() . "/" . $request->getActionName() . ".js";
		
		// 
		$local_js_file = APPLICATION_PATH . "/../arquivos/application/js/" . $js_filename;
		$http_js_file = $basePath . "/arquivos/application/js/" . $js_filename;
		
		// Verifica se o js da ação existe
		$string = "";
		if(file_exists($local_js_file)) {
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper("viewRenderer");
			$viewRenderer->initView();
			$view = $viewRenderer->view;
			
			$view->headScript()
				->appendFile($http_js_file);
		}
	}
}
