<?php

/**
 * Modelo da tabela de usuarios
 *
 * @name user_Model_Usuarios
 * @see Zend_Db_Table_Abstract
 */
class user_Model_Usuarios extends Tryum_Db_Table {
	/**
	 * Armazena o nome da tabela
	 *
	 * @access protected
	 * @name $_name
	 * @var string
	 */
	protected $_name = "usuarios";

	/**
	 * Armazena o nome do campo da tabela primaria
	 *
	 * @access protected
	 * @name $_primary
	 * @var string
	 */
	protected $_primary = "idusuario";
	
	/**
	 * Inicializa o model
	 * 
	 * @name init
	 */
	public function init() {
		//
		$this->setCampo("nome", "Nome");
		$this->setCampo("email", "Email");
		$this->setCampo("login", "Login");
		$this->setCampo("senha", "Senha");
		$this->setCampo("idperfil", "Perfil");
		
		//
		$this->setModifier("senha", array('type'=>"password"));
		
		// 
		$this->setAutocomplete("idperfil", "user_Model_Perfis");
		
		// Seta a visibilidade
		$this->setVisibility("senha", TRUE, TRUE, FALSE, FALSE);
		
		//
		$this->setDescription("nome");
		
		//
		parent::init();
	}
}

