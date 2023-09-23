var FCKToolbarBreak = function()
{
	var oBreakDiv = document.createElement( 'div' ) ;
	
	oBreakDiv.className = 'TB_Break' ;
	
	oBreakDiv.style.clear = FCKLang.Dir == 'rtl' ? 'left' : 'right' ;
	
	FCKToolbarSet.DOMElement.appendChild( oBreakDiv ) ;
}