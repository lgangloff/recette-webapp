<script type="text/javascript">
	//--------------------------------
	// Save functions
	//--------------------------------
	var ajaxObjects = new Array();

	// Use something like this if you want to save data by Ajax.
	function saveMyTree()
	{
			saveString = treeObj.getNodeOrders();
			var ajaxIndex = ajaxObjects.length;
			ajaxObjects[ajaxIndex] = new sack();
			var url = 'index.php?q=categorie/update&saveString=' + saveString;
			ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
			ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
			ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function			
		
	}
	function saveComplete(index)
	{
		document.location.reload();
	}

</script>

<h2>Gestion des catégories</h2>
<div id="categories">
	<fieldset>
		<legend>Hiérarchie des catégories</legend>
		{include file="./util/liste_categorie_element.tpl" element=$categories ulid=categorie_tree collapse=true}	
		
		<input type="button" onclick="saveMyTree()" value="Enregistrer les modifications">
	</fieldset>
	
	<fieldset>
		<legend>Ajout d'une catégorie</legend>
		<form method="POST" action="index.php?q=categorie/add">
			<label>Libellé:</label><input type="text" name="libelle"/>
			<input type="submit" value="Ajouter"/>
		</form>
	</fieldset>
	
</div>

<script type="text/javascript">	
	treeObj = new JSDragDropTree();
	treeObj.setFileNameRename("index.php?q=categorie/rename");
	treeObj.setFileNameDelete("index.php?q=categorie/delete");
	treeObj.setImageFolder("{$contextpath}/images/");
	treeObj.setTreeId('categorie_tree');
	treeObj.setMaximumDepth(7);
	treeObj.setMessageMaximumDepthReached('Maximum depth reached'); // If you want to show a message when maximum depth is reached, i.e. on drop.
	treeObj.initTree();

	treeObj.collapseAll();
</script>