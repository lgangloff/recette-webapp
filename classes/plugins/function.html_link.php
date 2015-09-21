<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Fichier :  function.html_link.php
 * Type :     fonction
 * Nom :      html_link
 * Rôle :     Affiche un lien si les droits d'accès le permettent
 * -------------------------------------------------------------
 */
function smarty_function_html_link($params, &$smarty)
{
	
	$href = $params['href'];	
	$title = $params['title'];
	$params = $params['params'];

	$conf = $smarty->getTemplateVars('conf');
	
	if ($conf->hasAccess($conf->getMapping($href), $_SESSION)){
		return "<a href=\"index.php?q=$href$params\" title=\"$title\">$title</a>";
	}	
}
?> 