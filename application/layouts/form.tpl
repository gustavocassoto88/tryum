<div class="zend_form">
	<form enctype="multipart/form-data" action="{$form->getAction()}" method="post">
		<div class="form-buttons">
			<input type="submit" name="submit" id="submit" value="{$form->getElement('submit')->getLabel()}">
			<button name="cancel" id="cancel" type="button" data-primary="{$primary}" data-url="{$this->url(['module'=>"user", 'controller'=>$controller, 'action'=>"list"], "default", FALSE)}">Cancelar</button>
		</div>
		<div class="form-tab">
			<ul>
				<li><a href="#tab-geral">Geral</a></li>
				{foreach from=$_tabs item=_tab}
					<li><a href="#tab-{$_tab['name']}">{$_tab['name']}</a></li>
				{/foreach}
			</ul>
		
			<div id="tab-geral">
				{foreach from=$form->getElements() item=element}
					{if $element->getName() != "submit" && $element->getName() != "cancel"}
						{$element}
					{/if}
				{/foreach}
			</div>
			
			{foreach from=$_tabs item=_tab}
				<div id="tab-{$_tab['name']}">
					<div class="tab-table-list">
						{assign var=_tab_list value=$_tab['model']->fetchAll($_tab['select']->where($primary|cat:' = ?', $id))}
						{assign var=_tab_primary value=current($_tab['model']->getPrimaryField())}
						{assign var=_tab_infos value=$_tab['model']->info()}
						{assign var=_tab_columns value=$_tab['model']->getCampo()}
						
						<table width="100%" cellpadding="0" cellspacing="0" class="list">
						<thead>
							<tr>
								<th>#</th>
							{foreach $_tab_list->current() as $column => $value}
								{if $_tab['model']->getVisibility($column, 'list')}
								<th>{$_tab_columns[$column]}</th>
								{/if}
							{/foreach}
							</tr>
						</thead>
						<tbody>
						{foreach from=$_tab_list item=_tab_row}
							<tr data-url="{if count($_tab['url']) > 0}{$this->url($_tab['url'], "default", TRUE)}/{$_tab_primary}/{$_tab_row[$_tab_primary]}{/if}">
								<td>{$_tab_row[$_tab_primary]}</td>
							{foreach $_tab_row as $column => $value}
								{if $_tab['model']->getVisibility($column, 'list')}
								<td>{$this->GetColumnValue($_tab_row, $column)}</td>
								{/if}
							{/foreach}
							</tr>
						{/foreach}
						</tbody>
						</table>
					</div>
				</div>
			{/foreach}
		</div>
		
		{if $id|default:0 > 0}
		<input type=hidden name="referer_url" value="{$this->url(['module'=>"user", 'controller'=>$controller, 'action'=>"list"], "default", FALSE)}">
		{/if}
	</form>
</div>