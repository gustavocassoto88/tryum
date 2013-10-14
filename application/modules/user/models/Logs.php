<?php

/**
 * Modelo da tabela de logs
 *
 * @name user_Model_Logs
 * @see Zend_Db_Table_Abstract
 */
class user_Model_Logs extends Tryum_Db_Table {
	/**
	 * Armazena o nome da tabela
	 *
	 * @access protected
	 * @name $_name
	 * @var string
	 */
	protected $_name = "logs";

	/**
	 * Armazena o nome do campo da tabela primaria
	 *
	 * @access protected
	 * @name $_primary
	 * @var string
	 */
	protected $_primary = "idlog";
	
	/**
	 * Inicializa o model
	 * 
	 * @name init
	 */
	public function init() {
		//
		$this->setCampo("idusuario", "Usuário");
		$this->setCampo("tabela", "Tabela");
		$this->setCampo("json", "JSON");
		$this->setCampo("acao_executada", "Ação Executada");
		$this->setCampo("data_execucao", "Data de Execução");
		
		//
		$this->setAutocomplete("idusuario", "user_Model_Usuarios");
		
		//
		$this->setDescription("nome");
		
		//
		parent::init();
	}
}

