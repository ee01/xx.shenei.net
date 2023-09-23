var FCKToolbarBreak = function()
{
	var oBreakDiv = document.createElement( 'div' ) ;
	
	oBreakDiv.style.clear = oBreakDiv.style.cssFloat = FCKLang.Dir == 'rtl' ? 'right' : 'left' ;
	
	FCKToolbarSet.DOMElement.appendChild( oBreakDiv ) ;
}