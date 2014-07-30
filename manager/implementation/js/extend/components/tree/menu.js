(function(app) {
	app.widget('Menu', function(){
        return app.view({
        	type: 'CollectionView',
        	className: 'menu',
        	itemView: app.view({
        		type: 'CompositeView',
                template: '#node-template',
                tagName: 'ul',
                isBranch: false,
                isShow: false,
                initialize: function(){
                	if(this.model.nodes){
                		this.$el.addClass("branch");
                		this.isBranch = true;
                		this.collection = this.model.nodes;
                	} else {
                		this.$el.addClass("leaf");
                	}
                },     
                onShow: function() {
                	$(".menu ul:first-child li").addClass("active");
                    if(this.isBranch){
                    	this.$el.children("li").find("span").removeClass("glyphicon-minus").addClass("glyphicon-plus");
                    	this.$el.children("ul").hide();
                    }
                },
                events: {
                    'click li:first': 'toggle'
                },    
                toggle: function(event) {
                    event.stopPropagation();
                    if (this.isBranch) {
                    	//active selected item.
                    	$("li").removeClass("active");
                    	this.$el.children("li").toggleClass("active");
                    	//hide children item.
                    	this.$el.children("ul").toggle();
                    	//exchange icon status in "+-".
                    	var _dom = this.$el.children("li").find("span");
                    	if(_dom.hasClass("glyphicon-plus")) {
                    		_dom.removeClass("glyphicon-plus").addClass("glyphicon-minus");
                    	} else {
                    		_dom.removeClass("glyphicon-minus").addClass("glyphicon-plus");
                    	}                    	
                    }
                }
        	}),
        	onShow: function(){
        		var that = this;
                app.remote("menu").done(function(data){
                    that.trigger('view:render-data', data);
                });
        	}
        }); 
    });
	app.Util.Tpl.build('node-template', [
	    "<li><a href='{{action}}'><span class='glyphicon glyphicon-minus'></span>{{nodeName}}</a></li>"  
	]);

})(Application);


