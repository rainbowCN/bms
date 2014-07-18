/**
 * The Default String Column Cell Definition.
 *
 * @author James.Yu
 * @created 2014.06.24
 */


;(function(app){

	app.widget('StringCell', function(){

		var UI = app.view({
			template: '<span>{{{truncate value 18 " ..."}}}</span>',
		});

		return UI;
	});

})(Application);