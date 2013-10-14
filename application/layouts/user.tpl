<!doctype html>
<html>
	<head>
		{$this->headTitle()}
		{$this->headMeta()}
		{$this->headLink()}
		{$this->headScript()}
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript">
			document.basePath = '{$basePath}';
			document.openedController = '{$openedController}';
		</script>
	</head>
	<body>
		<div class="site-layout">
			<div class="site-top-nav">
				{$this->navigation()->menu()}
				<ul class="login-info">
					<li><a href="#">{$logged_usuario['nome']}</a></li>
					<li><a href="{$this->url(['module'=>"user", 'controller'=>"usuarios", 'action'=>"logout"], "default", TRUE)}" class="nav-logout">Sair</a></li>
				</ul>
			</div>
			
			<div class="site-contents">
				<div class="site-left-breadcrumb">
					{$this->navigation()->breadcrumbs()->setLinkLast(TRUE)->setSeparator(' - ')->setMinDepth(-1)->render()}
				</div>
				<div class="site-middle">
					<div class="site-content">
						{$this->layout()->content}
					</div>
				</div>
			</div>
			
			<div class="site-footer"></div>
		</div>
		
		{if $success|default:"" != ""}
			<div id="msg-box" class="msg-success">
				{$success}
			</div>
		{/if}
		{if $error|default:"" != ""}
			<div id="msg-box" class="msg-error">
				{$error}
			</div>
		{/if}
	</body>
</html>