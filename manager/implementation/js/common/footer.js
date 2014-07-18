(function(app) {
	
    app.regional('Footer', {
        template: [
            '<div class="container">',
                '<hr>',
                '<footer>',
                     '<p action="opTest">&copy; Company 2014</p>',
                '</footer>',
             '</div>'
        ],
        initialize: function(){
        	this.enableActionTags();
        },
        actions: {
            'opTest': function($triggerTag, e){

            }
        }        
    });
    
})(Application);