<?php

/**
 * Controlador dos menus
 *
 * @name user_MenuscategoriasController
 */
class user_MenuscategoriasController extends Tryum_Controller_Action {
	/**
	 * Armazena o model padrão da tela
	 *
	 * @access protected
	 * @name $_model
	 * @var user_Model_Menuscategorias
	 */
	protected $_model = NULL;

	/**
	 *
	 */
	public function init() {
		// Inicializa o model da tela
		$this->_model = new user_Model_Menuscategorias();
		
		//
		parent::init();
	}
}
