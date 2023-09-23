/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: fckcommands.js
 * 	Define all commands available in the editor.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

var FCKCommands = FCK.Commands = new Object() ;
FCKCommands.LoadedCommands = new Object() ;

FCKCommands.RegisterCommand = function( commandName, command )
{
	this.LoadedCommands[ commandName ] = command ;
}

FCKCommands.GetCommand = function( commandName )
{
	var oCommand = FCKCommands.LoadedCommands[ commandName ] ;
	
	if ( oCommand )
		return oCommand ;

	switch ( commandName )
	{
		case 'Link'			: oCommand = new FCKDialogCommand( 'Link'		, FCKLang.DlgLnkWindowTitle		, 'dialog/fck_link.html'		, 400, 330, FCK.GetNamedCommandState, 'CreateLink' ) ; break ;
		case 'Anchor'		: oCommand = new FCKDialogCommand( 'Anchor'		, FCKLang.DlgAnchorTitle		, 'dialog/fck_anchor.html'		, 370, 170 ) ; break ;
		case 'BulletedList'	: oCommand = new FCKDialogCommand( 'BulletedList', FCKLang.BulletedListProp		, 'dialog/fck_listprop.html'	, 370, 170 ) ; break ;
		case 'NumberedList'	: oCommand = new FCKDialogCommand( 'NumberedList', FCKLang.NumberedListProp		, 'dialog/fck_listprop.html'	, 370, 170 ) ; break ;

		case 'Image'		: oCommand = new FCKDialogCommand( 'Image'		, FCKLang.DlgImgTitle			, 'dialog/fck_image.html'		, 450, 400 ) ; break ;
		case 'Flash'		: oCommand = new FCKDialogCommand( 'Flash'		, FCKLang.DlgFlashTitle			, 'dialog/fck_flash.html'		, 450, 400 ) ; break ;
		case 'SpecialChar'	: oCommand = new FCKDialogCommand( 'SpecialChar', FCKLang.DlgSpecialCharTitle	, 'dialog/fck_specialchar.html'	, 400, 320 ) ; break ;
		case 'Table'		: oCommand = new FCKDialogCommand( 'Table'		, FCKLang.DlgTableTitle			, 'dialog/fck_table.html'		, 400, 250 ) ; break ;
		case 'TableProp'	: oCommand = new FCKDialogCommand( 'Table'		, FCKLang.DlgTableTitle			, 'dialog/fck_table.html?Parent', 400, 250 ) ; break ;
		case 'TableCellProp': oCommand = new FCKDialogCommand( 'TableCell'	, FCKLang.DlgCellTitle			, 'dialog/fck_tablecell.html'	, 500, 250 ) ; break ;

		case 'Style'		: oCommand = new FCKStyleCommand() ; break ;

		case 'FontName'		: oCommand = new FCKFontNameCommand() ; break ;
		case 'FontSize'		: oCommand = new FCKFontSizeCommand() ; break ;
		case 'FontFormat'	: oCommand = new FCKFormatBlockCommand() ; break ;

		case 'Source'		: oCommand = new FCKSourceCommand() ; break ;
		case 'Preview'		: oCommand = new FCKPreviewCommand() ; break ;

		case 'TextColor'	: oCommand = new FCKTextColorCommand('ForeColor') ; break ;
		case 'BGColor'		: oCommand = new FCKTextColorCommand('BackColor') ; break ;

		case 'PasteText'	: oCommand = new FCKPastePlainTextCommand() ; break ;
		case 'PasteWord'	: oCommand = new FCKPasteWordCommand() ; break ;

		case 'TableInsertRow'		: oCommand = new FCKTableCommand('TableInsertRow') ; break ;
		case 'TableDeleteRows'		: oCommand = new FCKTableCommand('TableDeleteRows') ; break ;
		case 'TableInsertColumn'	: oCommand = new FCKTableCommand('TableInsertColumn') ; break ;
		case 'TableDeleteColumns'	: oCommand = new FCKTableCommand('TableDeleteColumns') ; break ;
		case 'TableInsertCell'		: oCommand = new FCKTableCommand('TableInsertCell') ; break ;
		case 'TableDeleteCells'		: oCommand = new FCKTableCommand('TableDeleteCells') ; break ;
		case 'TableMergeCells'		: oCommand = new FCKTableCommand('TableMergeCells') ; break ;
		case 'TableSplitCell'		: oCommand = new FCKTableCommand('TableSplitCell') ; break ;
		case 'TableDelete'			: oCommand = new FCKTableCommand('TableDelete') ; break ;

		case 'Undo'	: oCommand = new FCKUndoCommand() ; break ;
		case 'Redo'	: oCommand = new FCKRedoCommand() ; break ;

		// Generic Undefined command (usually used when a command is under development).
		case 'Undefined'	: oCommand = new FCKUndefinedCommand() ; break ;
		
		// By default we assume that it is a named command.
		default:
			if ( FCKRegexLib.NamedCommands.test( commandName ) )
				oCommand = new FCKNamedCommand( commandName ) ;
			else
			{
				alert( FCKLang.UnknownCommand.replace( /%1/g, commandName ) ) ;
				return null ;
			}
	}
	
	FCKCommands.LoadedCommands[ commandName ] = oCommand ;
	
	return oCommand ;
}

// Gets the state of the "Document Properties" button. It must be enabled only
// when "Full Page" editing is available.
FCKCommands.GetFullPageState = function()
{
	return FCKConfig.FullPage ? FCK_TRISTATE_OFF : FCK_TRISTATE_DISABLED ;
}
