(function(app) {
	
    app.context('Login', {
        template: '@login/login.html',
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
})(Application);