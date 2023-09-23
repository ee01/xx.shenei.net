var FCKToolbarFontSizeCombo = function( tooltip, style )
{
	this.Command	= FCKCommands.GetCommand( 'FontSize' ) ;
	this.Label		= this.GetLabel() ;
	this.Tooltip	= tooltip ? tooltip : this.Label ;
	this.Style		= style ? style : FCK_TOOLBARITEM_ICONTEXT ;
}

// Inherit from FCKToolbarSpecialCombo.
FCKToolbarFontSizeCombo.prototype = new FCKToolbarSpecialCombo ;

FCKToolbarFontSizeCombo.prototype.GetLabel = function()
{
	return FCKLang.FontSize ;
}

FCKToolbarFontSizeCombo.prototype.CreateItems = function( targetSpecialCombo )
{
	targetSpecialCombo.FieldWidth = 70 ;
	
	var aSizes = FCKConfig.FontSizes.split(';') ;
	
	for ( var i = 0 ; i < aSizes.length ; i++ )
	{
		var aSizeParts = aSizes[i].split('/') ;
		this._Combo.AddItem( aSizeParts[0], '<font size="' + aSizeParts[0] + '">' + aSizeParts[1] + '</font>', aSizeParts[1] ) ;
	}
}