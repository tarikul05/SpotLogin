/*
 CKEditor Plugin 
 name : Cy-GistInsert.
 2016-08-27. 
 by igotit. 
*/
CKEDITOR.plugins.add( 'Cy-GistInsert', {
    icons: 'Cy-GistInsert',
    init: function( editor ) {
        editor.addCommand( 'cmd-insertgist1', {  // adding the command 
            exec: function( editor ) {
                editor.insertHtml( 'Test from plugin Cy-GistInsert cmd-insertgist1 ' ); // at now just testing purpose.
            }
        });
        editor.ui.addButton( 'Cy-GistInsert', {
            label: 'Insert GitHub Gist',      // button's tooltip text.
            command: 'cmd-insertgist1',       // the command to be executed when the button is clicked.
            toolbar: 'insert'                 // toolbar groub into which the button will be added
        });
    }
});