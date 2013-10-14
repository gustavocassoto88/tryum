<?php

/**
 * Modelo da tabela de perfis
 *
 * @name user_Model_Perfis
 * @see Zend_Db_Table_Abstract
 */
class user_Model_Perfis extends Tryum_Db_Table {
	/**
	 * Armazena o nome da tabela
	 *
	 * @access protected
	 * @name $_name
	 * @var string
	 */
	protected $_name = "perfis";

	/**
	 * Armazena o nome do campo da tabela primaria
	 *
	 * @access protected
	 * @name $_primary
	 * @var string
	 */
	protected $_primary = "idperfil";
	
	/**
	 * Inicializa o model
	 * 
	 * @name init
	 */
	public function init() {
		//
		$this->setCampo("descricao", "Descrição");
		
		//
		$this->setDescription("descricao");
		
		// Busca a sessão
		$session = new Zend_Session_Namespace("login");
		if($session->logged_usuario['idperfil'] < 99) {
			$select = $this->select();
			$select->where("idperfil < 99");
			$this->setQueryAutoComplete("default", $select);
		}
		
		//
		parent::init();
	}
}

