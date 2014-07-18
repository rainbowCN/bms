/**
 * This is the ActionCell definition 
 *
 * options
 * -------
 * passed down by this.model.get('actions')
 * 
 * actions: { (replace the actions)
 * 		'name': {
 * 			label: ...,
 * 			icon: ...,
 * 			tooltip: ...,
 * 			fn: function(){
 * 				this.model is the row record data model
 * 			}
 * 		},
 * 		...
 * }
 *useless
 * @author James.Yu
 * @created 2013.11.27
 */

;(function(app){

	app.widget('OperateCell', function(){

		var UI = app.view({
			template: [
				'{{#each actions}}',
					'<span class="action-cell-item" action="{{@key}}" data-toggle="tooltip" title="{{tooltip}}"><i class="{{icon}}"></i> {{label}}</span> ',
				'{{/each}}'
			],
			className: 'action-cell',

			initialize: function(options){
				this.row = options.row;
				var actions = this.model.get('actions') || [];

					//default
					_.each({
						edit: {
							icon: 'fa fa-edit',
							tooltip: 'Edit'
						},
						'remove': {
							icon: 'fa fa-trash-o',
							tooltip: 'Remove'
						}
					}, function(def, name){
						if(actions[name]){
							actions[name] = _.extend(def, actions[name]);
						}else
							actions[name] = def;
					});


				//allow action impl overriden by action config.fn
				this.actions = this.actions || {};
				_.each(actions, function(action, name){
					if(action.fn){
						this.actions[name] = function($action){
							action.fn.apply(this.row, arguments);
							/*Warning:: If we use options.row here, it won't work, since the options object will change, hence this event listener will be refering to other record's row when triggered*/
						}
					}
				}, this);
				this.model.set('actions', actions);
				this.enableActionTags(true);
			},
			tooltips: true

		});
		return UI;
	});	

})(Application);
