Application.setup({
    template: [
      	'<div region="banner" view="Banner"></div>',
       	'<div region="body"></div>',
       	'<div region="footer" view="Footer"></div>'
    ],
    contextRegion: 'body', //use region 'body' as the context region
    defaultContext: 'Index',
    loginContext: 'Login'
}).run();

Application.addInitializer(function(options) {
	Application.trigger('app:check-user');
});

Application.onCheckUser = function() {
	Application.remote({
		url: '/touch',
		async: false,
		type: 'json'
	}).done(function(data, textStatus, jqXHR) {
		if (!data.isLogin) {
			Application.trigger('app:navigate', Application.config.loginContext);
		} else {
			if (!window.location.hash ||
				(Application.currentContext &&
				Application.currentContext.name === Application.config.loginContext)) {
				Application.currentUser = data.user;
				Application.trigger('app:navigate', Application.config.defaultContext);
			}
		}
	}).fail(function(jqXHR, textStatus, errorThrown) {
		console.debug(this.url, errorThrown);
	});
};