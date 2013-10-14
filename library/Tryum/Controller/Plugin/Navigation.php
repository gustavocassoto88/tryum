<?php

/**
 * Cria o plugin do arquivos
 *
 * @name Tryum_Controller_Plugin_Navigation
 */
class Tryum_Controller_Plugin_Navigation extends Zend_Controller_Plugin_Abstract {
	/**
	 * Método da classe
	 * 
	 * @name includejs
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		// Busca a sessão
		$session = new Zend_Session_Namespace("login");
		
		// Busca o modulo
		$module = $request->getModuleName();
		if(empty($module)) {
			$module = "default";
		}
		
		// Busca o view
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper("viewRenderer");
		$viewRenderer->initView();
		$view = $viewRenderer->view;
		
		// Verifica se é o user
		if($module == "user") {
			// Verifica se está logado
			if(!isset($session->logged_usuario)) {
				return FALSE;
			}
			
			// Cria o filtro user
			if($session->logged_usuario['idperfil'] > 0) {
				$where = "
					WHERE 
						T01.idperfil <= " . $session->logged_usuario['idperfil'] . "
				";
			}
			else {
				$where = "
					WHERE
						1 = 2
				";
			}
		
			// Cria o objeto de navegação
			$navigation = new Zend_Navigation();
			
			// Busca o objeto de conexão com o banco
			$db = Zend_Registry::get("db");
			
			// Busca os itens do menu do usuario logado
			$sql = "
				SELECT 
					T01.descricao as \"funcionalidade\",
					T01.modulo as \"modulo\",
					T01.controlador as \"controlador\",
					T01.acao as \"acao\",
					T02.descricao as \"categoria\"
				FROM 
					menu_itens T01
					INNER JOIN menu_categorias T02 USING(idcategoria)
				" . $where . "
				ORDER BY
					T02.descricao,
					T01.descricao
			";
			
			$result = $db->query($sql);
			$list = $result->fetchAll();
			
			// Percorre os itens da consulta
			$category_name = "";
			$category = "";
			$i = 0;
			foreach($list as $row) {
				
				if($row['controlador'] == $request->getControllerName()) {
					$view->openedController = $row['categoria'];
				}
				
				// Cria a categoria
				if($category_name != $row['categoria']) {
					$category_name = $row['categoria'];
					
					// Adiciona a categoria no menu
					if(is_array($category)) {
						$navigation->addPage($category);
					}
					
					// Cria uma nova categoria
					$category = array(
						'label' => $row['categoria'],
						'uri'	=> "#",
						'pages'	=> array()
					);
				}
				
				// Adiciona a pagina
				$category['pages'][$i++] = array(
					'label'			=> $row['funcionalidade'],
					'controller'	=> $row['controlador'],
					'action'		=> $row['acao'],
					'module'		=> $row['modulo']
				);
			}
			
			// Adiciona o ultimo laço
			$navigation->addPage($category);
			
			// Adiciona a navegação ao view
			$view->navigation($navigation);
			$view->navigation()->menu()->setUlClass('left-menu-nav');
		}
		
		// Assina a acao/controller e modulo para a view
		$view->currentController = $request->getControllerName();
		$view->currentAction = $request->getActionName();
		$view->currentModule = $request->getModuleName();
		
		// Verifica se existe navegação
//		if(file_exists(APPLICATION_PATH . '/configs/' . strtolower($module) . '_navigation.xml')) {
//			$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/' . strtolower($module) . '_navigation.xml', 'nav');
//			$navigation = new Zend_Navigation($config);
//			$view->navigation($navigation);
//		}
	}

}
