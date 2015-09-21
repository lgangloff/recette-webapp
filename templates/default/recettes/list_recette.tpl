<h2>Liste des recettes</h2>
{foreach item=recette from=$recettes}
		
		<div onclick="document.location.href='index.php?q=recette/display&id={$recette.id_recette}&categorie={$smarty.get.categorie}'" 
			class="recette_thumbs">
			<h3 title="{$recette.titre}">{$recette.titre|truncate:50:"..."}</h3>
			{if $recette.hasImage}
			<img src="index.php?q=recette/image&id_recette={$recette.id_recette}" height="60px"/>			
			{/if}
			{if isset($recette.resume)}
			<p>
				{$recette.resume|truncate:320:"..."}
			</p>
			{/if}
			<span class="notation">
				<ul class="note_etoile">								
					{section name=foo start=1 loop=7}
						{if $smarty.section.foo.index <= $recette.note}
							<li class="on"></li>
						{else}
							<li></li>
						{/if}
					{/section}
				</ul>
			</span>
			<span class="date_creation">
				Ajouté le {$recette.date_creation|date_format:"%d/%m/%Y"}
			</span>
		</div>
{/foreach}