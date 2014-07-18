(function(app) {
	app.widget('BlogForm', function(){ //{Blog}
        return app.view({
        	effect: 'fade',
            className: 'panel panel-default',
            template: '#form-blog-tpl', //{blog}
            initialize: function(options){ 
            	this.id = options.id;
            	this.listenTo(this, 'editor:focusout', this.immediatelyVal);
            },  
            immediatelyVal: function(name) {
            	this.getEditor(name).validate(true);
            },
            editors: { //{editors}
                id: {
                    type: 'text',
                    label: 'Id',
                    layout: {
                    	label: 'col-sm-2',
                    	field: 'col-sm-6'
                    }
                },            	
                title: {
                    type: 'text',
                    label: 'Title',
                    layout: {
                    	label: 'col-sm-2',
                    	field: 'col-sm-6'
                    },
                    validate: { 
                        required: {
                            msg: 'Name cann\'t be null!',
                        } 
                    }	
                },               
                content: {
                    type: 'textarea',
                    label: 'Content',
                    layout: {
                    	label: 'col-sm-2',
                    	field: 'col-sm-6'
                    },
                    validate: { 
                        required: {
                            msg: 'Name cann\'t be null!',
                        } 
                    }	
                }             
            },
            actions: {
                _bubble: false,
                'cancel': function($triggerTag, e){
                	this.close();
                },
                'submit': function($triggerTag, e){
                	var self = this;
                	if(!this.validate(true)){
                		var _data = this.getValues();
                		if(_data.id==="-1"){ _.omit(_data, 'id'); } else { _data._id = _data.id; }
                		app.remote({
                		    entity: 'blog', //{blog}
                		    payload: _data
                		}).done(function(){
                			self.close();
                			self.parentCt.trigger("view:update-form", _data);
                		});
                	}
                }
            },            
            onShow: function() {   
                if(this.id){
                    var that = this;                	
                    app.remote({
                        entity: 'blog', //{blog} 
                        _id: this.id
                    }).done(function(data){
                    	_.map(data, function(val, key){ 
                    		//{title} {content}
                    		if(key==='id'||key==='title'||key==='content') that.getEditor(key).setVal(val, val);
                    	});               
                    });
                } else {
                	this.getEditor("id").setVal('-1');
                	this.getEditor("id").disable(true);
                }
            }
        }); 
    });
	app.Util.Tpl.build('form-blog-tpl', [ //{blog}
        '<div class="panel-body">',
			'<form class="form-horizontal">',
				'<fieldset>',
					'<legend>Blog Info</legend>', //{BLog}
					'<div editor="id"></div>',
					'<div editor="title"></div>', //{title} 
					'<div editor="content"></div>', //{content} 			   						
					'<div class="form-group">',
				      	'<div class="col-md-10 col-md-offset-4">',
				      		'<button class="btn btn-primary" type="button" action="submit" style="margin-right:10px">Submit</button>',
				      		'<button class="btn btn-default" type="button" action="cancel">Cancel</button>',
				      	'</div>',
				    '</div>',						
				'</fieldset>',
			'</form>',			
	  	'</div>'     
	]);

})(Application);

