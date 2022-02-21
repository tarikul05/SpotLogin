CKEDITOR.plugins.add( 'AppFields',
{   
   requires : ['richcombo'],// 'styles' ],
   
   //jQuery.extend({
	//});
   
   init : function( editor )
   {
      var config = editor.config,
         lang = editor.lang.format;

      // Gets the list of tags from the settings.
      var tags = []; //new Array();
      //this.add('value', 'drop_text', 'drop_label');
      /*
	  tags[0]=["[{project_name}]", "Project Name", "Project Name"];
      tags[1]=["[{project_address}]", "Project Address", "Project Address"];
      tags[2]=["[{project_district}]", "Project=District", "Project District"];
      */
	  var getValues = function(url) {
		var result = [
			['aaa', 'aaa', 'aaa'],
			['bbb', 'bbb', 'bbb'],
		];
		// $.ajax({
		// 	url: url,
		// 	type: 'POST',
		// 	dataType: 'json',
		// 	async: false,
		// 	success: function(data) {
		// 		//result = data;
		// 		$.each(data, function(key, row){
		// 			result.push([row.value, row.drop_text, row.drop_label]);
		// 		});
		// 	}
		// });
	   return result;
	};
	
	  //var school_code=getCookie('school_code');
	  var get_school_code = function(p_cookie_name) {
		var name = p_cookie_name + "=";
		var ca = document.cookie.split(';');
		for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	};
      //alert(get_school_code('school_code'));
	  var ajx_url = window.location.protocol + '//' + window.location.hostname + '/'+get_school_code('school_code')+'/template_variables.php'+'?time='+new Date().getTime();
	  //var ajx_url = window.location.protocol + '//' + window.location.hostname + '/'+get_school_code('school_code')+'/template_variables.php';
	  //alert(ajx_url);
	  var tags = getValues(ajx_url);
	  // Create style objects for all defined styles.

      editor.ui.addRichCombo( 'AppFields',
         {
            label : "Variables",
            title :"Variables",
            voiceLabel : "Variables",
            className : 'cke_panel_listItem',
            multiSelect : false,

            panel :
            {
				//commented below css to avoid wrap list items
               //css : [ config.contentsCss, CKEDITOR.getUrl( editor.skinPath + 'editor.css' ) ],
               voiceLabel : lang.panelVoiceLabel
            },

            init : function()
            {
               this.startGroup( "AppFields" );
			   //setTimeout(function(){
				   //this.add('value', 'drop_text', 'drop_label');
				   for (var this_tag in tags){
					  this.add(tags[this_tag][0], tags[this_tag][1], tags[this_tag][2]);
				   }
			   //}, 1000);
            },

            onClick : function( value )
            {         
               editor.focus();
               editor.fire( 'saveSnapshot' );
               editor.insertHtml(value);
               editor.fire( 'saveSnapshot' );
            }
         });
   }
});