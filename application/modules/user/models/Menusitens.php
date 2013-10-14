<?php

/**
 * Modelo da tabela de menus_itens
 *
 * @name user_Model_Menusitens
 * @see Zend_Db_Table_Abstract
 */
class user_Model_Menusitens extends Tryum_Db_Table {
	/**
	 * Armazena o nome da tabela
	 *
	 * @access protected
	 * @name $_name
	 * @var string
	 */
	protected $_name = "menu_itens";

	/**
	 * Armazena o nome do campo da tabela primaria
	 *
	 * @access protected
	 * @name $_primary
	 * @var string
	 */
	protected $_primary = "iditem";
	
	/**
	 * Inicializa o model
	 * 
	 * @name init
	 */
	public function init() {
		//
		$this->setCampo("descricao", "Descrição");
		$this->setCampo("idcategoria", "Categoria");
		$this->setCampo("idperfil", "Perfil");
		$this->setCampo("modulo", "Modulo");
		$this->setCampo("controlador", "Controlador");
		$this->setCampo("acao", "Ação");
		
		//
		$this->setAutocomplete("idcategoria", "user_Model_Menuscategorias");
		$this->setAutocomplete("idperfil", "user_Model_Perfis");
		
		//
		$this->setDescription("descricao");
		
		//
		parent::init();
	}
}

