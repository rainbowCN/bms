(function(app) {
	
    app.context('Login', {
        template: '#form-login-modal-tpl',
        initialize: function(options){ 
        	this._validate = true;
        	this.listenTo(this, 'editor:focusout', this.validate);
        	this.listenTo(this, 'editor:callback', this.callback);
        },  
        validate: function(name) {
        	this.getEditor(name).validate(true);
        },
        callback: function(options){
        	this.getEditor(options.name).status(options.msg); 
        },
        editors: {        	
            name: {
                type: 'text',
                label: 'Username',
                layout: {
                	label: 'col-sm-3',
                	field: 'col-sm-5'
                },
                validate: {
                    required: {
                        msg: 'Username can\'t be null!'
                    },
                    fn: function(val, parentCt){
                    	app.remote({
                    	    entity: 'user',
                    	    payload: {
                    	    	name: val
                    	    },
                    	    _method: 'unique'
                    	}).done(function(data){
                    		if(!data.unique){
                    			parentCt.trigger('editor:callback', {"name":"name", "msg":"Username can\'t be found!"});
                    			parentCt._validate = false;
                    		} else {
                    			parentCt._validate = true;
                    		}
                    	});
                    }
                }
            },               
            password: {
                type: 'password',
                label: 'Password',
                layout: {
                	label: 'col-sm-3',
                	field: 'col-sm-5'
                }
            },    
        },
        
        actions: {
            _bubble: false,
            'cancel': function($triggerTag, e){
            	//this.close();
            },
            'submit': function($triggerTag, e){
            	if(this._validate) {
                	app.remote({
                		entity: 'user',
                		_method: 'login',
            		    payload: this.getValues() 
            		}).done(function(data, textStatus, jqXHR){
            			if(data.isLogin){
            				app.trigger('app:check-user');
            			} else {
            				app.trigger('app:error', 'Username or password is incorrect!');
            			}
            		}).fail(function(jqXHR, textStatus, errorThrown) {
    					console.debug(this.url, errorThrown);
    				});
            	}
            }
        }     
    });

	app.Util.Tpl.build('form-login-modal-tpl', [
	      '<div class="modal">',
	    		'<div class="modal-dialog">',
	    	    	'<div class="modal-content">',
	    	    		'<div class="modal-body">',
			       			'<form class="form-horizontal">',
			       				'<fieldset>',
			       					'<legend>Sign in</legend>',
			      					'<div editor="name"></div>',
			      					'<div editor="password"></div>',   					
			       					'<div class="form-group">',
			       				      	'<div class="col-md-10 col-md-offset-3">',
			       				      		'<button class="btn btn-primary" type="button" action="submit" style="margin:0px 20px">Submit</button>',
			       				      		'<button class="btn btn-default" type="button" action="cancel">Cancel</button>',
			       				      	'</div>',
			       				    '</div>',						
			       				'</fieldset>',
			       			'</form>',			
	    	    		'</div>',
	    	    	'</div>',
	    	    '</div>',
	    	'</div>'	                                        
	]);  	
})(Application);