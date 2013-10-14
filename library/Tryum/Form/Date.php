<?php

/**
 * Elemento date do formulario
 *
 * @name Tryum_Form_Date
 * @package Tryum
 * @subpackage Form
 */
class Tryum_Form_Date extends Zend_Form_Element_Text {
	/**
	 * Configura o elemento
	 * 
	 * @name init
	 */
	public function init() {
		parent::setAttrib("field-type", "date");
		parent::setAttrib("class", "datepicker");
	}
}
