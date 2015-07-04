(function ($) {
	// todo: prevent form submittion before the user has fillied out everything
	// console.log($.browser);
	var color_change_timer = false;
	$('#card_colour').iris({
        width: 200,
        change: function( event, ui) {
        	if(!color_change_timer) {
	        	// console.log(ui.color.toCSS());
	        	color_change_timer = setTimeout(function(){
	        		form.update_color(event, ui.color.toCSS());
	        		color_change_timer = false;
	        	},500);
        	}
        }
    });
    
    $('input:not(#card_colour),select,textarea').focus(function(event){
    	$('#card_colour').iris('hide');
    });
    $("#card_colour").blur( function( event ) {
    	console.log(event);
    });
   
    $("#card_colour").focus(function(event){
    	$('#card_colour').iris( 'show' );
    })
    
	var temperatures = [];
	$(".temperature:checked").each(function(index, el){
	  		temperatures.push( $(el).val() );
	  	});
	//define product model
    var Card = Backbone.Model.extend({
        defaults: {
            'id' 				: '',
            'background_color'  : $('#card_colour').val(),
			'background' 		: ( $('#card-background-src').val() ? $('#card-background-src').val() : card_global.theme_url+'/img/blank.gif'),
			'permalink'			: '',
			'excerpt'			: '',
			'name_size'			: '',
			'title'     		: $("#common_name").val(),
			'title_attr'		: '',
			'latin_name'		: $("#latin_name").val(),
			'scale'     		: $('#scale').val(),
			'food_chain'		: $('#food_chain_hierarchy').val() ,
			'graphic'   		: '',
			'image'     		: ( $('#card-image').val() ? $('#card-image').val() :  card_global.theme_url+'/img/blank-card.gif' ),
			'image_credit'     	: '',
			'classification' 	: '',
			'point_score'		: $('#card_point_value').val(),
			'card_text'			: $('#card_info').val(),
			'temperature'		: temperatures.join(", "),
			'graphic_credit'	: 'image_credit',
			'habitat_1'			: $('#habitat_1').val(),
			'habitat_2'			: $('#habitat_2').val(),
			'habitat_3'			: $('#habitat_3').val()
        }
    });
    
    
	var CardForm = Backbone.View.extend({
	  initialize: function() {
	  		
            this.card = new Card();
            this.render();
            this.update_diet();
            this.update_scale();
           
      },
      model: null,
	  el: '#post',
	  events: {
	    "keyup #common_name": "update_name",
	    "keyup #card_info": "update_text",
	    "keyup #latin_name": "update_latin_name",
	    "change #diet": "update_diet",
	    "change #food_chain_hierarchy": "update_diet",
	    "keyup #card_point_value": "update_point_value",
	    "change #scale": "update_scale",
	    "change #card_colour": "update_color",
	    "change #card_image":"preview_image",
	    "change .checkbox": "update_temperature",
	    "change .habitat-select": "update_habitat"
	  },
	  card: false,
	  update_name: function(evt){
	  	
	  	this.card.set({ title: $("#common_name").val()})
	    this.render();
	  },
	  update_latin_name: function(evt){
	  	
	  	this.card.set({ latin_name: $("#latin_name").val()})
	    this.render();
	  },
	  update_text: function(evt){
	  	var card_text = $("#card_info").val();

	  	this.card.set({card_text: card_text.replace(/(?:\r\n|\r|\n)/g, '<br />') });
	  	
	    this.render();
	  },
	  update_scale: function(evt){
	  		var val = $("#scale").val();
	  		
	  		if( val == 'null' || !val ) {
		  		var img = ''; 
	  		} else {
  				var src = '';
  				var img = '<img src="'+card_global.theme_url+'/img/num/'+val+'.png" alt="Scale '+val+'" />';
  			}
  			
	  		this.card.set({scale: img });
	  		this.render();
	  },
	  update_diet: function(evt){
	  	var diet = $("#diet").val();

	  	var food_chain = $("#food_chain_hierarchy").val();
	  	
	  	if( food_chain != 'null' && diet != 'null'){
	  		
	  		var src = card_global.theme_url+'/img/num/'+diet+food_chain+'.png';
	  		var img = '<img src="'+src+'" alt="Diet  '+diet+' '+' Food Chain Hierarchy '+food_chain+'" />'
	  		this.card.set({food_chain: img });
	  		this.render();
	  	
	  	} else {
	  		this.card.set({food_chain: ''});
	  	}
	  	
	  },
	  preview_image: function(){
	  	var input = $("#card_image").get( 0 );
	  	
	  	if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
            	
            	form.update_graphic(e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
	  	
	  },
	  update_graphic: function(src){
	  	
	  	this.card.set({ image: src });
	  	this.render();
	  
	  },
	  update_temperature: function(evt){
	  	
	  	var temperatures =  new Array;
	  	$(".temperature:checked").each(function(index, el){
	  		temperatures.push( $(el).val() );
	  	});
	  	this.card.set({temperature:temperatures.join(", ")});
	  	this.render();
	  	
	  },
	  update_point_value: function(evt){
	  	var point = $("#card_point_value").val();
	  	this.card.set({point_score:point});
	  	this.render();
	  },
	  update_color: function(evt, color){
	  		
	  		$("#card-").css("background", color);
	  		this.card.set({ background_color: color})
	  		this.get_card_image();
	  		this.render();
	  		
	  },
	  update_habitat: function(evt){
	  	var select =  $(evt.currentTarget);
	  	var id = select.attr('id');
	  	
	  	var value = select.val();
	 
	  	this.card.set(id, value);
	  	this.get_card_image();
	  	
	  	
	  },
	  render: function(){
	  	// this.cards = 
	  	// console.log(this.card);
	  	
	  	var template = _.template( $("#card-template").html(), this.card.attributes );
            // Load the compiled HTML into the Backbone "el"
           $("#preview-card").html( template );
        
	  },
	  get_card_image: function(){
	  	var data = {
			action: 'get_card_image',
			habitat_1: this.card.get('habitat_1'),
			habitat_2:this.card.get('habitat_2'),
			habitat_3:this.card.get('habitat_3'),
			color:this.card.get('background_color')
		};
		// console.log(card_global.ajax_url, data);
		$.post( card_global.ajax_url, data, function(response) {
			form.update_card_image(response);
			
		});
	  },
	  update_card_image: function( card_image ){
	  	this.card.set('background', card_image);
	  	this.render();
	  }

	})
	
	var form = new CardForm();
}(jQuery));
