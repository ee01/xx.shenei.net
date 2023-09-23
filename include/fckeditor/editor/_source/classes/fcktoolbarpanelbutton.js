var FCKToolbarPanelButton = function( commandName, label, tooltip, style )
{
	this.Command	= FCKCommands.GetCommand( commandName ) ;
	this.Label		= label ? label : commandName ;
	this.Tooltip	= tooltip ? tooltip : ( label ? label : commandName) ;
	this.Style		= style ? style : FCK_TOOLBARITEM_ONLYICON ;
	this.State		= FCK_UNKNOWN ;
	this.IconPath	= FCKConfig.SkinPath + 'toolbar/' + commandName.toLowerCase() + '.gif' ;
}

FCKToolbarPanelButton.prototype.Click = function(e)
{
	

	if ( this.State != FCK_TRISTATE_DISABLED )
	{
		this.Command.Execute(0, this.DOMDiv.offsetHeight, this.DOMDiv) ;
//			this.FCKToolbarButton.HandleOnClick( this, e ) ;
	}
		
	return false ;
}

FCKToolbarPanelButton.prototype.CreateInstance = function( parentToolbar )
{
	this.DOMDiv = document.createElement( 'div' ) ;
	this.DOMDiv.className = 'TB_Button_Off' ;

	this.DOMDiv.FCKToolbarButton = this ;
	
	var sHtml =
		'<table title="' + this.Tooltip + '" cellspacing="0" cellpadding="0" border="0">' +
			'<tr>' ;
			
	if ( this.Style != FCK_TOOLBARITEM_ONLYTEXT ) 
		sHtml += '<td class="TB_Icon"><img src="' + this.IconPath + '" width="21" height="21"></td>' ;
		
	if ( this.Style != FCK_TOOLBARITEM_ONLYICON ) 
		sHtml += '<td class="TB_Text" nowrap>' + this.Label + '</td>' ;
	
	sHtml +=
				'<td class="TB_ButtonArrow"><img src="' + FCKConfig.SkinPath + 'images/toolbar.buttonarrow.gif" width="5" height="3"></td>' +
			'</tr>' +
		'</table>' ;
	
	this.DOMDiv.innerHTML = sHtml ;

	var oCell = parentToolbar.DOMRow.insertCell(-1) ;
	oCell.appendChild( this.DOMDiv ) ;
	
	this.RefreshState() ;
}

// The Panel Button works like a normal button so the refresh state functions
// defined for the normal button can be reused here.
FCKToolbarPanelButton.prototype.RefreshState	= FCKToolbarButton.prototype.RefreshState ;
FCKToolbarPanelButton.prototype.Enable			= FCKToolbarButton.prototype.Enable ;
FCKToolbarPanelButton.prototype.Disable			= FCKToolbarButton.prototype.Disable ;