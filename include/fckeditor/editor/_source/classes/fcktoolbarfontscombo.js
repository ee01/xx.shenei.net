var FCKToolbarFontsCombo = function( tooltip, style )
{
	this.Command	= FCKCommands.GetCommand( 'FontName' ) ;
	this.Label		= this.GetLabel() ;
	this.Tooltip	= tooltip ? tooltip : this.Label ;
	this.Style		= style ? style : FCK_TOOLBARITEM_ICONTEXT ;
}

// Inherit from FCKToolbarSpecialCombo.
FCKToolbarFontsCombo.prototype = new FCKToolbarSpecialCombo ;

FCKToolbarFontsCombo.prototype.GetLabel = function()
{
	return FCKLang.Font ;
}

FCKToolbarFontsCombo.prototype.CreateItems = function( targetSpecialCombo )
{
	var aFonts = FCKConfig.FontNames.split(';') ;
	
	for ( var i = 0 ; i < aFonts.length ; i++ )
		this._Combo.AddItem( aFonts[i], '<font face="' + aFonts[i] + '" style="font-size: 12px">' + aFonts[i] + '</font>' ) ;
}