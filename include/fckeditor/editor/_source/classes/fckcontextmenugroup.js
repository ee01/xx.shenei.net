var FCKContextMenuGroup = function( addSeparator, contextMenu, firstItemCommand, firstItemLabel, hasIcon )
{
	this.IsVisible = true ;
	
	// Array with all available context menu items of this group.
	this.Items = new Array() ;
	
	if ( addSeparator )
		this.Add( new FCKContextMenuSeparator() ) ;
	
	if ( contextMenu && firstItemCommand && firstItemLabel )
		this.Add( new FCKContextMenuItem( contextMenu, firstItemCommand, firstItemLabel, hasIcon ) ) ;

	// This OPTIONAL function checks if the group must be shown.
	this.ValidationFunction = null ;
}

// Adds an item to the group's items collecion.
FCKContextMenuGroup.prototype.Add = function( contextMenuItem )
{
	this.Items[ this.Items.length ] = contextMenuItem ;
}

// Creates the <TR> elements that represent the item in a table (usually the rendered context menu).
FCKContextMenuGroup.prototype.CreateTableRows = function( table )
{
	for ( var i = 0 ; i < this.Items.length ; i++ )
	{
		this.Items[i].CreateTableRow( table ) ;
	}
}

FCKContextMenuGroup.prototype.SetVisible = function( isVisible )
{
	for ( var i = 0 ; i < this.Items.length ; i++ )
	{
		this.Items[i].SetVisible( isVisible ) ;
	}
	
	this.IsVisible = isVisible ;
}

FCKContextMenuGroup.prototype.RefreshState = function()
{
	if ( ! this.IsVisible ) return ;
	
	for ( var i = 0 ; i < this.Items.length ; i++ )
	{
		this.Items[i].RefreshState() ;
	}
}