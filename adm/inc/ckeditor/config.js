/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.width = 800;

	//config.extraPlugins = 'widget';

	//config.extraPlugins = 'youtube';
	//config.toolbar = [{ name: 'insert', items: ['Image', 'Youtube']}];
	//config.extraPlugins = 'html5audio';
	config.extraPlugins = 'soundPlayer,youtube,html5audio,video';
};
