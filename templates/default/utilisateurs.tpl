<h2>Gestion des utilisateurs</h2>
<div id="utilisateurs">
	<fieldset>
		<legend>Liste des utilisateurs</legend>
		<form action="index.php?q=utilisateur/update" method="POST">
			<table>
				<thead>
					<tr>
						<th>Login</th>
						<th>Email</th>
						<th>Rôle</th>
						<th width="1%">Activé</th>
					</tr>
				</thead>
				<tbody>
					{foreach item=u from=$utilisateurs name=table_row}
					<tr class="{cycle values='even,odd'}">
						<td>
							<input name="utilisateurs_id[]" value="{$u.id_utilisateur}" type="hidden"/>
							{$u.login}
						</td>
						<td>
							<input name="utilisateurs_email[]" value="{$u.email}" />
						</td>
						<td>
							<input id="utilisateurs_roles_{$u.id_utilisateur}" name="utilisateurs_roles[]" type="hidden" value="{$u.roles}" />
							<div class="cancelWidth">
								<input type="radio" value="ADMIN" 
									name="utilisateurs_roles_{$u.id_utilisateur}"  {if $u.roles == 'ADMIN'}checked {/if} 
									onclick="document.getElementById('utilisateurs_roles_{$u.id_utilisateur}').value='ADMIN'"/><label>Admin.</label>
								<input type="radio" value="GEST" 
									name="utilisateurs_roles_{$u.id_utilisateur}" {if $u.roles == 'GEST'}checked {/if}
									onclick="document.getElementById('utilisateurs_roles_{$u.id_utilisateur}').value='GEST'"/><label>Gest.</label>
								<input type="radio" value="USER" 
									name="utilisateurs_roles_{$u.id_utilisateur}" {if $u.roles == 'USER'}checked {/if} 
									onclick="document.getElementById('utilisateurs_roles_{$u.id_utilisateur}').value='USER'"/><label>Util.</label>
							</div>
						</td>
						<td>
							<input id="utilisateurs_enable_{$u.id_utilisateur}" 
								name="utilisateurs_enable[]" value="{$u.enable}" type="hidden"/>
							<input type="checkbox" value="{$u.enable}" 
									name="utilisateurs_enable_{$u.id_utilisateur}" {if $u.enable== 1}checked {/if} 
									onclick="document.getElementById('utilisateurs_enable_{$u.id_utilisateur}').value=this.checked ? '1' : '0'"/>
								
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
			<input type="submit" value="Enregistrer">
		</form>
	</fieldset>
	<div class="addElement">
		<fieldset>
			<legend>Ajout d'un utilisateur</legend>
			<form method="POST" action="index.php?q=utilisateur/add">
				<div class="right">
					<label>Login:</label><input type="text" name="utilisateur_login" value="{$utilisateur.login}"/><br>
					<label>Email:</label><input type="text" name="utilisateur_email" value="{$utilisateur.email}"/><br>
				</div>
				<div class="left">
					<input type="radio" value="ADMIN" name="utilisateur_role"  {if $utilisateur.roles == 'ADMIN'}checked {/if} /><label>Administrateur</label><br>
					<input type="radio" value="GEST" name="utilisateur_role" {if $utilisateur.roles == 'GEST'}checked {/if}/><label>Gestionnaire</label><br>
					<input type="radio" value="USER" name="utilisateur_role" {if $utilisateur.roles != 'GEST' and $utilisateur.roles != 'ADMIN'}checked {/if} /><label>Utilisateur</label><br>
				</div>
				<input type="submit" value="Ajouter"/>
			</form>
		</fieldset>
	</div>
</div>