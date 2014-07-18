(function(app) {
    app.regional('UserDataGrid', {
        tagName: 'table',
        type: 'CompositeView',
        className: 'table table-striped table-hover',
        template: [                   
			'<thead>',
			 	'<tr>',
			 		'<th>#</th>',
			 		'<th>Name</th>',
			 		'<th>Gender</th>',
			 		'<th>Email</th>',
			 		'<th>Operation</th>',
			 	'</tr>',
			'</thead>',
			'<tbody>',			  	
			'</tbody>'             
        ],
        itemViewContainer: "tbody",
        itemView: Application.view({
    		type: 'ItemView',     		
    		initialize: function(options){
    			this.id = options.id;
    		},
    		tagName: 'tr',
            template: [ 
		  		'<td>{{id}}</td>',
		  		'<td>{{name}}</td>',
		  		'<td>{{gender}}</td>',
		  		'<td>{{email}}</td>',
		  		'<td><button id={{id}} type="button" class="btn btn-primary btn-xs" style="margin-right:5px;" action="show">Detail</button><button id={{id}} type="button" class="btn btn-primary btn-xs" action="delete">Delete</button></td>',
            ]         
        }),
        actions: { 
        	_bubble: true,
            'show': function($triggerTag, e){
            	var $id = $triggerTag.attr("id");
            	this.parentCt.trigger("view:show-form", {id: $id});
            },
            'delete': function($triggerTag, e){
            	var self = this;
            	var $id = $triggerTag.attr("id");
            	Application.remote({
            	    entity: 'user',
            	    payload: { _id: $id }
            	}).done(function(data){
                    self.collection.remove(self.collection.get($id));
            	});
            }            
        },        
        onShow: function() {
            var that = this;
            app.remote({
            	url:"/user"
            }).done(function(data){
                that.trigger('view:render-data', data);
            });
        }     
    });
})(Application);

