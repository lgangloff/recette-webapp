<ul id="menu_haut">
	<li><a {if !$smarty.get.categorie}class="selected"{/if} href="index.php">Accueil</a></li>	
	{foreach item=cat from=$principalesCategories}
	    <li>
			<a href="index.php?q=recette/list?categorie={$cat.id_categorie}" 
				{if $cat.id_categorie == $filariane[0].id_categorie}class="selected"{/if}>
				{$cat.libelle}
			</a>
		</li>
	{/foreach}
</ul>