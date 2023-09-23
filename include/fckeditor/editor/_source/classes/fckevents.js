var FCKEvents ;

if ( !( FCKEvents = NS.FCKEvents ) )
{
	FCKEvents = NS.FCKEvents = function( eventsOwner )
	{
		this.Owner = eventsOwner ;
		this.RegisteredEvents = new Object() ;
	}

	FCKEvents.prototype.AttachEvent = function( eventName, functionPointer )
	{
		if ( ! this.RegisteredEvents[ eventName ] ) this.RegisteredEvents[ eventName ] = new Array() ;

		this.RegisteredEvents[ eventName ][ this.RegisteredEvents[ eventName ].length ] = functionPointer ;
	}

	FCKEvents.prototype.FireEvent = function( eventName, params )
	{
		var bReturnValue = true ;

		var oCalls = this.RegisteredEvents[ eventName ] ;
		if ( oCalls )
		{
			for ( var i = 0 ; i < oCalls.length ; i++ )
				bReturnValue = ( oCalls[ i ]( this.Owner, params ) && bReturnValue ) ;
		}

		return bReturnValue ;
	}
}