<div class="zend_form">
	<form enctype="multipart/form-data" action="{$basePath}/user/usuarios/trocarsenha" method="post">
	<div class="form-buttons">
			<input type="submit" name="submit" id="submit" value="Trocar" />
			<button name="cancel" id="cancel" type="button" data-url="{$basePath}/user">Cancelar</button>
		</div>
		<div class="form-tab">
			<ul>
				<li><a href="#tab-geral">Geral</a></li>
			</ul>
		
			<div id="tab-geral">
				<div>
					<div id="element-senha_atual">
						<label for="senha_atual" class="required">Senha Atual</label>
						<input type="password" name="senha_atual" id="senha_atual" value="" field-type="text" class="varchar string" />
					</div>
					
					<div id="element-senha_nova">
						<label for="senha_nova" class="required">Nova Senha</label>
						<input type="password" name="senha_nova" id="senha_nova" value="" field-type="text" class="varchar string" />
					</div>
				
					<div id="element-senha_confirmar">
						<label for="senha_confirmar" class="required">Confirme Senha</label>
						<input type="password" name="senha_confirmar" id="senha_confirmar" value="" field-type="text" class="varchar string" />
					</div>
				</div>
			</div>
		</div>
		
	</form>
</div>
