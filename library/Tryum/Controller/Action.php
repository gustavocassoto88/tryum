<?php 
/**
 * Controlador para extensão das funcionalidades do Zend
 *
 * @name Tryum_Controller_Action
 * @see Zend_Controller_Action
 */
class Tryum_Controller_Action extends Zend_Controller_Action {
	/**
	 * Armazena os icones dinamicos da listagem
	 * 
	 * @access protected
	 * @name $__listExtraIcons
	 * @var array
	 */
	protected $__listExtraIcons = array();
	
	/**
	 * Armazena os parametros obrigatórios para o funcionamento da tela
	 * 
	 * @access protected
	 * @name $_requiredParam
	 * @var array
	 */
	protected $_requiredParam = array();
	
	/**
	 * Armazena a variavel de mensagens
	 *
	 * @access public
	 * @name $_messages
	 * @var Zend_Session_Namespace
	 */
	public $_messages = NULL;
	
	/**
	 * Método que inicializa o controlador
	 *
	 * @name init
	 */
	public function init() {
		
		// Busca o campo da chave primaria
		$primary = $this->_model->getPrimaryField();
		$primary = $primary[1];
		$this->view->primary = $primary;
		
		// Inicializa a sessão das mensagens
		$this->_messages = new Zend_Session_Namespace("messages");
		
		// Assina o model principal
		$this->view->_model = $this->_model;
		
		// Chama o parente
		parent::init();
	}
	
	/**
	 * Ação de consulta padrão
	 *
	 * @access protected
	 * @name listAction
	 */
	protected function listAction() {
		// Busca a pagina exibida
		$current_page = intval($this->_request->getParam("page", 1));
		
		// Cria o formulario para busca
		$form = $this->_model->getForm("search");
		
		// Popula o formulário 
		$form->isValid($this->_request->getParams());
		
		// Busca as informações da tabela
		$columns = $this->_model->describeTable();
		
		// Cria a query inicial
		$select = $this->_model->select();
		
		//	
		foreach($form->getElements() as $element) {
			// Busc ao nome do elemento
			$form_name = $element->getName();
			
			// Verifica se é o botão submit
			if($form_name == "submit") {
				continue;
			}
			
			// Busca o valor à ser buscado
			$value = $this->_request->getParam($form_name);
			$field_type = $element->getAttrib("alt");
			$name_name = $element->getName();
	
			// Verifica se é decimal
			switch($field_type) {
				case "decimal":
					$field_value = number_format($value, 2, ",", ".");
					break;
					
				case "date":
					$field_value = implode("/", array_reverse(explode("-", $value)));
					// Verifica se a data existe
					if($field_value == "00/00/0000") {
						$field_value = "";
					}
					break;
					
				default:
					$field_value = $value;
			}
	
			// Seta o valor no campo
			$form->getElement($name_name)->setValue($field_value);

			// Verifica se o valor está sendo passado
			if(strlen($value) > 0) {
				// Verifica o tipo
				switch($columns[$form_name]['DATA_TYPE']) {
					// Verifica se é uma string
					case "varying":
					case "varchar":
						// Modifica para suportar o ILIKE
						$signal = "LIKE";
							$value = "%" . $value . "%";
						break;
					
					// Outros tipos não são full-text
					default:
						$signal = "=";
						break;
					
				}
				
				// Monta o where
				$select->where($form_name . " " . $signal . " ?", $value);
			}
		}
		
		// Cria um hook para a listagem
		$return = $this->doBeforeList($select);
		
		// Verifica se o retorno do hook é um select personalizado
		if($return != NULL) {
			$select = $return;
		}
		
		// Monta a ordem
		$primary_field = $this->_model->getPrimaryField();
		$parans = array();
		foreach($primary_field as $field) {
			$select->order($field);
		}
		
		// Busca a configuração da paginação
		$config = Zend_Registry::get("config");
		
		// Cria a paginação
		try {
			$paginator = Zend_Paginator::factory($select);
			$paginator->setPageRange($config->tryum->paginator->range);
			$paginator->setCurrentPageNumber($current_page);
		} catch(Exception $e) {
			//var_dump($e);
			//die("<br />Fatal PHP error in " . __FILE__ . " on line " . __LINE__);
		}
		
		// Verifica o parametro nopage
		if($this->_request->getParam("nopage", 0) == 1) {
			$paginator->setItemCountPerPage(-1);
		}
		else {
			$paginator->setItemCountPerPage($config->tryum->paginator->perpage);
		}
		
		// Chama o hook manipulador
		$form_temp = $this->doAfterCreateForm();
		
		// Percorre os elementos novos
		foreach($form_temp->getElements() as $element) {
			// Busca o nome do elemento
			$name_name = $element->getName();
			
			// Verifica se possui os dados na sessão
			$param = $this->_request->getParam($name_name);
			if($param > 0) {
				// Busca a descricao
				$label = $this->_request->getParam($name_name . "_label");
				
				// Seta os valores no campo
				$element->setAttrib("ac_label", $label);
				$element->setValue($param);
			}
			
			// Adiciona o elemento ao formulário
			$form->addElement($element);
		}
		
		// Percorre os parametros obrigatorios
		foreach($this->_requiredParam as $param => $value) {
			$form->setAction($form->getAction() . "/" . $param . "/" . $value);
		}
		
		// Busca o total de paginas paginas
		$total_paginas = (int)($paginator->getTotalItemCount() / $paginator->getItemCountPerPage());
		$total_paginas++;
		
		// Assina as variaveis à view
		$this->view->requireParam = $this->_requiredParam;
		$this->view->form = $form;
		$this->view->paginator = $paginator;
		$this->view->listExtraIcons = $this->__listExtraIcons;
		$this->view->next_page = ($current_page < $total_paginas) ? $current_page + 1 : $total_paginas;
		$this->view->previous_page = ($current_page > 1) ? $current_page - 1 : 1;
		
		// Assina o modulo e o controlador que foi chamado
		$this->view->module = $this->_request->getParam("module");
		$this->view->controller = $this->_request->getParam("controller");
		$this->view->primaryKeys = $primary_field;
		$this->view->searchFormVisibility = $this->_model->getSearchFormVisibility();
	}
	
	/**
	 * Ação de busca padrão
	 * 
	 * @access protected
	 * @name searchAction
	 */
	protected function searchAction() {
		// Busca o formulário
		$form = $this->_model->getForm("search");
		
		// Chama o hook manipulador
		$form_temp = $this->doAfterCreateForm();
		
		// Percorre os elementos novos
		foreach($form_temp->getElements() as $element) {
			$form->addElement($element);
		}
		
		// Percorre as variaveis post
		$params = array();
		foreach($form->getElements() as $element) {
			// Verifica se é o botão submit
			if($element->getName() == "submit") {
				continue;
			}
			
			// Busca o valor à ser buscado
			$value = $this->_request->getParam($element->getName());
			
			// Verifica se o valor está sendo passado
			if(strlen($value) > 0) {
				// Busca o tipo do campo
				$field_type = $element->getAttrib("field-type");
				$name_name = $element->getName();
				
				// Verifica se é decimal
				switch($field_type) {
					case "decimal":
						$value = str_replace(",", ".", str_replace(".", "", $value));
						if($value <= 0) {
							$value = "";
						}
						break;
						
					case "checkbox":
						if($value <= 0) {
							$value = "";
						}
						break;
					
					case "date":
						$value = implode("-", array_reverse(explode("/", $value)));
						// Verifica se a data existe
						if($value == "00/00/0000") {
							$value = "";
						}
						break;
				}
			
				if(strlen($value) > 0) {
					$params[$element->getName()] = $value;
				}
			}
			
			// Verifica se existe campo label de autocomplete
			$label = $this->_request->getParam($element->getName()."_label", NULL);
			if($label !== NULL) {
				
				// Verifica se o valor está sendo passado
				if(strlen($value) > 0) {
					$params[$element->getName()."_label"] = $label;
				}
			}
		}
		
		// Verifica o parametro nopage
		if($this->_request->getParam("nopage") == "on") {
			$params["nopage"] = TRUE;
		}
		
		// Redireciona para a listagem
		$this->_helper->redirector("list", NULL, NULL, array_merge($params, $this->_requiredParam));
	}
	
	/**
	 * Ação de criação e tratamento do formulario
	 * 
	 * @access protected
	 * @name formAction
	 */
	protected function formAction() {
		// Inicia o vetor que armazenará as futuras inserções
		$toAddAtMultiple = array();
		
		// Busca o campo da chave primaria
		$primary_field = $this->_model->getPrimaryField();
		
		// Cria os parametros
		$where = array();
		foreach($primary_field as $field) {
			// Busca o parametro
			$value = $this->_request->getParam($field, 0);
			$id = $value;
			
			// Verifica o parametro
			if($value > 0) {
				$where[$field . " = ?"] = $value;
			}
		}
			
		// Verifica se existe id passando por parametro
		if(count($where) > 0) {
			// Cria o formulario para atualizar
			$form = $this->_model->getForm("update");
			
			// Busca o registro para popular o formulario para edição
			$data = $this->_model->fetchRow($where);
		}
		else {
			// Cria o formulario para inserir
			$form = $this->_model->getForm("insert");
		}
		
		// Hook para a criação do formulário
		$form = $this->doOnCreateForm($form);
		
		// Verifica se esta vindo por post
		if($this->_request->isPost()) {
			
			// Percorre os campos de multipla seleção
			foreach($this->_model->getMultipleFields() as $multiple_name) {
				// Busca o elemento do formulario
				$element = $form->getElement($multiple_name);
				
				// Remove a obrigatoriedade
				$element->setRequired(FALSE);
				
				// Verifica se é um campo de seleção multipla
				if($element->getAttrib("data-ac_middle") != "") {
					
					// Remove o campo do formulario
					$tmp = array(
						'reference_table' => $element->getAttrib("data-ac"),
						'relation_table' => $element->getAttrib("data-ac_middle"),
						'data' => $_POST[$multiple_name],
						'label' => $_POST[$multiple_name."l"]
					); 
					
					unset($_POST[$multiple_name."l"]);
					
					// Monta o json dos dados
					$toJson = array();
					foreach($tmp['data'] as $index => $value) {
						$toJson[] = array('value' => $value, 'label' => $tmp['label'][$index]);
					}
					$toJson = array_reverse($toJson);
					
					// Ajusta os atributos
					$element->setAttrib('data-json_values', json_encode($toJson));
					$toAddAtMultiple[] = $tmp;
				}
			}
			
			// Percorre os valores
			foreach($_POST as $key => $value) {
				
				// Busca o elemento do formulario
				$element = $form->getElement($key);
				
				// Verifica se o campo existe
				if($element != NULL) {
					
					// Busca o tipo do campo
					$field_type = $element->getAttrib("field-type");
					
					// Verifica se é decimal
					switch($field_type) {
						case "decimal":
							$_POST[$key] = str_replace(",", ".", str_replace(".", "", $value));
							break;
							
						case "integer":
							if(!is_numeric($_POST[$key])) {
								$_POST[$key] = NULL;
							}
							break;
							
						case "password":
							$_POST[$key] = md5($_POST[$key]);
							break;
						
						case "":
							
							break;
							
						case "date":
							$_POST[$key] = implode("-", array_reverse(explode("/", $value)));
							break;
					}
				}
			}
			
//			$uploader = new Zend_File_Transfer_Adapter_Http();
//			$uploader->isValid();
//			$destination = APPLICATION_PATH . "/../arquivos/uploads/destaques";
//			$uploader->setDestination($destination);
			
			// Verifica se o formulário é válido
			if($form->isValid($_POST)) {
				// Busca os dados dos elementos
				$data = $form->getValues();
				
				// Busca as colunas do formulario
				$columns = $this->_model->getCampo();
				foreach($columns as $name => $description) {
					// Verifica se existe o campo
					if($form->getElement($name) == NULL) {
						continue;
					}
					
					// Busca o auto-complete
					if($form->getElement($name)->getAttrib("data-ac_middle") != "") {
						unset($data[$name]);
					}
					// Busca os modificadores
					$modifier = $this->_model->getModifier($name);
					if($modifier['type'] == "file") {
						// Verifica se existe arquivo
						if($_FILES[$name]['size'] > 0) {
							// Faz o upload caso exista
							$form->$name->receive();
							
							// Adiciona o nome ao data
							$data[$name] = basename($form->$name->getFileName());
						}
						else {
							unset($data[$name]);
						}
					}
				}
				
				// Verifica se o registro está sendo inserido ou editado
				if(count($where) <= 0) {
					// Hook antes da inserção
					$data = $this->doBeforeInsert($data);
					
//					// 
//					foreach($data as $key => $value) {
//						unset($data[$key]);
//						$data[strtoupper($key)] = $value;
//					}
//					
//					var_dump($data);
//					die();
					
					// Salva os dados
					$this->_model->insert($data);
					
					// Busca o id gerado
					$sequence_name = $this->_model->getSchemaName() . "." . $this->_model->getTableName() . "_" . implode("_", $this->_model->getPrimaryField());
					$id = $this->_model->getAdapter()->lastInsertId($sequence_name);
					
					// Cria o log
					$data_log = array(current($primary_field) => (int)$id);
					$data_log = array_merge($data_log, $data);
					$this->doDatabaseLog("INSERT", $data_log);
					
					// Hook depois da inserção
					$this->doAfterInsert($id);
				}
				else {
					// Hook antes da atualização
					$data = $this->doBeforeUpdate($data);
					
					// Cria o log 
					$data_log = array(current($primary_field) => (int)$this->_request->getParam(current($primary_field), 0));
					$data_log = array_merge($data_log, $data);
					$this->doDatabaseLog("UPDATE", $data_log);
					
					// Salva os dados
					$this->_model->update($data, $where);
					
					// Armazena o id
					$id = (int)$this->_request->getParam(current($primary_field), 0);
					
					// Hook depois da atualização
					$this->doAfterUpdate();
				}
				
				// Percorre os valores de multiplos selects
				foreach($toAddAtMultiple as $toAdd) {
					// Cria o model do relacionamento
					$relation_model = new $toAdd['relation_table']();
					$reference_model = new $toAdd['reference_table']();
					
					// Busca o id da tabela
					if(is_array($this->_model->getPrimaryField())) {
						$idreference1 = array_pop($this->_model->getPrimaryField());
					}
					else {
						$idreference1 = $this->_model->getPrimaryField();
					}
					
					// Busca o id da tabela de referencia
					if(is_array($this->_model->getPrimaryField())) {
						$idreference2 = array_pop($reference_model->getPrimaryField());
					}
					else {
						$idreference2 = $reference_model->getPrimaryField();
					}
					
					// Remove os itens ja cadastrados
					if(count($where) > 0) {
						$relation_model->delete($where);
					}
					
					// Percorre os valores do formulario
					foreach($toAdd['data'] as $value) {
						// Monta o vetor de dados
						$data = array();
						$data[$idreference1] = $id;
						$data[$idreference2] = $value;
						
						// Insere o valor
						$relation_model->insert($data);
					}
				}
				
				// Verifica se existe parametro da pagina
				if($this->_request->getParam("page", 0) > 0) {
					$this->_requiredParam['page'] = $this->_request->getParam("page", 0);
				}
				
				// Verifica se possui referer
				$referer_url = $this->_request->getParam("referer_url", "");
				if($referer_url != "") {
					// Busca o basepath
					$config = Zend_Registry::get('config');
					$basepath = $config->tryum->config->basepath;
					$url = str_replace($basepath, "", $referer_url);
					
					foreach($primary_field as $primary) {
						$url = substr($url, 0, strpos($url, "/" . $primary));
					}
					
					// Redireciona o usuário à tela anterior
					$this->_redirect($url);
				}
				else {
					// Redireciona o usuário à consulta
					$this->_helper->redirector("list", NULL, NULL, $this->_requiredParam);
				}
			}
		}
		else {
			// Cria o hook para população do formulario
			$data = $this->doBeforePopulate($data);
			
			// Popula os campos caso estiver editando
			if(count($where) > 0) {
				// Busca o nome do elemento
				$form_elements = $form->getElements();
				
				//
				foreach($form_elements as $form_element) {
					// Busca o nome do elemento
					$form_name = $form_element->getName();
					
					// Verifica se éo botão submit
					if(($form_name == "submit") || ($form_name == "cancel")) { 
						continue;
					}
					
					$middle = $form_element->getAttrib("data-ac_middle");
					if($middle != "") {
						//var_dump($form_element);
					}
					else {
						// Seta o valor no campo
						$form->getElement($form_name)->setValue($data[$form_name]);
					}
				}
			}
			
			// Verifica se possui abas
			$tabs = $this->_model->getTabs();
			$this->view->_tabs = $tabs;
		}
		
		// Chama o hook manipulador
		$form_temp = $this->doAfterCreateForm();
		
		// Percorre os elementos novos
		foreach($form_temp->getElements() as $element) {
			
			// Busca o nome do elemento
			$name_name = $element->getName();
			
			// Verifica se possui os dados na sessão
			$param = $this->_request->getParam($name_name);
			if($param > 0) {
				// Busca a descricao
				$label = $this->_request->getParam($name_name . "_label");
				
				// Seta os valores no campo
				$element->setAttrib("ac_label", $label);
				$element->setValue($param);
			}
			else {
				// Popula os campos caso estiver editando
				if(count($where) > 0) {
					// Verifica se é um autocomplete
					if(strlen($element->getAttrib("ac")) > 0) {
						// Busca os modelos referencia
						$reference_autocomplete = $element->getAttrib("ac");
						$reference_ref = $element->getAttrib("ac_ref");
						
						// Cria o objeto model
						$model_ref = new $reference_ref();
						
						// Busca a coluna descrição
						$model_final = new $reference_autocomplete();
						$description_column = $model_final->getDescription();
					
						// Busca o valor
						$label = $this->_request->getParam($name_name."_label", -1);
						
						// Busca o campo da chave primaria
						$primary_field = $model_final->getPrimaryField();
				
						// Busca os campos
						foreach($primary_field as $field) {
							$return = $this->_model->fetchAll($where);
							
							// Busca o label e o valu
							$label = $return->current()->findParentRow($reference_ref)->findParentRow($reference_autocomplete)->$description_column;
							$value = $return->current()->findParentRow($reference_ref)->findParentRow($reference_autocomplete)->$field;
						}
						
						// Seta os valores no campo
						$element->setAttrib("ac_label", $label);
						$element->setValue($value);
					}
				}
			}
			
			// Adiciona o elemento ao formulario
			$form->addElement($element);
		}
		
		// Percorre os elementos do form para formata-los
		foreach($form->getElements() as $element) {
			// Busca o tipo do campo
			$field_type = $element->getAttrib("field-type");
			$value = $element->getValue();
			$name_name = $element->getName();
			
			// Verifica se é decimal
			switch($field_type) {
				case "decimal":
					$value = number_format($value, 2, ",", ".");
					break;
					
				case "date":
					// Separa a hora da data
					$values = explode(" ", $value);
					
					// Trata a data
					$date = implode("/", array_reverse(explode("-", $values[0])));
					
					// Verifica se a data existe
					if($date == "00/00/0000") {
						$date = "";
					}
					
					// Junta a data
					$value = $date;
					break;
			}
			
			// Seta o valor no campo
			$form->getElement($name_name)->setValue($value);
		}
		
		// Percorre os parametros obrigatorios
		$url = $form->getElement("cancel")->getAttrib("data-url");
		foreach($this->_requiredParam as $param => $value) {
			$form->setAction($form->getAction() . "/" . $param . "/" . $value);
			$url .= "/" . $param . "/" . $value;
		}
		
		// Verifica se tem parametro de pagina
		if($this->_request->getParam("page", 0) > 0) {
			$url .= "/page/" . $this->_request->getParam("page", 0);
			$form->setAction($form->getAction() . "/page/" . $this->_request->getParam("page", 0));
		}
		
		// Adiciona a url ao cancelar
		$form->getElement("cancel")->setAttrib("data-url", $url);
	
		// Assina as variaveis
		$this->view->id = $id;
		$this->view->requireParam = $this->_requiredParam;
		$this->view->form = $form;
		$this->view->submitLabel = $form->getElement("submit")->getLabel();
		$this->view->module = $this->_request->getParam("module");
		$this->view->controller = $this->_request->getParam("controller");
	}
	
	/**
	 * Ação para remoção de registros
	 * 
	 * @access protected
	 * @name deleteAction
	 */
	protected function deleteAction() {
		// Desabilita o layout
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		// Busca o campo da chave primaria
		$primary_field = $this->_model->getPrimaryField();

		// Cria os parametros
		$where = array();
		foreach($primary_field as $field) {
			$where[$field . " = ?"] = (int)$this->_request->getParam($field, 0);
		}
		
		// Percorre os campos de multipla seleção
		foreach($this->_model->getMultipleFields() as $multiple_name) {
			$autocomplete = $this->_model->getAutocomplete($multiple_name);
			
			$model = new $autocomplete['middle_model']();
			
			$model->delete($where);
		}
		
		
		// Hook para antes da exclusão
		$this->doBeforeDelete();
		
		// Busca os dados do registro deletado
		$data = $this->_model->fetchRow($where)->toArray();
		
		//
		try {
			// Deleta o item
			$this->_model->delete($where);
		} catch (Exception $e) {
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->_helper->json(array('result' => FALSE, 'message' => $e->getMessage()));
			}
			else {
				throw new Zend_Controller_Action_Exception($e->getMessage(), $e->getCode());
			}
		}
		
		// Cria o log
		try {
			$this->doDatabaseLog("DELETE", $data);
		} catch(Exception $e) {
			$json_message = "Não foi possivel salvar o log";
		}
		
		
		//
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->_helper->json(array('result' => TRUE, 'message' => $json_message));
			die();
		}
		
		// Redireciona o usuario para a listagem
		$this->_helper->redirector("list", NULL, NULL, $this->_requiredParam);
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
		$insert_data['tabela'] = $this->_model->getTableName();
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
	
	/**
	 * Adiciona parametros obrigatórios da tela
	 *
	 * @name setRequiredParam
	 * @param string $name Nome do parametro
	 * @param string $value Valor do parametro
	 */
	public function setRequiredParam($name, $value) {
		// Armazena o parametro
		$this->_requiredParam[$name] = $value;
	}
	
	/**
	 * Hook para ser executado antes do insert
	 * 
	 * @access protected
	 * @name doBeforeInsert
	 * @param array $data Vetor com os valores à serem inseridos
	 * @return array
	 */
	protected function doBeforeInsert($data) {
		return $data;
	}
	
	/**
	 * Hook para ser executado depois do insert
	 * 
	 * @access protected
	 * @param integer $id Id gerado pelo insert
	 * @name doAfterInsert
	 */
	protected function doAfterInsert($id=0) {
	}
	
	/**
	 * Hook para ser executado depois do atualização
	 * 
	 * @access protected
	 * @name doAfterUpdate
	 */
	protected function doAfterUpdate() {
	}
	
	/**
	 * Hook para ser executado antes do update
	 * 
	 * @access protected
	 * @name doBeforeUpdate
	 * @param array $data Vetor com os valores à serem atualizados
	 * @return array
	 */
	protected function doBeforeUpdate($data) {
		return $data;
	}
	
	/**
	 * Hook para ser executado antes da listagem dos registros
	 * 
	 * @access protected
	 * @param Zend_Db_Select $select Objeto select
	 * @name doBeforeList
	 */
	protected function doBeforeList($select=NULL) {
		return $select;
	}
	
	/**
	 * Hook para ser executado antes da exclusão
	 * 
	 * @access protected
	 * @name doBeforeDelete
	 */
	protected function doBeforeDelete() {
	}
	
	/**
	 * Cria icones na listagem de forma dinamica
	 *  
	 * @param string $value Nome do icone
	 * @param string $class Classe CSS do icone
	 * @param array $url Vetor com as posições/valores do module,controller,action
	 * @param boolean $clear Valor de deve resetar os parametros
	 */
	public function createIcon($value, $class, $url, $clear=FALSE) {
		// Formata a posição do vetor
		$icon = array();
		$icon['value'] = $value;
		$icon['class'] = $class;
		$icon['url'] = $url;
		$icon['clear'] = $clear;
		
		// Armazena na variavel global
		$this->__listExtraIcons[] = $icon;
	}
	
	/**
	 * Hook para manipulação do formulário na sua criação
	 * 
	 * @name doOnCreateForm
	 * @param Zend_Form $form Formulario ja criado pelo model
	 * @return Zend_Form
	 */
	protected function doOnCreateForm($form) {
		// Retorna o proprio formulário 
		return $form; 
	}
	
	/**
	 * Hook para manipulação do formulário após sua criação
	 * 
	 * @name doAfterCreateForm
	 * @return Zend_Form
	 */
	protected function doAfterCreateForm() {
		// Cria o formulario temporario
		$form = new Zend_Form();
		
		// Retorna o proprio formulário 
		return $form; 
	}
	
	/**
	 * Hook para manipulação dos dados antes de popular o formulário
	 * 
	 * @name doBeforePopulate
	 * @return array
	 */
	protected function doBeforePopulate($data) {
		
		// Retorna o proprio dados 
		return $data; 
	}
}
