<ul class="ullevel{$level}{if $checked} checked{/if}">
{foreach item=categorie from=$element}
    <li class="level{$level}">
		<input type="checkbox" value="{$categorie.id_categorie}" 
			{if $recette.categorie[{$categorie.id_categorie}]}checked {/if}
			name="recette_categorie[]" 
			onclick="clickCheckBox(this,{$level})"/>
		<label>{$categorie.libelle}</label>
		{include file="./recettes/checkbox_sub_categorie_element.tpl" 
			element=$categorie.fils 
			level={$level}+1 
			checked=$recette.categorie[{$categorie.id_categorie}]}
	</li>
{/foreach}
</ul>