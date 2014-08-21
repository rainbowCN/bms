(function(app) {
    app.regional('User', {
        className: 'panel panel-default',
        template: [
			'<div class="panel-body">',
   				'<div><button type="button" class="btn btn-primary" style="float:right" action="add">Add</button></div>',
   				'<div style="clear:both"></div>',
   				'<div region="datagrid"></div>',
   				'<div region="pagination"></div>',
   				'<div style="clear:both"></div>',
       			'<div region="form" view="App::Blank"></div>',		
		  	'</div>'                   
        ],
        actions: { 
        	_bubble: true,
            'add': function($triggerTag, e){
            	this.form.trigger('region:load-view', 'UserForm', {});
            }          
        },     
        onShow: function() {
        	var self  = this;
        	this.datagrid.trigger('region:load-view', 'Datagrid', {
        	    className: 'table table-hover',
        	    columns: [
              	        {
              	            name: 'id',
              	            label: '#ID'
              	        },
              	        {
              	            name: 'name',
              	            icon: 'fa fa-github-alt'
              	        },
              	        {
              	            name: 'email',
              	            label: 'Email',
                  	        icon: 'fa fa-envelope'
              	        },
              	        {
              	            cell: 'operate',
              	            label: 'operation',
              	            icon: 'fa fa-cog',
              	            actions: {
              	                edit: {
              	                    fn: function(){
              	                    	self.editForm(this.model.attributes);
              	                    }
              	                },
              	        		remove: {
              	                    fn: function(){
              	                    	self.deleteForm(this.model.attributes);
              	                    }
              	        		}
              	            }
              	        }
              	    ]
        	});    
        	var datagrid = this.datagrid.currentView;     
        	this.pagination.trigger('region:load-view', 'Paginator', {
        	    target: datagrid,
        	    className: 'pagination pagination-sm pull-right'
        	});     
        	datagrid.trigger('view:load-page', {
        	    url: 'user',
        	    page: 1,
        	    pageSize: 5
        	});        	
        },
        
        editForm: function(options){
        	this.form.trigger('region:load-view', 'UserForm', options);
        },
        
        deleteForm: function(options){
        	var self = this;
        	Application.remote({
        	    entity: 'user',
        	    payload: { _id: options.id }
        	}).done(function(){
        		self.form.currentView.close();
        		self.datagrid.currentView.trigger('view:load-page', {});
        	});
        },
        
        // Triggered by the event of submit from UserForm
        onUpdateForm: function(){
        	this.form.currentView.close();
			this.datagrid.currentView.trigger('view:load-page', {});
        }             
    });
})(Application);

