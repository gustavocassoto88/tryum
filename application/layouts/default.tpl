</body>
</html>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		{$this->headTitle()}
		{$this->headMeta()}
		{$this->headLink()}
		{$this->headScript()}
		<script type="text/javascript">
			document.basePath = '{$basePath}';
		</script>
	</head>


	<body>
		<div id="faixa-topo"></div>

		<header id="header">
			<div id="header-hack-margin">
				<div class="container_24" id="header-container">
					<a id="header-logo" class="prefix_1 grid_6" href="{$this->Url([], "index", true)}" title="Tryum Soluções Web"> 
						<img src="{$basePath}/arquivos/default/img/layout/logo.png" alt="Tryum Soluções Web" width="227" height="85" id="header-logo-img">
						<img src="{$basePath}/arquivos/default/img/layout/logo-y.png" alt="Tryum Soluções Web" width="43" height="42" id="header-logo-y">
					</a><!-- /LOGO -->

					<nav id="header-nav" class="prefix_2">
						<ul id="nav-list">

							<li class="grid_2 suffix_1 nav-item">
								<a href="{$this->Url([], "index", true)}" class="nav-link {if $currentAction == "index"}ativo{/if}" title="Página Inicial">
									<span class="nav-icon inicio"></span>
									<span class="nav-text">Início</span>
								</a>
							</li>

							<li class="grid_2 suffix_1 nav-item">
								<a href="#" class="nav-link {if $currentAction == "sobre"}ativo{/if}" title="Saiba mais sobre a empresa">
									<span class="nav-icon sobre"></span>
									<span class="nav-text">Sobre</span>
								</a>
							</li>

							<li class="grid_2 suffix_1 nav-item">
								<a href="#" class="nav-link {if $currentAction == "cases"}ativo{/if}" title="Conheça nossos cases!">
									<span class="nav-icon cases"></span>
									<span class="nav-text">Cases</span>
								</a>
							</li>

							<li class="grid_2 suffix_1 nav-item">
								<a href="#" class="nav-link {if $currentAction == "orcar"}ativo{/if}" title="Faça um orçamento">
									<span class="nav-icon orcar"></span>
									<span class="nav-text">Orçar</span>
								</a>
							</li>

							<li class="grid_2 nav-item">
								<a href="{$this->Url([], "contato", true)}" class="nav-link {if $currentAction == "contato"}ativo{/if}" title="Entre em contato conosco!">
									<span class="nav-icon contato"></span>
									<span class="nav-text">Contato</span>
								</a>
							</li>

						</ul><!--/NAV-LIST-->

					</nav><!--/ MENU / NAV -->

				</div><!--/header-container-->
			</div>
		</header>

		{$this->layout()->content}
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

		<footer id="footer">
			<div class="container_24" itemscope itemtype="http://data-vocabulary.org/Organization">
				<div class="grid_6 prefix_1 suffix_1" id="footer-copyright">
					<span class="copyright-title" itemprop="name">Tryum - Soluções Web</span>
					<span class="copyright-copy">Todos os Direitos reservados @{date('Y')}</span>
				</div>

				<div class="grid_1 footer-separator">
					<div class="footer-separator-img"></div>
				</div>

				<div class="grid_5 prefix_1 suffix_1" id="footer-contacts">
					<span class="contacts-tel" itemprop="tel">(43) 9603-1606</span>
					<span class="contacts-tel" itemprop="tel">(44) 9819-7312</span>
				</div>
				<span itemprop="locality" class="hide">Londrina</span>
				<span itemprop="region" class="hide">PR</span>

				<div class="grid_1 footer-separator">
					<div class="footer-separator-img"></div>
				</div>

				<div class="grid_5 prefix_1" id="footer-social">
					<a href="#" target="_blank" class="social-fb hide-text" title="Curta nossa página no facebook!">Facebook</a>
					<a href="mailto:email@provedor.com.br" target="_blank" class="social-mail hide-text" title="Envie um email para nós!">Email</a>
				</div>
			</div>
		</footer>
	</body>
</html>
