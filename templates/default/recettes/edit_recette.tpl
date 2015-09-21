{if $mode=="creation"}
	<h2>Ajouter une recette</h2>
{elseif $mode=="edition"}
		<h2>Modification de "{$recette.titre}"</h2>	
{/if}

<script type="text/javascript" src="{$contextpath}/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left"
	});
</script>
<div id="recette_detail">
	<form class="formulaire" method="POST" action="index.php?q=recette/save&categorie={$smarty.get.categorie}" enctype="multipart/form-data">
		<input type="hidden" name="recette_id" value="{$recette.id_recette}" >
		
		<div style="float: left; width: 200px;">
			<fieldset class="categorie">
				<legend>Catégorie</legend>
				{include file="./recettes/checkbox_sub_categorie_element.tpl" element=$sub_categories level=0 checked=false}
			</fieldset>
			<fieldset class="image">
				<legend>Image</legend>
				<input type="file" style="font-size:80%;" name="recette_image_upload">
				<input type="hidden" name="recette_image_tmp" value="{$recette.image_tmp}"/>
				{if $recette.image_tmp}
					<img alt="" src="{$recette.image_tmp}" width="170px"/>
				{else}
					<img alt="" src="index.php?q=recette/image&id_recette={$recette.id_recette}" width="170px"/>
				{/if}
			</fieldset>
		</div>
		
		<fieldset class="information">
			<legend>Informations</legend>
			
			<div>
				<label>Titre</label>
				<input style="width: 100%;" type="text" maxlength="64" name="recette_titre" value="{$recette.titre}"/>
			</div>
			<div>
				<div id="notes">
					{foreach item=critere from=$criteres}
						<div class="notation">
							<label>{$critere.libelle}</label>
							<ul class="note_etoile">
								<li id="note_{$critere.id_critere}_0" class="note0"></li>									
								{section name=foo start=1 loop=7}
								  <li id="note_{$critere.id_critere}_{$smarty.section.foo.index}"></li>
								{/section}
								
							</ul>
							<ul class="note_value">
								{section name=foo start=0 loop=7}
								  <li><input 
								  	id="value_note_{$critere.id_critere}_{$smarty.section.foo.index}" 
								  	type="radio" 
								  	name="note_{$critere.id_critere}"
								  	value="{$smarty.section.foo.index}"
								  	{if $smarty.section.foo.index == $recette.criteres[$critere.id_critere]} checked {/if}
								  	/></li>
								{/section}
							</ul>
						</div>
						<script type="text/javascript">
							for(i=0;i<=6;i++){
								e = document.getElementById("note_{$critere.id_critere}_"+i);
								{literal}
								e.onmouseover = function(){onmouseover(this)};
								e.onmouseout = function(){onmouseout(this)};
								e.onclick = function(){onclick(this)};
						        {/literal}
							}
							onmouseout(document.getElementById("note_{$critere.id_critere}_1"));
				        </script>
				        
					{/foreach}
				</div>
				<span id="temps">
					<label>Temps</label>
					<input type="text" maxlength="16" name="recette_temps" value="{$recette.temps}"/>
				</span>
			</div>
			<div style="clear: both;">
				<label>Source (http)</label>
				<input style="width: 100%;" type="text" maxlength="512" name="recette_source" value="{$recette.source}"/>
			</div>
			<div>
				<label>Résumé</label>
				<textarea style="width: 100%; height: 100px;" name="recette_resume">{$recette.resume}</textarea>
			</div>
		</fieldset>
		
		<fieldset class="ingredient">
			<legend>Ingrédients</legend>
				<div class="ingredients">
				<table border="0" width="100%" id="tIng">
					<thead><tr><th width="10px"></th><th width="20px">Phase</th><th>Nom</th><th width="50px">Quantité</th><th width="50px">Quantité</th><th width="50px"></th></tr></thead>
					<tbody>
						{foreach item=ing from=$recette.ingredient name=table_row}
							<tr class="{cycle values="even,odd"}">
								<td>
									{$smarty.foreach.table_row.iteration}
								</td>
							{foreach key=k item=v from=$ing}
								<td>
									<input type="text" name="{$k}[]" value="{$v}" />
								</td>
							{/foreach}
								<td>
									<input type="button" onclick="removeRow('tIng', this)" value="Suppr." />
								</td>
							</tr>
						{/foreach}			
					</tbody>
				</table>
			</div>
			<input type="button" onclick="addRow('tIng')" value="Ajouter un ingrédient">
		</fieldset>
		
		<fieldset class="modeoperatoire">
			<legend>Mode opératoire</legend>
			<textarea style="width: 100%; height: 400px;" name="recette_modeoperatoire">{$recette.modeoperatoire}</textarea>
		</fieldset>


		<input type="submit" value="Enregistrer la recette">
	</form>
</div>