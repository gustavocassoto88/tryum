<?php

/**
 * Classe que trata o layout por modulos
 *  
 * @name Tryum_View_ModularLayout
 */
class Tryum_View_ModularLayout extends Zend_Controller_Plugin_Abstract {
	/**
	 * Remove a rota padrão
	 * @param Zend_Controller_Request_Abstract $request Dados da requisição
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		Zend_Layout::getMvcInstance()->setLayout($request->getModuleName());
	}
}