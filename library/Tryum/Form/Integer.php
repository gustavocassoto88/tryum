<?php

/**
 * Elemento numérico do formulario
 *
 * @name Tryum_Form_Integer
 * @package Tryum
 * @subpackage Form
 */
class Tryum_Form_Integer extends Zend_Form_Element_Text {
	/**
	 * Configura o elemento
	 * 
	 * @name init
	 */
	public function init() {
		parent::setAttrib("alt", "integer");
		parent::setAttrib("class", "integer");
		parent::setAttrib("field-type", "integer");
	}
}
