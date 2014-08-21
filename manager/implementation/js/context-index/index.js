(function(app) {
	
    app.context('Index', {
        template: [
            '<div class="container">',                   
	       		'<div class="row">',
	   				'<div class="col-sm-3" region="sidebar"></div>', 
	       			'<div class="col-sm-9" region="content"></div>',
	       		'</div>',
       		'</div>'
        ],
        navRegion: 'content',
        initialize: function() {
        	console.log("initialize");
        },
        onNavigateTo: function(subpath) {
        	console.log("subpath", subpath);
        },
        onSwitchContent: function(options){
    	},
    	onShow: function(){
            TreeNode = Backbone.Model.extend({
                initialize: function(){
                    var nodes = this.get("nodes");
                    if (nodes){
                        this.nodes = new TreeNodeCollection(nodes);
                        this.unset("nodes");
                    }
                }        
            });
            TreeNodeCollection = Backbone.Collection.extend({
                model: TreeNode
            });
            this.sidebar.trigger('region:load-view', "Menu", {collection: new TreeNodeCollection(), dataURL: 'menu'});   
            this.content.trigger('region:load-view', "User");
    	}
    });
})(Application);