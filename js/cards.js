	/***
	 jQuery Cookie
	 via. http://stilbuero.de/jquery/cookie/
	 ***/
	jQuery.cookie = function(name, value, options) {
	    if (typeof value != 'undefined') { // name and value given, set cookie
	        options = options || {};
	        if (value === null) {
	            value = '';
				options = jQuery.extend({}, options); // clone object since it's unexpected behavior if the expired property were changed
	            options.expires = -1;
	        }
	        var expires = '';
	        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
	            var date;
	            if (typeof options.expires == 'number') {
	                date = new Date();
	                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
	            } else {
	                date = options.expires;
	            }
	            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
	        }
	        // NOTE Needed to parenthesize options.path and options.domain
	        // in the following expressions, otherwise they evaluate to undefined
	        // in the packed version for some reason...
	        var path = options.path ? '; path=' + (options.path) : '';
	        var domain = options.domain ? '; domain=' + (options.domain) : '';
	        var secure = options.secure ? '; secure' : '';
	        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	    } else { // only name given, get cookie
	        var cookieValue = null;
	        if (document.cookie && document.cookie != '') {
	            var cookies = document.cookie.split(';');
	            for (var i = 0; i < cookies.length; i++) {
	                var cookie = jQuery.trim(cookies[i]);
	                // Does this cookie string begin with the name we want?
	                if (cookie.substring(0, name.length + 1) == (name + '=')) {
	                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
	                    break;
	                }
	            }
	        }
	        return cookieValue;
	    }
	};


	/**
	 * indexInArray function.
	 *
	 * @access public
	 * @param mixed arr
	 * @param mixed val
	 * @return void
	 */
	function indexInArray(arr,val){
		for(var i=0;i<arr.length;i++) if(arr[i]==val) return i;
		return -1;
	}
	function flip_ready() {
		// js for flipping cards
		jQuery('.flip').toggle(
		function(e){
			var link = jQuery(this);
			var id = link.attr('id');
	    	link.text('Flip Back');
	    	jQuery("#card-"+id.substring(5)).parent().addClass('flipped');

	        e.preventDefault();
	    },
	    function(e){
	    	var link = jQuery(this);
	    	var id = link.attr('id');
	    	link.text('Flip Card');
	    	jQuery("#card-"+id.substring(5)).parent().removeClass('flipped');
	    	e.preventDefault();
	    });
	}

	function toggle_ready() {
		jQuery("#toggle-photo-graphic-trigger").toggle(function(){
	    	jQuery(this).attr("class","show-photo").html("<i class='icon-pencil'></i> Graphic set");
	    	jQuery('.graphic').hide();
	    	jQuery('.photo').show();
	    	jQuery.cookie('phylo_view', 'photo', { path: '/'} );
	    	return false;
	    },function(){
	    	jQuery(this).attr("class","show-graphic").html("<i class='icon-camera'></i> Photo set");
	    	jQuery('.photo').hide();
	    	jQuery('.graphic').show();
	    	jQuery.cookie( 'phylo_view', 'graphic', { path: '/'} );
	    	return false;
	    });
	}


	/* Card Cart */
    var COOKIE_NAME = 'phylomon_cards';
    var COOKIE_OPTIONS = { path: '/'};
    var cookie_value = jQuery.cookie(COOKIE_NAME);

    var cookie_array = new Array();
    if( cookie_value )
		cookie_array = cookie_value.split(',');

    jQuery.each(cookie_array, function(index, value){
    	jQuery("#select-card-"+value).prop( "checked", true );
    });

    jQuery.ajax({
	  type: "GET",
	  url: Phylo.ajaxurl,
	  data: { action: "get_selected" }
	})
  .done( function( json_data ) {
  	if( !json_data ) {
  		return;
  	}
  	var data = jQuery.parseJSON(json_data),
  		html = '';
  	if( ! data ) {
  		return;
  	}
  	for ( var i = 0; i < data.length; i++) {
  		var id = data[i].id;
  		var elm = document.getElementById( "select-card-" + id );
  		if( elm ) {
  			elm.checked = true;
  		}
 		html += '<li id="card-in-cart-'+id+'"><a id="card-name-'+id+'" href="'+data[i].permalink+'">'+data[i].title+'</a></li>'
	}

	document.getElementById( "phylomon-cards-cart-list" ).innerHTML = html;
	document.getElementById( "no-cards" ).style.display = 'none';

  });

    function select_cards_ready() {
	 	// js for selecting cards
	    // Select cards and place them into the card cart
	    jQuery('input.select-card').on('click', function() {
	  		// modify cookie
	  		cookie_value = jQuery.cookie(COOKIE_NAME);
	  		card_id = jQuery(this).val();
	  		card_permalink = jQuery('#card-permalink-'+card_id);
	  		card_link = card_permalink.attr("title");
	  		card_url  = card_permalink.attr("href");
	  		// console.log("start ",this.checked,cookie_value);
	  		if( this.checked ) // add a card to
	  		{
	  			// set cookie
	  			if(cookie_value)
	  				cookie_value = cookie_value+","+card_id;
	  			else
	  				cookie_value = card_id;

	  			jQuery.cookie(COOKIE_NAME, cookie_value,COOKIE_OPTIONS);
	  			console.log( 'do the stuff' );
	  			// add the card to the cart
	  			jQuery("#phylomon-cards-cart-list").prepend("<li id='card-in-cart-"+card_id+"'><a href='"+card_url+"'>"+card_link+"</a></li>");

	  			jQuery("#no-cards").hide();
	  		}
	  		else{
	  			// delete the sting from the cookie
	  			var cookie_array = new Array();
	  			cookie_array = cookie_value.split(',');
	  			var index = indexInArray(cookie_array,card_id);
	  			if(index != -1)
	  				cookie_array.splice(index,1)

	  			cookie_value = cookie_array.toString();

	  			//console.log("remove cookie", cookie_value);
	  			jQuery.cookie(COOKIE_NAME, cookie_value,COOKIE_OPTIONS);

	  			// console.log( cookie_value );
	  			// remove the card from the cart
	  			jQuery("#card-in-cart-"+card_id).remove();

	  			if( !cookie_value )
	  				jQuery("#no-cards").show();
	  		}
	  		highlight_cart();
	  	});
	}

	// Remove all the cards from the cart at once
    jQuery("#remove-cards").click(function() {
    	if(confirm("Are you sure you want to remove all selected Cards? \nThis cannot be undone! "))
    	{
    		// delete the cookie
    		jQuery.cookie(COOKIE_NAME, null, COOKIE_OPTIONS);
    		jQuery('input.select-card').attr('checked', false);
    		jQuery("#phylomon-cards-cart-list").html("");
    		jQuery("#no-cards").show();
    		highlight_cart();

    	}

    	// do nothing
    	return false;
    })


	function highlight_cart(){
		jQuery("#phylomon-cards-cart-list").addClass('fade');
		setTimeout(function(){jQuery("#phylomon-cards-cart-list").removeClass('fade');},1500);

	}

	// autocomplete
	var cache = {};
	function autocomplete_ready() {
		jQuery("#card-autosearch").autocomplete({
			source:function(request, response) {
				if (cache.term == request.term && cache.content) {
					response(cache.content);
				}
				if (new RegExp(cache.term).test(request.term) && cache.content && cache.content.length < 13) {
					var matcher = new RegExp(jQuery().ui.autocomplete.escapeRegex(request.term), "i");
					response(jQuery().grep(cache.content, function(value) {
	    				return matcher.test(value.value)
					}));
				}
				request.action = "search_cards";

				jQuery.ajax({
					url: Phylo.ajaxurl,
					dataType: "json",
					data: request,
					success: function(data) {

						// Set the cache term
						cache.term = request.term;

						// Loop over the returned data and format it for display
						for(var i in data){
							var img = "";
							if(data[i].img != ""){
								// Build the image tag
								img = '<img src="'+data[i].img+'" class="card-thumb" alt="'+data[i].value+'" />';
							}
							// Build the label
							var label = data[i].value;
							// Set the new label
							data[i].label = label;
						}

						// Fill the cache with the formated data
						cache.content = data;
						// Pass the retrieved card data to the autocomplete function to display them
						response(data);
					},
					error: function(jqXHR, textStatus, errorThrown){
						console.log("AJAX FAILED! textStatus: ",textStatus,"errorThrown: ",errorThrown);
					}
				});
			},
			delay:100,
			minLength:1,
			select: function(event, ui) {
				if ( ui && ui.item && ui.item.id ) {
					window.location.href = window.location.origin + "?p="+ ui.item.id;
				}
			}
		});
	}
// run when dom is loaded
( function( $ ) {
		select_cards_ready();
		toggle_ready();
		autocomplete_ready();
		flip_ready();
		jQuery( document.body ).on( 'post-load', function () {
			select_cards_ready();
			toggle_ready();
			flip_ready();
		});
	} )( jQuery );
/*
CSS Browser Selector v0.4.0 (Nov 02, 2010)
Rafael Lima (http://rafael.adm.br)
http://rafael.adm.br/css_browser_selector
License: http://creativecommons.org/licenses/by/2.5/
Contributors: http://rafael.adm.br/css_browser_selector#contributors
*/
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);

