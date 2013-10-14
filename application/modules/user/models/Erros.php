<?php

/**
 * Modelo da tabela de erros
 *
 * @name user_Model_Erros
 * @see Zend_Db_Table_Abstract
 */
class user_Model_Erros extends Tryum_Db_Table {
	/**
	 * Armazena o nome da tabela
	 *
	 * @access protected
	 * @name $_name
	 * @var string
	 */
	protected $_name = "erros";

	/**
	 * Armazena o nome do campo da tabela primaria
	 *
	 * @access protected
	 * @name $_primary
	 * @var string
	 */
	protected $_primary = "iderro";
	
	/**
	 * Inicializa o model
	 * 
	 * @name init
	 */
	public function init() {
		//
		$this->setCampo("data_execucao", "Data Execução");
		$this->setCampo("mensagem", "Mensagem");
		$this->setCampo("parametros", "Parametros");
		$this->setCampo("idusuario", "Ação Usuário");
		$this->setCampo("trace", "Trace");
		
		//
		$this->setDescription("data_execucao");
		
		//
		parent::init();
	}
}

