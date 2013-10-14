<!doctype html>
<html>
	<head>
		{$this->headTitle()}
		{$this->headMeta()}
		{$this->headLink()}
		{$this->headScript()}
		<script type="text/javascript">
			document.basePath = '{$basePath}';
		</script>
	</head>
	<body>
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
	</body>
</html>
