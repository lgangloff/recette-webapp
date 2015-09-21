<ul id="menu">
	{if isset($smarty.session.login)}
	<li id="menu_login" class="block_menu shadow">
		<h3>Bienvenue {$smarty.session.login}</h3>
		<ul>
			<li>{html_link href="disconnect" title="Se déconnnecter"}</li>
		</ul>
	</li>
	{/if}
	
	{has_permission urls="recette/create,categorie,utilisateur/list"}
	<li class="block_menu shadow">
		<ul>	
			<li>{html_link href="recette/create" title="Ajouter une recette..."}</li>
			<li>{html_link href="categorie" title="Gérer les catégories..."}</li>	
			<li>{html_link href="utilisateur/list" title="Gérer les utilisateurs..."}</li>	
		</ul>	
	</li>
	{/has_permission}
		
	{foreach item=current from=$inv_filariane name=farianne}
		{if count($souscategories[$current.id_categorie]) > 0}
		<li id="menu_categorie" class="block_menu shadow">
			<h3>{$current.libelle}</h3>
			<ul>
			{foreach item=cat from=$souscategories[$current.id_categorie] name=temp}
			    <li>
					<a {if $cat.id_categorie == $inv_filariane[$smarty.foreach.farianne.index-1].id_categorie}class="selected"{/if}
						href="index.php?q=recette/list?categorie={$cat.id_categorie}">
						{$cat.libelle}
					</a>
				</li>
			{/foreach}
			</ul>
		</li>
		{/if}
	{/foreach}
	{if !isset($smarty.session.login)}
	<li id="menu_login" class="block_menu shadow">
		<h3>Connexion</h3>
		<form action="index.php?q=login" method="POST">
			<label>Login:</label><input type="text" name="login">
			<label>Mot de passe:</label><input type="password" name="password">
			<input type="submit" value="Se connecter">
		</form>
	</li>
	{/if}
</ul>