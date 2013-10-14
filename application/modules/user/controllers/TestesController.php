<?php

/**
 * Controlador dos testes
 *
 * @name user_TestesController
 */
class user_TestesController extends Tryum_Controller_Action {
	/**
	 * Armazena o model padrão da tela
	 *
	 * @access protected
	 * @name $_model
	 * @var user_Model_Testes
	 */
	protected $_model = NULL;

	/**
	 * Inicializa o controller
	 * 
	 * @name init
	 */
	public function init() {
		// Inicializa o model da tela
		$this->_model = new user_Model_Testes();
		
		// Continua o carregamento do controlador
		parent::init();
	}
}
