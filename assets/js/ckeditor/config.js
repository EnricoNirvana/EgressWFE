/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
config.toolbar = 'DocToolbar';
 
	config.toolbar_DocToolbar =
	[
		{ name: 'document', items : [ 'Source','-','Save','NewPage','Preview','Print' ]  },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar','PageBreak','Iframe' ] },
                '/',
		{ name: 'styles', items : [ 'Styles','Format' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] }
	];
	
	// config.resize_enabled = false;
	
	// Sizes are in pixels
	config.resize_minWidth = 925;
	config.resize_maxWidth = 925;
	config.resize_minHeight = 275;
	config.resize_maxHeight = 275;
	
	config.uiColor = '#90B0CF';
	
};
