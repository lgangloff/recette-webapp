<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Des recettes bio €co !</title>
		
		<script type="text/javascript" src="{$contextpath}/js/recette.js"></script>
		{if $include_page=="categories.tpl"}
		<script type="text/javascript" src="{$contextpath}/js/ajax.js"></script>
		<script type="text/javascript" src="{$contextpath}/js/context-menu.js"></script><!-- IMPORTANT! INCLUDE THE context-menu.js FILE BEFORE drag-drop-folder-tree.js -->
		<script type="text/javascript" src="{$contextpath}/js/drag-drop-folder-tree.js"></script>
		{/if}
		<link rel="stylesheet" href="{$contextpath}/css/style.css" type="text/css">
		<link rel="stylesheet" href="{$contextpath}/css/context-menu.css" type="text/css">
		
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-11321020-2']);
			_gaq.push(['_trackPageview']);
			if (document.location.href.indexOf("localhost")==-1){
				(function() {
					var ga = document.createElement('script'); 
					ga.type = 'text/javascript'; 
					ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(ga, s);
				})();
			}		
		</script>	
	</head>
	<body>
	
		{include file="./menu/menu_gauche.tpl"}
			
		<div id="right">
		
			{include file="./menu/menu_haut.tpl"}
			
			<div id="content" class="shadow">
				<div id="breadcrumb">
					Vous êtes ici: <a href="index.php">Accueil</a>
					{foreach item=cur from=$filariane}
						>> <a href="index.php?q=recette/list?categorie={$cur.id_categorie}">{$cur.libelle}</a>
					{/foreach}
				</div>
				{if count($erreur) gt 0 or count($message) gt 0}		
				<div id="message" class="shadow">
					{if $erreur}
						<ul class="erreur">
							{foreach item=lib from=$erreur}
								<li>{$lib}</li>
							{/foreach}
						</ul>
					{/if}
					{if $message}
						<ul class="message">
							{foreach item=lib from=$message}
								<li>{$lib}</li>
							{/foreach}
						</ul>
					{/if}
				</div>
				{/if}
				{include file="$include_page"}
			</div>
		</div>
		
		<div id="footer" class="">
		</div>

	</body>
</html>