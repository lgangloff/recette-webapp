<h2>{$recette.titre}</h2>
<div id="recette_detail">
	{has_permission urls="recette/delete, recette/edit"}
	<div class="options">		
		<ul>
			{has_permission urls="recette/delete"}
			<li><a href="index.php?q=recette/delete&id={$recette.id_recette}" 
				onclick="return confirm('Êtes-vous sur de vouloir supprimer {$recette.titre} ?')" class="supprimer"></a></li>
			{/has_permission}
			{has_permission urls="recette/edit"}
			<li><a href="index.php?q=recette/edit&id={$recette.id_recette}&categorie={$smarty.get.categorie}" class="modifier"></a></li>
			{/has_permission}
		</ul>		
	</div>
	{/has_permission}
	{if $recette.hasImage}
	<img alt="" src="index.php?q=recette/image&id_recette={$recette.id_recette}" width="250px"/>
	{/if}
	<div class="coin_gauche">
		<div class="temps">{$recette.temps}</div>
		<ul>	
		{foreach item=cat from=$recette.categories}
			<li>{$cat.libelle}</li>
		{/foreach}
		</ul>
		{section name=c loop=$recette.criteres}
			<div class="notation">
				<label>{$recette.criteres[c].libelle}</label>
				<ul class="note_etoile">								
					{section name=foo start=1 loop=7}
						{if $smarty.section.foo.index <= $recette.criteres[c].valeur}
							<li class="on"></li>
						{else}
							<li></li>
						{/if}
					{/section}
				</ul>
			</div>
		{/section}
	</div>
	
	{$recette.resume}
	
	<div style="clear: both;"></div>
		

	<h3>Ingrédients</h3>
	<div class="ingredients">
		<table>
			<thead><tr><th width="5%">Phase</th><th>Nom</th><th width="15%">Quantité</th><th width="15%">Quantité</th></tr></thead>
			<tbody>
				{foreach item=ing from=$recette.ingredients name=table_row}
					<tr class="{cycle values='even,odd'}">
					{foreach key=k item=v from=$ing}
						<td>
							{$v}
						</td>
					{/foreach}
					</tr>
				{/foreach}			
			</tbody>
		</table>
	</div>
	
	{$recette.modeoperatoire}
	
	{if $recette.source}
		Source: <a href="{$recette.source}">{$recette.source}</a>
	{/if}
</div>