<?php

/**
 * Elemento de checagem do formulario
 *
 * @name Tryum_Form_Checkbox
 * @package Tryum
 * @subpackage Form
 */
class Tryum_Form_Checkbox extends Zend_Form_Element_Checkbox {
	/**
	 * Configura o elemento
	 * 
	 * @name init
	 */
	public function init() {
		parent::setAttrib("field-type", "checkbox");
	}
}
