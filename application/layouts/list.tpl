<div class="buttons" id="buttons-bar">
	<a href="{$this->CreateUrl("form", NULL, NULL, [])}" class="btn-new border-left">Novo</a>
	<a href="{$this->CreateUrl("form", NULL, NULL, [])}/{$primary}/" class="btn-edit">Editar</a>
	<a href="{$this->CreateUrl("delete", NULL, NULL, [])}/{$primary}/" class="btn-remove border-right">Remover</a>
	{foreach from=$listExtraIcons item=icon}
		<td>
			<a class="{$icon['class']}" href="{$this->url($icon['url'], "default", $icon['clear'])}">{$icon['value']}</a>
		</td>
	{/foreach}
</div>

<div class="table-list">
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>#</th>
				{foreach $_model->getCampo() as $column=>$value}
					{if $_model->getVisibility($column, 'list')}
						<th>
							{$value}
						</th>
					{/if}
				{/foreach}
			</tr>
			<tr class="filter" data-search="{$basePath}/user/{$currentController}/search/{key($requireParam)}/{current($requireParam)}">
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				{foreach $_model->getCampo() as $column=>$value}
					{if $_model->getVisibility($column, 'list')}
						<th>
							{$form->getElement($column)}
						</th>
					{/if}
				{/foreach}
			</tr>
			
		</thead>
		<tbody>
		{foreach from=$paginator item=row}
			<tr>
				<td><input type="radio" name="{$primary}" value="{$row[$primary]}" /></td>
				<td>{$row[$primary]}</td>
				{foreach $_model->getCampo() as $column=>$value}
					{if $_model->getVisibility($column, 'list')}
					<td>
						{$this->GetColumnValue($row, $column)}
					</td>
					{/if}
				{/foreach}
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>

<div class="footer-bar">
	{$this->paginationControl($paginator, NULL, 'paginator.tpl')}
</div>