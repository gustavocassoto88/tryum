<?php

/**
 * Controlador dos menus
 *
 * @name user_MenusitensController
 */
class user_MenusitensController extends Tryum_Controller_Action {
	/**
	 * Armazena o model padrão da tela
	 *
	 * @access protected
	 * @name $_model
	 * @var user_Model_Menusitens
	 */
	protected $_model = NULL;

	/**
	 *
	 */
	public function init() {
		// Inicializa o model da tela
		$this->_model = new user_Model_Menusitens();
		
		//
		parent::init();
	}
}
