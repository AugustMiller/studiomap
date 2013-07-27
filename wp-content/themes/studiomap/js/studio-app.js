/*
	AJAX Interface for Maps App.
*/



/*
	Studio Cachine and Card Display Class
*/

function Studios ( endpoint , dir ) {
	var self = this;

	self.studios = [];
	self.endpoint = endpoint;
	self.directory = dir;
	self.searchForm = $('#studio-query');
	self.searchContainer = $('section.search');
	self.searchOpen = false;
	self.searchBtn = $('.filter-button');
	self.inputs = self.searchForm.find("input, select, button, textarea");
	self.mapContainer = $('.studio-map-holder');
	self.map = document.getElementById('map-primary')
	self.cardHolder = $('#rolodex');
	self.xhr;

	self.init();
}

Studios.prototype.init = function( ) {
	var self = this;

	self.createMap();

	self.searchForm.on('submit' , function ( e ) {
		e.preventDefault();
		self.query();
	});

	$(window).on( 'resize.Map' , function ( ) {
		self.resize();
	});

	self.searchBtn.on( 'click.Search' , function ( ) {
		if ( self.searchOpen ) {
			self.hide();
		} else {
			self.show();
		}
	});

	self.hide();
	self.resize();
};

Studios.prototype.show = function( ) {
	var self = this;

	self.searchContainer.slideDown();
	self.searchOpen = true;
};

Studios.prototype.hide = function( ) {
	var self = this;

	self.searchContainer.slideUp();
	self.searchOpen = false;
};

Studios.prototype.resize = function( ) {
	var self = this,
		mapHeight = ( window.innerHeight - $('.menu').outerHeight() );
	self.mapContainer.height( mapHeight );
};

Studios.prototype.createMap = function( ) {
	var self = this,
		myLatlng = new google.maps.LatLng(45.52,-122.676),
		stylers = [
			{
				"featureType": "landscape.natural.terrain",
				"stylers": [
					{ "visibility": "off" }
				]
			},{
				"featureType": "transit",
				"stylers": [
					{ "visibility": "off" }
				]
			},{
				"featureType": "poi",
				"stylers": [
					{ "visibility": "simplified" }
				]
			},{
				"featureType": "road.highway",
				"elementType": "geometry.fill",
				"stylers": [
					{ "color": "#cfcfcf" }
				]
			},{
				"featureType": "road.highway",
				"elementType": "geometry.stroke",
				"stylers": [
					{ "color": "#ffffff" }
				]
			},{
				"featureType": "road.arterial",
				"elementType": "geometry.fill",
				"stylers": [
					{ "lightness": -11 },
					{ "color": "#b7b4b4" }
				]
			},{
				"elementType": "geometry.stroke",
				"stylers": [
					{ "visibility": "off" }
				]
			},{
				"elementType": "labels.text.stroke",
				"stylers": [
					{ "visibility": "off" }
				]
			},{
				"elementType": "labels.icon",
				"stylers": [
					{ "hue": "#08ff00" },
					{ "saturation": -100 },
					{ "gamma": 0.64 }
				]
			},{
				"featureType": "water",
				"elementType": "geometry.fill",
				"stylers": [
					{ "color": "#86d2ff" }
				]
			},{
				"featureType": "water",
				"elementType": "labels",
				"stylers": [
					{ "visibility": "off" }
				]
			},{
				"featureType": "road.arterial",
				"elementType": "geometry.stroke",
				"stylers": [
					{ "color": "#ffffff" },
					{ "visibility": "off" }
				]
			},{
				"featureType": "landscape.man_made",
				"elementType": "geometry.fill",
				"stylers": [
					{ "visibility": "on" },
					{ "saturation": -100 },
					{ "lightness": 5 }
				]
			},{
				"featureType": "road",
				"elementType": "labels.text.stroke",
				"stylers": [
					{ "visibility": "on" },
					{ "color": "#ffffff" }
				]
			}
		],
		mapOptions = {
			zoom: 13,
			center: myLatlng,
			disableDefaultUI: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			styles : stylers
		};

	self.mapContainer.css('height',window.innerHeight);

	map = new google.maps.Map( self.map , mapOptions );
};

Studios.prototype.query = function( ) {
	var self = this,
		serialized = self.searchForm.serialize();

	console.log( serialized );

	if ( self.xhr ) {
		self.xhr.abort();
	}

	self.beforeQuery();

	self.xhr = $.ajax({
		url : self.endpoint,
		type : "POST",
		data : serialized
	});

	self.xhr.done( function ( response , textStatus , jqXHR ) {
		console.log( response );
		self.parse( response.studios );
	});

	self.xhr.always( function ( ) {
		console.log("Always");
		self.afterQuery();
	});

	self.xhr.fail( function ( ) {
		console.log("Fail");
		self.afterQuery();
	});

};

Studios.prototype.beforeQuery = function ( ) {
	var self = this;
	self.inputs.prop( 'disabled' , true );
	self.clean();
};

Studios.prototype.afterQuery = function ( ) {
	var self = this;
	self.hide();
	self.inputs.prop( 'disabled' , false );
};

Studios.prototype.parse = function ( results ) {
	var self = this;

	for ( var s = 0; s < results.length; s++ ) {
		var studio = results[s];
		if ( ! self.exists( studio.id ) ) {
			self.studios.push( new Studio( studio , this ) );
		}
	}
};

Studios.prototype.tuck = function( ) {
	var self = this;

	for ( var s = 0; s < self.studios.length; ) {
		if ( self.studios[s].active ) {
			self.studios[s].collapse();
		}

		if ( self.studios[s].saved ) {
			self.studios[s].card.css('z-index', ( 2000 - s ) );
		}
		s++;
	}
};

Studios.prototype.clean = function ( ) {
	var self = this;

	for ( var s = 0; s < self.studios.length; ) {
		if ( ! self.studios[s].saved ) {
			self.studios[s].destroy();
			self.studios.splice( s , 1 );
		} else {
			s++;
		}
	}
};

Studios.prototype.exists = function ( id ) {
	var self = this;

	for ( var s = 0; s < self.studios.length; s++ ) {
		if ( self.studios[s].id === id ) return true;
	}
};

function Studio ( data , parent ) {
	var self = this;

	self.id = data.id;
	self.listings = parent;
	self.title = data.body.studio_name;
	self.url = data.permalink;
	self.loc = self.latlng(data.body.location.coordinates);
	self.active = false;
	self.saved = false;

	console.log( self );
	self.init();
}

Studio.prototype.init = function( ) {
	var self = this;

	self.marker = self.pin();
};

Studio.prototype.latlng = function( coords ) {
	var self = this,
		split = coords.split(','),
		location = new google.maps.LatLng( parseFloat( split[0] ) , parseFloat( split[1] ) );

	return location;
};

Studio.prototype.pin = function( ) {
	var self = this,
		pinImage = {
			url: self.listings.directory + '/images/map-pin.svg',
			size: new google.maps.Size( 35 , 35 ),
			// The origin for this image is 0,0.
			origin: new google.maps.Point( 0 , 0 ),
			// The anchor for this image is the base of the flagpole at 0,32.
			anchor: new google.maps.Point( 30 , 11 )
		},
		pin = new google.maps.Marker({
			position: self.loc,
			map: map,
			title: self.title,
			animation: google.maps.Animation.DROP,
			icon: pinImage
		});

	// So, we can register clicks on each header.

	google.maps.event.addListener( pin , 'click', function( ) {
		self.open();
	});

	return pin;
};

Studio.prototype.open = function( ) {
	var self = this;

	console.log("Opening.");

	if ( self.loaded ) {
		console.log("Already loaded!");
		if ( !self.active ) {
			self.show();
		} else {
			self.collapse();
		}
	} else {
		console.log("Needs load.");
		self.load();
	}

	self.saved = true;
};

Studio.prototype.show = function ( ) {
	var self = this;

	self.card.slideDown();
	self.expand();
};

Studio.prototype.expand = function( ) {
	var self = this;

	self.listings.tuck();

	self.active = true;

	self.card.animate({
		'margin-bottom' : 0
	},{
		duration : 250
	});

};

Studio.prototype.close = function( ) {
	var self = this;

	console.log("Closing.");

	self.card.slideUp().remove();
	self.card = null;
	self.loaded = false;
	self.active = false;
	self.saved = false;
};

Studio.prototype.hide = function( ) {
	var self = this;

	self.card.fadeOut();
};

Studio.prototype.collapse = function ( ) {
	var self = this;

	self.card.animate({
		'margin-bottom' : ( ( - self.card.height() ) + 25 ) + 'px'
	},{
		duration : 250
	});

	self.active = false;
};

Studio.prototype.load = function( ) {
	var self = this;
	$.ajax({
		url : self.url,
		method : "GET",
		success : function ( data ) {
			self.build( data );
		},
		failure : function ( data ) {
			self.close();
		}
	})
};

Studio.prototype.build = function ( html ) {
	var self = this;

	console.log("Studio.load()");

	$( html ).appendTo( self.listings.cardHolder );

	self.card = $( '#studio-' + self.id );
	self.card.on( 'click.Studio' , function ( ) {
		self.open();
	});

	self.closeBtn = self.card.find('.close');
	self.closeBtn.on( 'click.Studio' , function ( ) {
		self.close();
	});

	self.loaded = true;
	self.show();
};

Studio.prototype.destroy = function ( ) {
	var self = this;

	if ( self.loaded ) {
		self.close();
	}
	
	self.marker.setMap( null );
	self.loaded = false;

	console.log("Destroying");
};