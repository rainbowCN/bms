(function(app) {
	
    app.regional('Banner', {
    	
		template: '#app-banner-tpl',
		className: 'banner',
		
		initialize: function(options) {
			this.listenTo(app, 'app:context-switched', this.onContextSwitched);
		},
		
		actions: {
			logout: function($triggerTag, event) {
				event.preventDefault();
				app.remote({
					url: '/logout',
					async: false,
					dataType: 'json'
				}).done(function(data, textStatus, jqXHR) {
					app.currentUser = null;
					app.trigger('app:navigate', app.config.loginContext);
				}).fail(function(jqXHR, textStatus, errorThrown) {
					console.debug(this.url, errorThrown);
				});
			}
		},
		
		onContextSwitched: function(contextName) {
			console.log(contextName);
			if (contextName === app.config.loginContext) {
				this.ui.user.addClass('hidden').children('a').html('');
				this.ui.logout.addClass('hidden');
				this.ui.signUp.removeClass('hidden');
			} else {
				var strArray = [];
				strArray.push('Welcome: <em>');
				strArray.push(app.currentUser.name);
				strArray.push('</em>');
				this.ui.user.removeClass('hidden').children('a').html(strArray.join(''));
				this.ui.logout.removeClass('hidden');
				this.ui.signUp.addClass('hidden');
			}
		}    
    });
    
   
	app.Util.Tpl.build('app-banner-tpl', [
	    '<div class="navbar navbar-default">',
		    '<div class="navbar-header">',
		        '<a class="navbar-brand" href="#navigate/Index">Brand</a>',
		    '</div>',
		    '<div class="navbar-collapse collapse navbar-responsive-collapse">',
		         '<ul class="nav navbar-nav">',
		             '<li class="active"><a href="#navigate/Index">Index</a></li>',
		             '<li><a href="#navigate/About">About</a></li>',
		         '</ul>',
		         '<ul class="nav navbar-nav navbar-right">',
		  	         '<li ui="user" class="hidden"><a></a></li>',
		  	         '<li ui="logout" class="hidden"><a href="./logout" action="logout">Logout</a></li>',
		  	         '<li ui="signUp"><a>Sign up</a></li>',
		         '</ul>',
		     '</div>',
	     '</div>'
	]);
    
})(Application);

