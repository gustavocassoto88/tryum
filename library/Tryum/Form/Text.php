<?php

/**
 * Elemento text do formulario
 *
 * @name Tryum_Form_Text
 * @package Tryum
 * @subpackage Form
 */
class Tryum_Form_Text extends Zend_Form_Element_Text {
	/**
	 * Configura o elemento
	 * 
	 * @name init
	 */
	public function init() {
		parent::setAttrib("field-type", "text");
		parent::setAttrib("class", "string");
	}
}
