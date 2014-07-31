(function(app){
	
	/**
	 * ========================
	 * Application Extend Message & Notifycations
	 * ========================
	 */
	console = window.console || {log:function(){},error:function(){}};

	if(noty){
		if(window.error) console.log('!!WARNING::error notification function conflict!!');
		/**
		 * Notify the user about application error.
		 *
		 * @arguments Error Type
		 * @arguments Messages ,...,
		 */
		app.onError = function(msg){
			console.log(msg);
			noty({
				layout: 'center',
				text: '<span class="label label-important">'+msg+'</span>',
				type: 'error',
				timeout: 2000,
				dismissQueue: true
			});
			
		};
	}	
	Handlebars.registerHelper('truncate2', function(string, num) {
		return _.string.truncate(string, 5);
	});
})(Application);