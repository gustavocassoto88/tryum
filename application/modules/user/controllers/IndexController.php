<?php

/**
 *
 */
class user_IndexController extends Tryum_Controller_Action {
	/**
	 *
	 */
	public function init() {
	}

	/**
	 *
	 */
	public function indexAction() {
	}
	
	/**
	 * Método que busca os auto completes
	 * 
	 * @name autocompleteAction
	 */
	public function autocompleteAction() {
		// Busca o termo passado
		$filter = $this->_request->getParam("term", "");
		
		// Inicializa os dados de retorno
		$data = array();
		
		// Busca o auto-complete passado
		$autocomplete = $this->_request->getParam("ac");

		// Verifica se existe tabela do autocomplete
		$ac_table = $this->_request->getParam("ac_table", NULL);
		if($ac_table !== NULL) {
			$ac_table = "U_" . $ac_table;
		}
		
		// Instancia o model
		$model = new $autocomplete($ac_table);
		
		// Verifica se existe query de autocomplete
		$ac_name = $this->_request->getParam("ac_name", "default");
		
		// Busca o select
		$select = $model->getQueryAutoComplete($ac_name);
		
		// Busca o campo da chave primaria
		$primary_field = $model->getPrimaryField();	
		$description_field = $model->getDescription();

		// Verifica se é um espaço, para mostrar tudo
		if($filter == " ") {
			$filter = "";
		}
		
		// Da o bind nos parametros like
		$select->where($description_field . " LIKE lower(?)", "%" . strtolower($filter) . "%");
		
		// Ordena
		$select->order($description_field);
		
		// Limita
		$select->limit(30);
		
		// Busca a query do auto-complete
		$records = $model->fetchAll($select);

		// Percorre os registros
		foreach($records as $row) {
			// Busca os valores iniciais
			$label = ($row[$description_field]);
			$value = $row[$primary_field[1]];
			$line = array('label' => $label, 'identifier' => $value);
			
			// Percorre as colunas para os valores adicionais
			foreach($row as $column_name => $column_value) {
				// Só adicionar caso não for chave primaria ou descrição da tabela
				if(($column_name != $description_field) && ($column_name != $primary_field[1])) {
					$line[$column_name] = $column_value;
				}
			}
			
			// Monta o vetor
			$data[] = $line;
		}
		
		// Desabilita o layout e da o parse para json
		$this->_helper->json($data);
	}
}

