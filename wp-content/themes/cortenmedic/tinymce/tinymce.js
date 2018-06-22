(function() {

  tinymce.PluginManager.add( 'section_header', function( editor, url ) {
    // Add Button to Visual Editor Toolbar
    editor.addButton('section_header', {
      title: 'Nagłówek sekcji',
      cmd: 'section_header',
      image: url + '/sh.jpg',
    });

    // Add Command when Button Clicked
    editor.addCommand('section_header', function() {
      var text = editor.selection.getContent({
          'format': 'html'
      });
        
      if( text.length === 0 ) {
        alert( 'Nie zaznaczono tekstu' );
        return;
      }

      var return_text = '<h3 class="page-section-header">' + text + '</h3>';
      editor.execCommand("mceInsertContent", 0, return_text);
    });
  });

})();