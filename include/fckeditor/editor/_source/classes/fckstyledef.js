var FCKStyleDef = function( name, element )
{
	this.Name = name ;
	this.Element = element.toUpperCase() ;
	this.IsObjectElement = FCKRegexLib.ObjectElements.test( this.Element ) ;
	this.Attributes = new Object() ;
}

FCKStyleDef.prototype.AddAttribute = function( name, value )
{
	this.Attributes[ name ] = value ;
}

FCKStyleDef.prototype.GetOpenerTag = function()
{
	var s = '<' + this.Element ;
	
	for ( var a in this.Attributes )
		s += ' ' + a + '="' + this.Attributes[a] + '"' ;
	
	return s + '>' ;
}

FCKStyleDef.prototype.GetCloserTag = function()
{
	return '</' + this.Element + '>' ;
}


FCKStyleDef.prototype.RemoveFromSelection = function()
{
	if ( FCKSelection.GetType() == 'Control' )
		this._RemoveMe( FCKSelection.GetSelectedElement() ) ;
	else
		this._RemoveMe( FCKSelection.GetParentElement() ) ;
}