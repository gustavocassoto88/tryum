<?php
/**
 * Classe de extenssão do zend table
 * 
 * @name Tryum_Db_Table
 */
abstract class Tryum_Db_Table extends Zend_Db_Table_Abstract {
	
	/**
	 * Nome da tabela do banco de dados
	 * 
	 * @name $_name
	 * @var string
	 */
	protected $_name = "";
	
	/**
	 * Nome do campo da chave primaria
	 * 
	 * @name $_primary
	 * @var string
	 */
	protected $_primary = "";
	
	/**
	 * Armazena a visibilidade dos campos
	 * 
	 * @access protected
	 * @name $__columnsVisibility
	 * @var array
	 */
	protected $__columnsVisibility = array();
	
	/**
	 * Armazena os campos que são de usuario
	 * 
	 * @access protected
	 * @name $__userColumns
	 * @var array
	 */
	protected $__userColumns = array();
	
	/**
	 * Armazena a descrição da tabela
	 * 
	 * @access protected
	 * @name __describeTable
	 * @var array
	 */
	protected $__describeTable = NULL;
	
	/**
	 * Armazena o nome dos campos
	 * 
	 * @access protected
	 * @name __columnsName
	 * @var array
	 */
	protected $__columnsName = array();
	
	/**
	 * Armazena a descrição dos campos
	 * 
	 * @access protected
	 * @name __columnsDescription
	 * @var array
	 */
	protected $__columnsDescription = array();
	
	/**
	 * Armazena o nome da coluna descrição
	 * 
	 * @access protected
	 * @name __descriptionColumn
	 * @var string
	 */
	protected $__descriptionColumn = "";
	
	/**
	 * Armazena se deve ou não mostrar o formulario de busca
	 * 
	 * @access protected
	 * @name $_searchFormVisibility
	 * @var boolean
	 */
	protected $_searchFormVisibility = TRUE;
	
	/**
	 * Armazena os campos auto complete
	 * 
	 * @access protected
	 * @name $__autocompletes
	 * @var array
	 */
	protected $__autocompletes = array();
	
	/**
	 * Armazena o objeto de requisições
	 * 
	 * @access protected
	 * @name $_request
	 * @var Zend_Request
	 */
	protected $_request;
	
	/**
	 * Armazena o objeto da view
	 * 
	 * @access protected
	 * @name $view
	 * @var Zend_View
	 */
	protected $view;
	
	/**
	 * Armazena as queries dos autocompletes
	 * 
	 * @access protected
	 * @name $__queryautocomplete
	 * @var array
	 */
	protected $__queryautocomplete = array();
	
	/**
	 * Armazena os campos de multipla seleção
	 * 
	 * @access protected
	 * @name $__multipleselects
	 * @var array
	 */
	protected $__multipleselects = array();
	
	/**
	 * Inicializador da classe
	 * 
	 * @name init
	 */
	public function init() {
		// Busca as colunas do model
		$db = Zend_Registry::get("db");
		$this->__describeTable = $db->describeTable($this->getTableName());
		
		// Armazena o request
		$fc = Zend_Controller_Front::getInstance();
		$this->_request = $fc->getRequest();
		
		// Armazena o view
		$this->view = Zend_Registry::get("view");
	}
	
	/**
	 * Adiciona abas ao formulario
	 * 
	 * @name addTab
	 */
	public function addTab($name, $model, $select=NULL, $url=array()) {
		if(is_null($select)) {
			$select = $model->select();
		}
		
		$this->__tabs[] = array(
			'model' => $model,
			'name' => $name,
			'select' => $select,
			'url' => $url
		);
	}
	public function getTabs() {
		return $this->__tabs;
	}
	
	/**
	 * Busca os campos de uma tabela do banco de dados
	 * 
	 * @name setDynamicFields
	 */
	public function setDynamicFields() {
		// Cria o model dos campos de usuário
		$model = new user_Model_Camposusuario();
		$tables = new user_Model_Camposusuariotabelas();
		
		// Cria o select para buscar os campos da tabela
		$select = $model->select()
			->from($model->getTableName(), array('*'))
			->joinUsing($tables->getTableName(), current($tables->getPrimaryField()), array())
			->where("tabela = ?", str_replace("U_", "", $this->getTableName()));
			
		// Busca os campos da tabela
		$fields = $model->fetchAll($select);
		foreach($fields as $field) {
			// Adiciona o campo ao model
			$this->setCampo("U_" . $field->campo, $field->titulo, $field->descricao);
			
			// Adiciona o campo à lista de campos de usuario
			$this->__userColumns["U_" . $field->campo] = TRUE;
			
			// Verifica se é uma tabela relacionada
			if($field->idcampo_usuario_tabela_relacionada > 0) {
				$this->setAutocomplete("U_" . $field->campo, "user_Model_Dynamic", "", "", "U" . $field->campo);
				$this->setModifier("U_" . $field->campo, array("table" => $field->idcampo_usuario_tabela_relacionada));
			}
			
			// Verifica o tipo do campo de usuário
			switch($field->idcampo_usuario_tipo) {
				case 3: // Text
					$this->setVisibility("U_" . $field->campo, TRUE, TRUE, FALSE, FALSE);
					break;
					
				case 5: // Imagem
					$this->setVisibility("U_" . $field->campo, TRUE, TRUE, FALSE, FALSE);
					$this->setModifier("U_" . $field->campo, array('type' => "file", 'preview' => "arquivos/uploads/dynamic", 'destination' => APPLICATION_PATH . "/../arquivos/uploads/dynamic"));
					break;
			}
		}
	}
	
	/**
	 * Hook para a configuração da tabela
	 *
	 * @access protected
	 * @name _setupTableName
	 */
	protected function _setupTableName() {
		global $application;

		// Chama o parent
		parent::_setupTableName();

		// Busca as opções de configuração
		$options = $application->getOption("resources");
		$prefix = $options['db']['prefix'];
		
		// Adiciona o prefixo
		$this->_name = $prefix . $this->_name;
	}
	/**
	 * Retorna o nome da tabela
	 * 
	 * @name getTableName
	 * @return String
	 */
	public function getTableName() {
		return $this->_name;
	}
	
	/**
	 * Retorna o nome do esquema
	 * 
	 * @name getSchemaName
	 * @return String
	 */
	public function getSchemaName() {
		return $this->_schema;
	}
	
	/**
	 * Retorna a descrição da tabela
	 * 
	 * @name descriveTable
	 * @return array
	 */
	public function describeTable() {
		return $this->__describeTable;
	}
	
	/**
	 * Retorna a descrição do campo
	 * 
	 * @access protected
	 * @name __getColumnDescription
	 * @param string $name Nome do campo
	 * @return string
	 */
	public function __getColumnDescription($column_name) {
		return $this->__columnsDescription[$column_name];
	}
	
	/**
	 * Seta a visibilidade do campo
	 * 
	 * @param string $campo Nome da coluna na tabela
	 * @param boolean $cadastro True para aparecer o campo, False para esconder o campo
	 * @param boolean $edicao True para aparecer o campo, False para esconder o campo
	 * @param boolean $busca True para aparecer o campo, False para esconder o campo
	 * @param boolean $listagem True para aparecer o campo, False para esconder o campo
	 */
	public function setVisibility($campo, $cadastro=TRUE, $edicao=TRUE, $busca=TRUE, $listagem=TRUE) {
		$this->__columnsVisibility[$campo] = array(
			'insert'	=> $cadastro, 
			'update'	=> $edicao, 
			'search'	=> $busca, 
			'list'		=> $listagem
		);
	}
	
	/**
	 * Retorna a visibilidade dos campos ou do campo especificado
	 * 
	 * @name getVisibility
	 * @param string $field Nome do campo para retornar a visibilidade
	 * @return string|array
	 */
	public function getVisibility($field=NULL, $type=NULL) {
		// Verifica se retorna todos
		if($field != NULL) {
		if($type == NULL) {
				return $this->__columnsVisibility[$field];
			}
			else {
				return $this->__columnsVisibility[$field][$type];
			}
		}
		else {
			return $this->__columnsVisibility;
		}
	}
	
	/**
	 * Seta um campo ao model
	 * 
	 * @name setCampo
	 * @param string $field Nome do campo
	 * @param string $name Nome do campo
	 * @param string $description Descrição do campo
	 */
	public function setCampo($field, $name, $description="") {
		// Adiciona o nome do campo no vetor
		$this->__columnsName[$field] = $name;
		
		// Adiciona a descrição em um vetor
		$this->__columnsDescription[$field] = $description;
		
		// Adiciona a visibilidade
		$this->setVisibility($field, TRUE, TRUE, TRUE, TRUE);
	}
	
	/**
	 * 
	 */
	public function getCampo() {
		return $this->__columnsName;
	}
	
	/**
	 * Busca o nome do campo que armazena a chave primaria
	 * 
	 * @name getPrimaryField
	 * @return string
	 */
	public function getPrimaryField() {
		return $this->_primary;
	}
	
	/**
	 * Seta se o formulario de busca será visivel
	 * 
	 * @name setSearchFormVisibility
	 * @param boolean $value Valor da visibilidade do formulario
	 */
	public function setSearchFormVisibility($value) {
		$this->_searchFormVisibility = $value;
	}
	
	/**
	 * Busca a visibilidade do formulario
	 * 
	 * @name getSearchFormVisibility
	 * @return boolean
	 */
	public function getSearchFormVisibility() {
		return $this->_searchFormVisibility;
	}
	
	/**
	 * Seta a columna de descrição
	 * 
	 * @name setDescription
	 * @param string $value Nome da coluna que será descrição
	 */
	public function setDescription($value) {
		$this->__descriptionColumn = $value;
		
		// Adiciona o autocomplete padrão
		$s = $this->select();
		$this->setQueryAutoComplete("default", $s);
	}
	
	/**
	 * Retorna a columna de descrição
	 * 
	 * @name getDescription
	 * @return string
	 */
	public function getDescription() {
		return $this->__descriptionColumn;
	}
	
	/**
	 * Seta um campo auto complete
	 * 
	 * @name setAutocomplete
	 * @param string|array $field Nome do campo que será um auto complete
	 * @param string $model Nome da classe que será o model desse auto complete
	 * @param string $ac_name Nome do autocomplete para usar
	 * @param string $middleClass Nome da classe que vai cadastrar os registros
	 */
	public function setAutocomplete($field, $model, $ac_name="default", $middleClass="", $refmap="") {
		// Verifica se é um vetor
		if(is_array($field)) {
			// Adiciona a referencia no vetor de autocompletes
			$this->__autocompletes[$field[0]] = array('model'=>$model, 'ac_name'=>$ac_name, 'middle_model'=>$middleClass);
			
			// Cria a referencia
			$reference = array(
				'columns'		=> array($field[0]),
				'refTableClass'	=> $model,
				'refColumns'	=> array($field[1])
			);
		}
		else {
			// Adiciona a referencia no vetor de autocompletes
			$this->__autocompletes[$field] = array('model'=>$model, 'ac_name'=>$ac_name, 'middle_model'=>$middleClass);
			
			// Cria a referencia
			$reference = array(
				'columns'		=> array($field),
				'refTableClass'	=> $model,
				'refColumns'	=> array($field)
			);
		}
		
		// Verifica o reference
		if($refmap == "") {
			$refmap = $model;
		}
		
		// Adiciona o relacionamento
		$this->_referenceMap[$refmap] = $reference;
		
		// Armazena os campos de seleção multipla
		if($middleClass != "") {
			$this->__multipleselects[] = $field;
		}
	}
	
	/**
	 * Retorna os campos de seleção multipla
	 * 
	 * @name getMultipleFields
	 * @return array
	 */
	public function getMultipleFields() {
		return $this->__multipleselects;
	}
	
	/**
	 * Busca um campo auto complete
	 * 
	 * @name getAutocomplete
	 * @return array
	 */
	public function getAutocomplete($field=NULL) {
		if($field == NULL) {
			return $this->__autocompletes;
		}
		else {
			return $this->__autocompletes[$field];
		}
	}
	
	/**
	 * Monta o formulário
	 * 
	 * @name getForm
	 * @param Tryum_DB_Table $model Modelo à gerar o formulário
	 * @param string $mode Modo do formulário
	 * @return Zend_Form
	 */
	public function getForm($mode="insert") {
		// Busca as informações da tabela
		$columns = $this->describeTable();
		
		// Monta a ação do formulario
		$form_action = array();
		$form_action['module'] = $this->_request->getParam("module");
		$form_action['controller'] = $this->_request->getParam("controller");
		switch($mode) {
			case "insert":
			case "update":
				$form_action['action'] = "form";
				
				// Busca o campo da chave primaria
				$primary_field = $this->getPrimaryField();
		
				// Cria os parametros
				foreach($primary_field as $field) {
					$form_action[$field] = $this->_request->getParam($field);
				}
		
				break;
			case "search":
				$form_action['action'] = "search";
				break;
		}
		
		// Cria a URL do action
		$url = $this->view->url($form_action, NULL, TRUE);
		
		// Busca as configurações do model
		$column_visibilities = $this->getVisibility();
		$column_names = $this->getCampo();
		
		// Cria o formulário
		$form = new Zend_Form();
		$form->setAttrib('enctype', 'multipart/form-data');
		$form->setAction($url)->setMethod("post");
		$form->setDisableLoadDefaultDecorators(TRUE);
		$form->addDecorator("FormElements")
			->addDecorator("HtmlTag", array('tag' => "div"))
			->addDecorator("Form");
		$form->setElementDecorators(array(
			"ViewHelper",
			"Label",
			"Errors",
			new Zend_Form_Decorator_HtmlTag(array('tag' => "div"))
		));
		
		// Cria o botão submit
		$element = new Tryum_Form_Submit("submit");
		
		// Verifica o modo do formulario
		switch($mode) {
			case "insert":
				$form_label = "Cadastrar";
				break;
			
			case "update":
				$form_label = "Atualizar";
				break;
				
			case "search":
				$form_label = "Buscar";
				break;
		}
		
		//		
		$element->setLabel($form_label);
		
		//
		$element
			->clearDecorators()
			->addDecorator('ViewHelper')
			->addDecorator('Errors')
			->addDecorator('HtmlTag', array('tag' => "div", 'openOnly' => TRUE, 'class' => "form-buttons"));
		
		// Adiciona o botão ao formulário
		$form->addElement($element);
		
		// Cria o botão submit
		$element = new Tryum_Form_Button("cancel");
		
		//
		$element->setLabel("Cancelar");
		
		//
		$back_action = array();
		$back_action['module'] = $this->_request->getParam("module");
		$back_action['controller'] = $this->_request->getParam("controller");
		$back_action['action'] = "list";
		
		// 
		if($this->_request->getParam("page", 0) > 0) {
			$back_action['page'] = $this->_request->getParam("page");
		}
		
		//
		$element
			->setAttrib("data-url", $this->view->url($back_action, NULL, TRUE))
			->clearDecorators()
			->addDecorator('ViewHelper')
			->addDecorator('Errors')
			->addDecorator('HtmlTag', array('tag' => "div", 'closeOnly' => TRUE, 'class' => "form-buttons"));
		
		// Adiciona o botão ao formulário
		$form->addElement($element);
		
		// Percorre as colunas
		foreach($column_names as $name_name => $column_description) {
			$column_info = $columns[$name_name];
			
			// Cria o decorator padrão
			$decorator = array(
				array('ViewHelper'),
				array('Label', array('escape' => FALSE)),
				array('HtmlTag', array('tag' => 'div')),
				array('Description'),
				array('Errors', array('class'=>'error')),
				array('HtmlTag', array('tag' => 'div', 'class' => "zend-form-element", 'id'=>"element-" . $name_name))
			);
			
			// Verifica se é um campo visivel
			if($column_visibilities[$name_name][$mode] === FALSE) {
				continue;
			}
			
			// Busca o nome do autocomplete se existir
			$autocomplete = $this->getAutocomplete($name_name);
			$autocomplete_name = $autocomplete['model'];
			$autocomplete_ac_name = $autocomplete['ac_name'];
			$autocomplete_middleclass = $autocomplete['middle_model'];
			$reference_table = $autocomplete_name;
			
			// Verifica se o autocomplete existe
			if(class_exists($autocomplete_name)) {
				$element = new Tryum_Form_Autocomplete($name_name);
				$element->setJQueryParam("source", "");
				$element->setAttrib("data-ac", $autocomplete_name);
				
				if($autocomplete_ac_name != "default") {
					$element->setAttrib("data-ac_name", $autocomplete_ac_name);
				}
				
				if($autocomplete_middleclass != "") {
					$element->setAttrib("data-ac_middle", $autocomplete_middleclass);
				}
				
				
				// Verifica se existe tabela
				if(strlen($this->_modifiers[$name_name]['table']) > 0) {
					// Cria o model das tabelas relacionadas
					$model = new user_Model_Camposusuariotabelasrelacionadas();
					$row = $model->fetchRow(array('idcampo_usuario_tabela = ?' => $this->_modifiers[$name_name]['table']));
					
					// Instancia a classe model
					$referece_model = new $reference_table("U_" . $row->tabela);
					
					// Adiciona a tabela
					$element->setAttrib("data-ac_table", $row->tabela);
				}
				else {
					// Instancia a classe model
					$referece_model = new $reference_table();
				}
				
				// Busca a coluna descrição
				$description_column = $referece_model->getDescription();
				
				//
				$decorator = array(
					array('UiWidgetElement', array('tag' => '')),
					array('Label', array('escape' => FALSE)),
					array('HtmlTag', array('tag' => 'div')),
					array('Description'),
					array('Errors', array('class'=>'error')),
					array('HtmlTag', array('tag' => 'div', 'class' => "zend-form-element", 'id'=>"element-" . $name_name))
				);
			}
			else {
				// Verifica o tipo do campo
//				echo $column_info['DATA_TYPE'] . "<br>";
				switch($column_info['DATA_TYPE']) {
					case "int":
					case "int4":
					case "int8":
					case "int16":
						$element = new Tryum_Form_Integer($name_name);
						$element->setAttribs(array("class" => $column_info['DATA_TYPE'] . " integer"));
						break;
						
					case "float":
						$element = new Tryum_Form_Integer($name_name);
						$element->setAttribs(array("class" => $column_info['DATA_TYPE'] . " float"));
						break;
						
					case "decimal":
					case "float8":
						$element = new Tryum_Form_Decimal($name_name);
						break;
					
					case "tinyint":
					case "bool":
						$element = new Tryum_Form_Checkbox($name_name);
						break;
						
					case "date":
					case "timestamp":
						$element = new Tryum_Form_Date($name_name);
						break;
					
					case "text":
						$element = new Tryum_Form_Textarea($name_name);
						
						// Cria o decorator padrão
						$decorator = array(
							array('ViewHelper'),
							array('Label', array('escape' => FALSE)),
							array('HtmlTag', array('tag' => 'div')),
							array('Description'),
							array('Errors', array('class'=>'error')),
							array('HtmlTag', array('tag' => 'div', 'class' => "zend-form-element", 'id'=>"element-" . $name_name))
						);
						break;
					
					case "varying":
					case "varchar":
					default:
						// Verifica se é campo senha
						if($this->_modifiers[$name_name]['type'] == "password") {
							$element = new Tryum_Form_Password($name_name);
							$element->setAttribs(array("class" => $column_info['DATA_TYPE'] . " string password"));
						}
						// Verifica se é campo file
						elseif($this->_modifiers[$name_name]['type'] == "file") {
							$element = new Tryum_Form_File($name_name);
							$element->setAttribs(array("class" => $column_info['DATA_TYPE'] . " file"));
							
							// Seta o destino do arquivo
							$element->setDestination($this->_modifiers[$name_name]['destination']);
							
							// Cria o nome da imagem
							//$filename = md5(time() . rand(0, 99999)) . "." . substr($_FILES[$name_name]['name'], strlen($_FILES[$name_name]['name']) - 3);
							$filename = md5(time() . rand(0, 99999)) . substr($_FILES[$name_name]['name'], strrpos($_FILES[$name_name]['name'], "."));
							$element->addFilter("Rename", array('target' => $this->_modifiers[$name_name]['destination'] . "/" . $filename));
							
							//
							$decorator = array(
								array('File'),
								array('Label', array('escape' => FALSE)),
								array('HtmlTag', array('tag' => 'div')),
								array('Description'),
								array('Errors', array('class'=>'error')),
								array('HtmlTag', array('tag' => 'div', 'class' => "zend-form-element", 'id'=>"element-" . $name_name))
							);
							
							// Verifica se está atualizando o registro
							if($mode == "update") {
								if($this->_modifiers[$name_name]['preview']) {
									$select = $this->select();
									
									// Cria os parametros
									foreach($primary_field as $field) {
										$param = $this->_request->getParam($field, 0);
										if($param > 0) {
											$select->where($field . " = ?")->bind($param);
										}
									}
									
									$arquivo = $this->fetchAll($select)->current()->$name_name;
									$element->setAttrib("data-prev_file", $this->_modifiers[$name_name]['preview'] . "/" . $arquivo);
								}
							}
						}
						else {
							$element = new Tryum_Form_Text($name_name);
							$element->setAttribs(array("class" => $column_info['DATA_TYPE'] . " string"));
						}
						break;
				}
			}
			
//			foreach($this->_modifiers[$name_name] as $attr => $modifier) {
//				$element->setAttrib($attr, $modifier);
//			}
//			
//			echo $element->getAttrib("type") . "<br>";
			
			// Verifica se é um autocomplete, e ja tem valor
			$label = $this->_request->getParam($name_name."_label", -1);
			if(($label > -1) || (strlen($description_column) > 0)) {
				$description = "";
				if(($mode == "update") && ($reference_table != NULL)) {
					
					// Busca o campo da chave primaria
					$primary_field = $this->getPrimaryField();
			
					$select = $this->select();
					
					// Cria os parametros
					foreach($primary_field as $field) {
						$param = $this->_request->getParam($field, 0);
						if($param > 0) {
							$select->where($field . " = ?")->bind($param);
						}
					}
					
					if($autocomplete_middleclass == "") {
						try {
							// Busca o registro
							$result = $referece_model->fetchRow(array('idkey = ?' => $this->fetchRow($select)->$name_name));
							if($result !== NULL) {
								// Armazena o valor
								$description = $result->$description_column;
							}
						}
						catch(Exception $e) {
							try {
								$description = $this->fetchAll($select)->current()->findParentRow($reference_table)->$description_column;
							}
							catch(Exception $e) {
								$description = "";
							}
						}
					}
					else {
						$reference_model = new $reference_table();
						$reference_description = $reference_model->getDescription();
						$reference_field = current($reference_model->getPrimaryField());
						$toJson = array();
						foreach($this->fetchAll($select)->current()->findDependentRowset($autocomplete_middleclass) as $row) {
							$toJson[] = array('value' => $row->$reference_field, 'label' => $row->findParentRow($reference_table)->$reference_description);
						}
						
						$element->setAttrib("data-json_values", json_encode($toJson));
					}
				}
				
				$element->setAttrib("data-ac_label", $this->_request->getParam($name_name . "_label", $description));
			}
			
			
			// Cria o elemento nome
			if($column_names[$name_name] == NULL) {
				continue;
			}
			$element
				->setLabel($column_names[$name_name] . " <span>" . $this->__getColumnDescription($name_name) . "</span>");
				
			// Verifica se precisa obrigatoriedade no forumulário
			if($mode != "search") {
				$element->setRequired(!$column_info['NULLABLE']);
			}
			
			// Estiliza
			$element->setDecorators($decorator);
			
			// Verifica se está no modo de busca
			if($mode == "search") {
				$element->setAttrib("placeholder", "Buscar " . $column_names[$name_name]);
			}
			
			// Verifica se o campo é um campo de usuário
			if($this->__userColumns[$name_name] === TRUE) {
				$element->setAttrib("data-userfield", "true");
			}
			
			// Adiciona o elemento ao formulário
			$form->addElement($element);
		}
		
		// Retorna o formulário
		return $form;
	}
	
	/**
	 * Seta o autocomplete na lista
	 * 
	 * @name setQueryAutoComplete
	 * @param string $name Nome do autocomplete
	 * @param Zend_Db_Table_Select $select Objeto de manipulação de query
	 */
	public function setQueryAutoComplete($name, $select) {
		$this->__queryautocomplete[$name] = $select;
	}
	
	/**
	 * Busca a query do autocomplete na lista
	 * 
	 * @name getQueryAutoComplete
	 * @param string $name Nome do autocomplete
	 * @return Zend_Db_Table_Select
	 */
	public function getQueryAutoComplete($name) {
		return $this->__queryautocomplete[$name];
	}
	
	/**
	 * Seta o modificador do campo
	 * 
	 * @name setModifier
	 * @param string $field Campo para aplicar o modificador
	 * @param array $modifier Vetor contendo os modificadores
	 */
	public function setModifier($field, $modifier) {
		// Verifica se o modificador ja existe
		if(!isset($this->_modifiers[$field])) {
			$this->_modifiers[$field] = $modifier;
		}
		else {
			// Concatena mais um modificador
			$this->_modifiers[$field] = array_merge($this->_modifiers[$field], $modifier);
		}
	}
	
	/**
	 * Busca o modificador do campo
	 * 
	 * @name getModifier
	 * @param string $field Campo para buscar o modificador
	 */
	public function getModifier($field) {
		// Retorna os modificadores
		return $this->_modifiers[$field];
	}
	
	/**
	 * Busca a posiçao do campo no formulario
	 * 
	 * @name getPosition
	 * @param string $field Nome do campo para buscar a posição
	 * @return string
	 * @todo Pensar numa forma de como saber se está editado ou inserindo
	 */
	public function getPosition($field) {
		// Inicializa a posição
		$position = 0;
		
		// Percorre as colunas
		foreach($this->__columnsName as $fieldName => $fieldDescription) {
			// Busca a visibilidade
			$action = $this->_request->getParam("action", "index");
			if($action == "list") {
				$mode = "search";
			}
			elseif($action == "form") {
				if(1) {
					$mode = "insert";
				}
				else {
					$mode = "update";
				}
			}
			
			// Verifica a visibilidade do campo
			if($this->__columnsVisibility[$fieldName][$mode]) {
				// Soma mais uma posição se for visivel
				$position++;
			}
			
			// Verifica se encontrou a posição
			if($fieldName == $field) {
				return $position;
			}
		}
	}
	
	/**
	 * Cria o log de banco de dados
	 *
	 * @name doDatabaseLog
	 * @param string $action Acao executada no banco de dados
	 * @param array $data Vetor com os dados à serem modificados/adicionados na tabela
	 */
	public function doDatabaseLog($action, $data) {
		// Busca a sessão das mensagens
		$session = new Zend_Session_Namespace("login");
	
		// Monta os dados
		$insert_data = array();
		$insert_data['idusuario'] = $session->logged_usuario['idusuario'];
		$insert_data['tabela'] = $this->getTableName();
		$insert_data['json_data'] = json_encode($data);
		$insert_data['acao_executada'] = $action;
		$insert_data['data_execucao'] = date("Y-m-d H:i:s");
	
		// Cria o model dos logs
		$model = new user_Model_Logs();
		try {
			$model->insert($insert_data);
		}
		catch(Exception $e) {
			throw new Zend_Controller_Action_Exception($e->getMessage(), $e->getCode());
		}
	}
}
