var FCKXml ;

if ( !( FCKXml = NS.FCKXml ) )
{
	FCKXml = NS.FCKXml = function()
	{
		this.Error = false ;
	}

	FCKXml.prototype.LoadUrl = function( urlToCall )
	{
		this.Error = false ;

		var oXmlHttp = FCKTools.CreateXmlObject( 'XmlHttp' ) ;

		if ( !oXmlHttp )
		{
			this.Error = true ;
			return ;
		}

		oXmlHttp.open( "GET", urlToCall, false ) ;
		
		oXmlHttp.send( null ) ;
		
		if ( oXmlHttp.status == 200 || oXmlHttp.status == 304 )
			this.DOMDocument = oXmlHttp.responseXML ;
		else if ( oXmlHttp.status == 0 && oXmlHttp.readyState == 4 )
		{
			this.DOMDocument = FCKTools.CreateXmlObject( 'DOMDocument' ) ;
			this.DOMDocument.async = false ;
			this.DOMDocument.resolveExternals = false ;
			this.DOMDocument.loadXML( oXmlHttp.responseText ) ;
		}
		else
		{
			this.Error = true ;
			alert( 'Error loading "' + urlToCall + '"' ) ;
		}
	}

	FCKXml.prototype.SelectNodes = function( xpath, contextNode )
	{
		if ( this.Error )
			return new Array() ;

		if ( contextNode )
			return contextNode.selectNodes( xpath ) ;
		else
			return this.DOMDocument.selectNodes( xpath ) ;
	}

	FCKXml.prototype.SelectSingleNode = function( xpath, contextNode ) 
	{
		if ( this.Error )
			return ;
			
		if ( contextNode )
			return contextNode.selectSingleNode( xpath ) ;
		else
			return this.DOMDocument.selectSingleNode( xpath ) ;
	}
}