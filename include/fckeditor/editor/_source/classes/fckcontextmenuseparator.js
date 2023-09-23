var FCKContextMenuSeparator = function()
{
}

FCKContextMenuSeparator.prototype.CreateTableRow = function( targetTable )
{
	// Creates the <TR> element.
	this._Row = targetTable.insertRow(-1) ;
	this._Row.className = 'CM_Separator' ;
	
	var oCell = this._Row.insertCell(-1) ;
	oCell.className = 'CM_Icon' ;
	
	var oDoc = targetTable.ownerDocument || targetTable.document ;
	
	oCell = this._Row.insertCell(-1) ;
	oCell.className = 'CM_Label' ;
	oCell.appendChild( oDoc.createElement( 'DIV' ) ).className = 'CM_Separator_Line' ;
}

FCKContextMenuSeparator.prototype.SetVisible = function( isVisible )
{
	this._Row.style.display = isVisible ? '' : 'none' ;
}

FCKContextMenuSeparator.prototype.RefreshState = function()
{
	// Do nothing... its state doesn't change.
} 
