;(function(app){

	app.editor('datepicker', function(){
		
		var Editor = app.view({

			template: '#editor-datepicker-tpl',
			className: 'form-group', //this class is suggested to be removed if there is no label in this editor options.

			events: {
				//fired on both parentCt and this editor
				'change': '_triggerEvent', 
				'keyup input, textarea': '_triggerEvent', 
				'focusout': '_triggerEvent', 
				'focusin': '_triggerEvent'
			},

			initialize: function(options){
				
				this.parentCt = options.parentCt;
				
				//prep basic editor display
				this.model = new Backbone.Model({
					uiId: _.uniqueId('extend-editor-'),
					layout: options.layout || '', //optional
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
						if(_.isFunction(options.validate)) {
							var error = options.validate(this.getVal(), this.parentCt); 
						} else {
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

				};				
				this.onRender = function(){
					this.ui.date.datepicker({ dateFormat: "yy/mm/dd" });
				};
			},

			setVal: function(val, loud){
				this.ui.date.val(val);
				if(loud) {
					this._triggerEvent({type: 'change'});
				}
			},

			getVal: function(){
				return this.ui.date.val();
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
				if(this.parentCt){
					host = this.parentCt;
				}
				host.trigger('editor:' + e.type, this.model.get('name'), this);
			}

		});
		return Editor;

	});

	app.Util.Tpl.build('editor-datepicker-tpl', [
		'{{#if label}}',
			'<label class="control-label {{#if layout}}{{layout.label}}{{/if}}" for="{{uiId}}">{{label}}</label>',
		'{{/if}}',
		'<div class="{{#if layout}}{{layout.field}}{{/if}}" data-toggle="tooltip" title="{{tooltip}}">', //for positioning with the label.
			'<div class="{{type}}">',
				'<input ui="date" name="{{name}}" type="text" id="{{uiId}}" placeholder="{{placeholder}}" value="{{value}}" class="form-control">',
			'</div>',	
			//msg & help
			'{{#if help}}<span class="help-block" ui="help" style="margin-bottom:0"><small>{{help}}</small></span>{{/if}}',
			'<span class="help-block input-error" ui="msg">{{msg}}</span>',
		'</div>'
	]);

})(Application);