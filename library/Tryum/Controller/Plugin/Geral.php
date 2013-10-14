<?php

/**
 * Cria o plugin para carregar informações para todo o site
 *
 * @name Tryum_Controller_Plugin_Geral
 */
class Tryum_Controller_Plugin_Geral extends Zend_Controller_Plugin_Abstract {
	/**
	 * Método da classe
	 * 
	 * @name preDispatch
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		// Busca o view
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper("viewRenderer");
		$viewRenderer->initView();
		$view = $viewRenderer->view;
		
		if(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], ".")) == ".jpg") {
			return TRUE;
		}
		
		if($this->_request->getParam("module") == "user") {
			return TRUE;
		}
	}

}
