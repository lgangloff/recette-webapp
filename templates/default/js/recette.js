function onmouseover(el) {

	if (!el.id)
		return;
	
	over = true;
	max = el.id.substr(el.id.length - 1, el.id.length);
	pref = el.id.substr(0, el.id.length - 1);
	for (i = 1; i <= 6; i++) {
		if (i <= max)
			document.getElementById(pref + i).className = "on";
		else
			document.getElementById(pref + i).className = "";
	}
}
function onmouseout(el) {
	if (!el.id)
		return;
	pref = el.id.substr(0, el.id.length - 1);
	iSelected = 0;
	for (i = 0; i <= 6; i++) {
		if (document.getElementById("value_" + pref + i).checked) {
			iSelected = i;
			break;
		}
	}
	for (i = 1; i <= 6; i++) {
		if (i <= iSelected)
			document.getElementById(pref + i).className = "on";
		else
			document.getElementById(pref + i).className = "";
	}
}
function onclick(el) {
	max = el.id.substr(el.id.length - 1, el.id.length);
	pref = el.id.substr(0, el.id.length - 1);
	document.getElementById("value_" + pref + max).checked = true;
}

function clickCheckBox(cb, level){
	var nextUl = cb.parentNode.getElementsByTagName("ul")[0];
	if (cb.checked){
		nextUl.className += " checked";
		checkParent(cb, level-1);
	}
	else{
		uncheckChild(cb, level+1);
	}
}

function checkParent(cb, level){
	if (level>=-1){
		var cbParent = cb.parentNode.parentNode.parentNode.getElementsByTagName("input")[0];
		cbParent.checked = true;
		clickCheckBox(cbParent, level-1);
	}
}
function uncheckChild(cb, level){
	var inputChilds = cb.parentNode.getElementsByTagName("input");
	var ulChilds = cb.parentNode.getElementsByTagName("ul");
	for ( var int = 0; int < inputChilds.length; int++) {
		var child = inputChilds[int];
		child.checked = false;
	}
	for ( var int = 0; int < ulChilds.length; int++) {
		var child = ulChilds[int];
		if (child.className.indexOf(" ")>0)
			child.className = child.className.substr(0,child.className.indexOf(" "));
	}
}

function removeRow(tableid, button){
    row = button.parentNode.parentNode;
    tBody = document.getElementById(tableid).getElementsByTagName('tbody')[0];
    rows = tBody.getElementsByTagName('tr');
    if(rows.length > 1){
        row.parentNode.removeChild(row);
    }
    refresh(tBody);
}

function addRow(tableid){
    tBody = document.getElementById(tableid).getElementsByTagName('tbody')[0];
    rows = tBody.getElementsByTagName('tr');
    lastRow = rows[rows.length-1];                                                           
    tBody.appendChild(lastRow.cloneNode(true));
   
   
    refresh(tBody);
}

function refresh(tbody){

    rows = tBody.getElementsByTagName('tr');
   
    for(i=0; i<rows.length;i++){                   
        row = rows[i];
        row.className = (i%2 == 0 ? "even" : "");
        cols = row.getElementsByTagName('td');
        cols[0].innerHTML = (i+1);
    }
   
}