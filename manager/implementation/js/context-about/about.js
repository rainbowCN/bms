(function(app) {
    app.context('About', {
        template: [
            '<div class="container">',                   
   	       		'<div class="row">',                   
					'<div class="jumbotron">',
						'<h1>About</h1>',
						'<p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>',
						'<p><a class="btn btn-primary btn-lg">Learn more</a></p>',
					'</div>',
					'<div region="content"></div>',
	       		'</div>',
	       	'</div>'					
        ],
        onNavigateTo: function(subpath) {
        	console.log('call "context:navigate-to" & exec "onNavigateTo" ', subpath);
        	
        }
    });
})(Application);