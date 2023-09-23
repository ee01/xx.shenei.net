var FCKToolbarSpecialCombo = function()
{
	this.SourceView			= false ;
	this.ContextSensitive	= true ;
}

function FCKToolbarSpecialCombo_OnSelect( itemId, item )
{
	this.Command.Execute( itemId, item ) ;
}

FCKToolbarSpecialCombo.prototype.CreateInstance = function( parentToolbar )
{
	this._Combo = new FCKSpecialCombo( this.GetLabel(), this.FieldWidth, this.PanelWidth, this.PanelMaxHeight ) ;

	this._Combo.Tooltip	= this.Tooltip ;
	this._Combo.Style	= this.Style ;
	
	this.CreateItems( this._Combo ) ;

	this._Combo.Create( parentToolbar.DOMRow.insertCell(-1) ) ;

	this._Combo.Command = this.Command ;
	
	this._Combo.OnSelect = FCKToolbarSpecialCombo_OnSelect ;
}

function FCKToolbarSpecialCombo_RefreshActiveItems( combo, value )
{
	combo.DeselectAll() ;
	combo.SelectItem( value ) ;
	combo.SetLabelById( value ) ;
}

FCKToolbarSpecialCombo.prototype.RefreshState = function()
{
	// Gets the actual state.
	var eState ;
	

		var sValue = this.Command.GetState() ;

		if ( sValue != FCK_TRISTATE_DISABLED )
		{
			eState = FCK_TRISTATE_ON ;
			
			if ( this.RefreshActiveItems )
				this.RefreshActiveItems( this._Combo, sValue ) ;
			else
			{
				if ( this._LastValue == sValue )
					return ;
	
				this._LastValue = sValue ;

				FCKToolbarSpecialCombo_RefreshActiveItems( this._Combo, sValue ) ;
			}
		}
		else
			eState = FCK_TRISTATE_DISABLED ;

	if ( eState == this.State ) return ;
	
	if ( eState == FCK_TRISTATE_DISABLED )
	{
		this._Combo.DeselectAll() ;
		this._Combo.SetLabel( '' ) ;
	}

	// Sets the actual state.
	this.State = eState ;

	// Updates the graphical state.
	this._Combo.SetEnabled( eState != FCK_TRISTATE_DISABLED ) ;
}

FCKToolbarSpecialCombo.prototype.Enable = function()
{
	this.RefreshState() ;
}

FCKToolbarSpecialCombo.prototype.Disable = function()
{
	this.State = FCK_TRISTATE_DISABLED ;
	this._Combo.DeselectAll() ;
	this._Combo.SetLabel( '' ) ;
	this._Combo.SetEnabled( false ) ;
}