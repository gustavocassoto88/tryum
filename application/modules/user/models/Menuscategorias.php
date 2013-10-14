<?php

/**
 * Modelo da tabela de menus_categorias
 *
 * @name user_Model_Menuscategorias
 * @see Zend_Db_Table_Abstract
 */
class user_Model_Menuscategorias extends Tryum_Db_Table {
	/**
	 * Armazena o nome da tabela
	 *
	 * @access protected
	 * @name $_name
	 * @var string
	 */
	protected $_name = "menu_categorias";

	/**
	 * Armazena o nome do campo da tabela primaria
	 *
	 * @access protected
	 * @name $_primary
	 * @var string
	 */
	protected $_primary = "idcategoria";
	
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
		
		//
		parent::init();
	}
}

