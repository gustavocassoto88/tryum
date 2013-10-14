<?php

/**
 * Elemento textarea do formulario
 *
 * @name Tryum_Form_Textarea
 * @package Tryum
 * @subpackage Form
 */
class Tryum_Form_Textarea extends Zend_Form_Element_Textarea {
	/**
	 * Configura o elemento
	 * 
	 * @name init
	 */
	public function init() {
		parent::setAttrib("field-type", "textarea");
		parent::setAttrib("class", "string textarea");
	}
}
