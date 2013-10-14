<!doctype html>
<html>
	<head>
		{$this->headMeta()} {$this->headTitle()} {$this->headLink()}
		{$this->headScript()}
		<script type="text/javascript">
			document.basePath = '{$basePath}';
		</script>
	</head>
<body>
	<div id="conteudo_login">
		<div class="tryum-logo"></div>
		<div class="formulario">
			<div class="espaco"></div> 
			<form enctype="application/x-www-form-urlencoded" action="{$basePath}/user/usuarios/login" method="post">
				<div>
					<label for="login" class="required">Login</label>
					<input type="text" name="login" id="login" placeholder="Informe o usuário" field-type="text" class="string" />
				</div>
				<div>
					<label for="senha" class="required">Senha</label>
					<input type="password" name="senha" id="senha" placeholder="Informe a senha" field-type="password" class="string password" />
				</div>
				<div class="buttons">
				
					<input type="submit" name="submit" id="submit" value="Entrar" />
				</div>
			</form>
		</div>
		<div></div>
		
		{if $success|default:"" != ""}
			<div id="msg-formulario" class="msg-success">
				{$success}
			</div>
		{/if}
		{if $error|default:"" != ""}
			<div id="msg-formulario" class="msg-error">
				{$error}
			</div>
		{/if}
		
	</body>
</html>
