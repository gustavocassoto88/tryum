<?php

/**
 * Cria o plugin do metas
 * 
 * @name Tryum_Controller_Plugin_Metas
 */
class Tryum_Controller_Plugin_Metas extends Zend_Controller_Plugin_Abstract {
	/**
	 * Método da classe
	 * 
	 * @name includejs
	 */
	public function preDispatch (Zend_Controller_Request_Abstract $request) {
		// Busca o view renderer
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper("viewRenderer");
		$viewRenderer->initView();
		$view = $viewRenderer->view;
		
		// Armazena o controller e o action
		$controller = (string)$request->getControllerName();
		$action = (string)$request->getActionName();
		
		// Busca as informações das metas
		try {
			$metas = new Zend_Config_Ini(APPLICATION_PATH . "/configs/metas.ini", $controller);
		}
		catch(Exception $e) {
			return FALSE;
		}
		
		// Verifica se existe a sessão das metas
		if($metas->$action !== NULL) {
			// Adiciona as meta tags
			$view->headTitle($metas->$action->title);
			$view->headMeta()->setName("description", $metas->$action->description);
			$view->headMeta()->setName("keywords", $metas->$action->keywords);
		}
	}
}
