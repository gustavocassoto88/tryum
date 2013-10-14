<?php

/**
 * Elemento numérico decimal do formulario
 *
 * @name Tryum_Form_Decimalr
 * @package Tryum
 * @subpackage Form
 */
class Tryum_Form_Decimal extends Zend_Form_Element_Text {
	/**
	 * Configura o elemento
	 * 
	 * @name init
	 */
	public function init() {
		parent::setAttrib("alt", "decimal");
		parent::setAttrib("class", "decimal");
		parent::setAttrib("field-type", "decimal");
	}
}
