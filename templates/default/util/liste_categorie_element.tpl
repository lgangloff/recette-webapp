<ul id="{$ulid}"{if $collapse == "true" && $second == "true"}style="display: none"{/if}>
{foreach item=categorie from=$element}
    <li id="node{$categorie.id_categorie}" >
		<a {if $categorie.id_categorie == $smarty.get.cat_id}class="selected" {/if}
			href="index.php?q=recette/list?categorie={$categorie.id_categorie}"
			class="level{$level}">
			{$categorie.libelle}
		</a>
		<!--a href="#">{$categorie.libelle}</a-->
		{include file="./util/liste_categorie_element.tpl" 
			element=$categorie.fils 
			ulid="" collapse=$collapse second=true
			level=($level+1)}
	</li>
{/foreach}
</ul>