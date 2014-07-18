(function(app) {
	app.widget('UserForm', function(){
        return app.view({
        	effect: 'fade',
            className: 'panel panel-default',
            template: '#form-user-tpl',
            initialize: function(options){ 
            	this.id = options.id;
            	this.listenTo(this, 'editor:focusout', this.immediatelyVal);
            },  
            immediatelyVal: function(name) {
            	this.getEditor(name).validate(true);
            },
            editors: {
                _global: {
                    layout: {
                        label: 'col-sm-2',
                        field: 'col-sm-6'
                    }
                },
                id: {
                    type: 'text',
                    label: 'Id'
                },            	
                name: {
                    type: 'text',
                    label: 'Name',
                    validate: { 
                        required: {
                            msg: 'Name cann\'t be null!',
                        } 
                    }	
                },     
                avatar: {
                    label: 'Avatar',
                    type: 'image',
                    help: 'Please choose your image to upload.'
                },               
                gender: {
                    type: 'radios',
                    label: 'Gender',             
                    options: {
                    	data: ['male', 'female'],
                    	inline: true
                    }
                },
                birthday: {
                    type: 'datepicker',
                    label: 'birthday',
                    validate: {
                    	fn: function(val, parentCt){
                    		var reg = /^(\d{1,4}\/)(\d{2}\/)(\d{2})$/;
                    		if(!reg.test(val)) {                    			
                    			return "Your birthday is incorrectly formatted. Please choose a valid birthday.";
                    		}
                    	}
                    }
                },                       
                email: {
                    type: 'email',
                    label: 'Email',
                    validate: {
                    	fn: function(val, parentCt){
                    		var reg = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
                    		if(!reg.test(val)) {
                    			return "Your Email Address is incorrectly formatted. Please enter a valid address.";
                    		}
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
                		self.getEditor("id").disable(false);
                		var _user = this.getValues();
                		if(_user.id===""){
                			user = _.omit(_user, 'id'); 
                		} else { // For stage.js make 'put' request.
                			_user._id = _user.id;
                		};
                		app.remote({
                		    entity: 'user',
                		    payload: _user
                		}).done(function(){
                			self.close();
                			self.parentCt.trigger("view:update-form", _user);
                		});
                	}
                }
            },            
            onShow: function() {   
                if(this.id){
                    var self = this;                	
                    app.remote({
                        entity: 'user',
                        _id: this.id
                    }).done(function(data){
                    	self.getEditor("id").disable(true);
                    	self.setValues(data, true);            
                    });
                } else {
                	this.getEditor("id").disable(true);
                	this.getEditor("gender").setVal('male','checked');
                }
            }
        }); 
    });
	app.Util.Tpl.build('form-user-tpl', [
        '<div class="panel-body">',
			'<form class="form-horizontal">',
				'<fieldset>',
					'<legend>Person Info</legend>',
					'<div editor="id"></div>',
					'<div editor="name"></div>',
					'<div editor="avatar"></div>',
					'<div editor="gender"></div>',					
					'<div editor="birthday"></div>',
					'<div editor="email"></div>',						
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

