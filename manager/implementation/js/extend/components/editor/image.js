;(function(app){

	app.editor('image', function(){
		
		var Editor = app.view({

			template: '#editor-image-tpl',
			className: 'form-group', //this class is suggested to be removed if there is no label in this editor options.

			events: {
				//fired on both parentCt and this editor
				'change': '_triggerEvent', 
				'keyup input, textarea': '_triggerEvent', 
				'focusout': '_triggerEvent', 
				'focusin': '_triggerEvent'
			},

			initialize: function(options){
				//[parentCt](to fire events on) as delegate
				this.parentCt = options.parentCt;
				
				//prep basic editor display
				this.model = new Backbone.Model({
					uiId: _.uniqueId('extend-editor-'),
					layout: options.layout || '',
					label: options.label || '', //optional
					name: options.name, //*required
					type: options.type, 
					value: options.value,
					tooltip: options.tooltip,
					help: options.help || ''
				});
				
				//prep validations
				if(options.validate) {
					this.validators = _.map(options.validate, function(validation, name){
						if(_.isFunction(validation)){
							return {fn: validation};
						}else 
							return {rule: name, options:validation};
					});
					//forge the validation method of this editor				
					this.validate = function(show){
						if(!this.isEnabled()) return; //skip the disabled ones.
						
						if(_.isFunction(options.validate)) {
							var error = options.validate(this.getVal(), this.parentCt); 

						}
						else {
							var error, validators = _.clone(this.validators);
							while(validators.length > 0){
								var validator = validators.shift();
								if(validator.fn) {
									error = validator.fn(this.getVal(), this.parentCt);
								}else {
									error = (app.Core.Editor.rules[validator.rule] && app.Core.Editor.rules[validator.rule](validator.options, this.getVal(), this.parentCt));
								}
								if(!_.isEmpty(error)) break;
							}
						}
						if(show) {
							this._followup(error); //eager validation, will be disabled if used in Compound editor 
							//this.status(error);
						}
						return error;//return error msg or nothing						
					};

					//internal helper function to group identical process (error -> eagerly validated)
					this._followup = function(error){
						if(!_.isEmpty(error)){
							this.status(error);
							//become eagerly validated
							this.eagerValidation = true;
						}else {
							this.status();
							this.eagerValidation = false;
						}
					};
					this.listenTo(this, 'editor:change editor:keyup', function(){
						if(this.eagerValidation)
							this.validate(true);
					});

				}

				//prep tooltip upon rendered.
				if(options.tooltip)
					this.enableTooltips(_.isObject(options.tooltip)?options.tooltip:{});

				//1. listen to editor:change so we can reveal [upload] and [clear] buttons
				this.listenTo(this, 'editor:change', function(){
					if(this.ui.input.val()){
						this.ui.upload.removeClass('hide').show();
						this.ui.clearfile.removeClass('hide').show();
					} else {
						this.ui.upload.hide();
						this.ui.clearfile.hide();
					}
					if(this.ui.preview.attr("src")){
				        this.ui.input.hide();
				        this.ui.help.hide();
				        this.ui.preview.removeClass("hide").show(); 
						this.ui.remove.removeClass('hide').show();
					} else {
				        this.ui.input.show();
				        this.ui.help.show();
				        this.ui.preview.addClass("hide"); 
				        this.ui.remove.addClass('hide');
					}
				});
				
				this.enableActionTags('Editor.File');
				
				this.onRender = function(){
					var self = this;
					this.$el.fileupload({
						url: '/api/'+self.model.get('type'),
						fileInput: null, //-remove the plugin's 'change' listener to delay the add event.
						add: function (e, data) {
							data.submit()
								.success(function(result, textStatus, jqXHR){
									self.setVal(result.result, true);
								});
						}
					});
				},
				_.extend(this.actions, {
					//2. implement [clear] button action
					clear: function(){
						this.setVal('', true);
					},
					//3. implement [upload] button action
					upload: function(){
						//add file to fileupload plugin.
						this.$el.fileupload('add', {
							fileInput: this.ui.input
						});
					},
					//4. implement [remove] button action
					remove: function() {
						var self = this;
						app.remote({
						    entity: self.model.get('type'),
						    payload: { _id: _.string.strRightBack(this.getVal(), '/') }
						}).done(function(data){
							self.setVal("", true);         			
			            });
					}
				});
			},

			isEnabled: function(){
				return !this._inactive;
			},
			
			disable: function(flag){
				if(flag === false){
					this._inactive = false;
				}else {
					this._inactive = true;
				}
				
				if(_.isUndefined(flag)){
					//disable but visible, will not participate in validation
					this.ui.input.prop('disabled', true);
					return;
				}

				if(flag){
					//hide and will not participate in validation
					this.$el.hide();
				}else {
					//shown and editable
					this.ui.input.prop('disabled', false);
					this.$el.show();
				}
			},

			setVal: function(val, loud){
				this.ui.input.val("");
				this.ui.preview.attr('src', val);  
				if(loud) {
					this._triggerEvent({type: 'change'});
				}
			},

			getVal: function(){
				if(!this.isEnabled()) return;
				if(this.ui.input)
					return this.ui.preview.attr("src");  
			},

			validate: $.noop,

			status: function(options){
				if(options){
					var type = 'error', msg = options;
					if(!_.isString(options)){
						type = options.type || type;
						msg = options.msg || type;
					}
					//set warning, error, info, success... msg type, no checking atm.
					var className = 'has-' + type;
					this.$el
						.removeClass(this.$el.data('type-class'))
						.addClass(className)
						.data('type-class', className);
					this.ui.msg.html(msg);
				}else {
					//clear
					this.$el
						.removeClass(this.$el.data('type-class'))
						.removeData('type-class');
					this.ui.msg.empty();
				}
			},

			//need to forward events if has this.parentCt
			_triggerEvent: function(e){
				var host = this;
				host.trigger('editor:' + e.type, this.model.get('name'), this);
				//host.trigger('editor:' + e.type + ':' + this.model.get('name'), this);
				
				if(this.parentCt){
					host = this.parentCt;
				}
				host.trigger('editor:' + e.type, this.model.get('name'), this);
				//host.trigger('editor:' + e.type + ':' + this.model.get('name'), this);
			}

		});
		return Editor;

	});

	app.Util.Tpl.build('editor-image-tpl', [
		'{{#if label}}',
			'<label class="control-label {{#if layout}}{{layout.label}}{{/if}}" for="{{uiId}}">{{label}}</label>',
		'{{/if}}',
		'<div class="{{#if layout}}{{layout.field}}{{/if}}" data-toggle="tooltip" title="{{tooltip}}">', //for positioning with the label.
			'<div class="{{type}}">',
			    '<img ui="preview" class="hide img-thumbnail" style="height:100px; width:100px; margin-right:10px" src="">',
				'<input ui="input" name="{{name}}" type="file" id="{{uiId}}" placeholder="{{placeholder}}" value="{{value}}" style="display:inline;">',
				'<span action="upload" class="hide file-upload-action-trigger" ui="upload" style="cursor:pointer;"><i class="glyphicon glyphicon-upload"></i> <!--1 space--></span>',
				'<span action="clear" class="hide file-upload-action-trigger" ui="clearfile"  style="cursor:pointer;"><i class="glyphicon glyphicon-remove-circle"></i></span>',
				'<span action="remove" class="hide file-upload-action-trigger" ui="remove"  style="cursor:pointer;"><i class="glyphicon glyphicon-trash"></i></span>',
				'<span ui="result" class="file-upload-result"></span>',					
			'</div>',	
			//msg & help
			'{{#if help}}<span class="help-block" ui="help" style="margin-bottom:0"><small>{{help}}</small></span>{{/if}}',
			'<span class="help-block input-error" ui="msg">{{msg}}</span>',
		'</div>'
	]);

})(Application);