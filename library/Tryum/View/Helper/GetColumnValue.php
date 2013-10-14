<?php 

class Tryum_View_Helper_GetColumnValue {
	
	/**
	 * Busca o valor da coluna, buscando o valor caso seja o valor de uma tabela relacionada
	 * 
	 * @name GetColumnValue
	 * @param Zend_Db_Table_Row
	 * @param string $field Nome da coluna
	 * @return string
	 */
	public function GetColumnValue($row, $field) {
		//
		$model = $row->getTable();
		
		// Busca a classe referencia da coluna
		$reference_table = $model->getAutocomplete($field);
		$reference_table = $reference_table['model'];
		if($reference_table != NULL) {
			// Instancia a classe model
			$referece_model = new $reference_table();
			
			// Busca a coluna descrição
			$description_column = $referece_model->getDescription();
			
			// Busca o valor
			$value = $row->findParentRow($reference_table)->$description_column;
		}
		else {
			// Busca o valor
			$value = $row->$field;
		}
		
		// Busca a descrição da tabela
		$columns = $model->describeTable();
		
		// Verifica o tipo do campo
		switch($columns[$field]['DATA_TYPE']) {
			case "decimal":
			case "float8":
				// Formata a data
				$value = number_format($value, 2, ",", ".");
				break;
				
			case "date":
			case "timestamp":
				// Remove a parte da hora
				$values = explode(" ", $value);
				
				// Formata a data
				$data = implode("/", array_reverse(explode("-", $values[0])));
				
				// Verifica se a data existe
				if($data == "00/00/0000") {
					$data = "--/--/----";
				}
				
				$value = $data;
				break;
				
			case "tinyint":
			case "bool":
				if($value) {
					$value = "Sim";
				}
				else {
					$value = "Não";
				}
				break;
				
			default:
				if(strlen($value) == 0) {
					$value = "--";
				}
				break;
		}
		
		
		//
		return $value;
	}
}
