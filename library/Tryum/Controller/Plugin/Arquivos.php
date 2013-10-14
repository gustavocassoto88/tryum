<?php

/**
 * Cria o plugin do arquivos
 * 
 * @name Tryum_Controller_Plugin_arquivos
 */
class Tryum_Controller_Plugin_Arquivos extends Zend_Controller_Plugin_Abstract {
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
		
		// Busca o basepath
		$options = Zend_Registry::get("config");
		$basePath = $options->tryum->config->basepath;
		
		// Busca os arquivos css do arquivos
		$config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/arquivos.ini", "css");
		
		// Percorre os arquivos
		$module = (string)$request->getModuleName();
		foreach($config->$module as $file) {
			if(strpos($file, "http://") === FALSE) {
				// Troca a palavra chave do modulo
				$file = str_replace(":module", $module, $file);
				
				// Adiciona o arquivo
				$view->headLink()->appendStylesheet($basePath . "/" . $file);
			}
			else {
				// Adiciona o arquivo
				$view->headLink()->appendStylesheet($file);
			}
		}
		
		// Busca os arquivos js do arquivos
		$config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/arquivos.ini", "js");
		
		// Percorre os arquivos js
		$module = (string)$request->getModuleName();
		foreach($config->$module as $file) {
			if(strpos($file, "http://") === FALSE) {
				// Troca a palavra chave do modulo
				$file = str_replace(":module", $module, $file);
				
				// Adiciona o arquivo
				$view->headScript()->appendFile($basePath . "/" . $file);
			}
			else {
				// Adiciona o arquivo
				$view->headScript()->appendFile($file);
			}
		}
		
		// Busca os titulos do arquivos
		$config = new Zend_Config_Ini(APPLICATION_PATH . "/configs/arquivos.ini", "title");
		
		// Percorre os arquivos js
		$module = (string)$request->getModuleName();
		$view->headTitle($config->$module)->setSeparator(" :: ");
	}
}
