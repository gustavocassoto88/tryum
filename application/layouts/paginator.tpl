<div class="pagination">
<!--	<p>Mostrando {$lastItemNumber} de {$totalItemCount} registro(s)</p>-->
	<ul>
		<li><a {if isset($previous)}href="{$this->url(['page' => $this->first])}"{/if} class="border-left"><span>«</span></a></li>
		
		<li><a {if isset($previous)}href="{$this->url(['page' => $previous])}"{/if} class="no-border"><span>&lsaquo;</span></a></li>
		
		{foreach from=$pagesInRange item=page}
			{if $page != $this->current}
				<li><a href="{$this->url(['page' => $page])}" class="no-border"><span>{$page}</span></a></li>
			{else}
				<li><a class="current no-border" href="{$this->url(['page' => $page])}"><span>{$page}</span></a></li>
			{/if}
		{/foreach}
		
		<li><a {if isset($next)}href="{$this->url(['page' => $next])}"{/if} class="no-border"><span>&rsaquo;</span></a></li>
		
		<li><a {if isset($next)}href="{$this->url(['page' => $last])}"{/if} class="border-right"><span>»</span></a></li>
	</ul>
</div>
