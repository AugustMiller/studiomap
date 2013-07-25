/*
	AJAX Interface for Maps App.
*/



/*
	Studio Cachine and Card Display Class
*/

function Studios ( endpoint ) {
	var self = this;

	self.studios = [];
	self.endpoint = endpoint;
	self.search = $('#studio-query');
	self.inputs = self.search.find("input, select, button, textarea");
	self.mapContainer = $('.studio-map-holder');
	self.map = document.getElementById('map-primary')
	self.xhr;

	self.init();
}

Studios.prototype.init = function( ) {
	var self = this;

	self.createMap();

	self.search.on('submit' , function ( e ) {
		e.preventDefault();
		self.query();
	});

	$(window).on( 'resize.Map' , function ( ) {
		self.resize();
	});

	self.resize();
};

Studios.prototype.resize = function( ) {
	var self = this,
		mapHeight = ( window.innerHeight - $('.menu').outerHeight() - $('.search').outerHeight() );
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
		serialized = self.search.serialize();

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
	self.inputs.prop( 'disabled' , false );
};

Studios.prototype.parse = function ( results ) {
	var self = this;

	for ( var s = 0; s < results.length; s++ ) {
		var studio = results[s];
		if ( ! self.exists( studio.id ) ) {
			self.studios.push( new Studio( studio ) );
		}
	}
};

Studios.prototype.clean = function ( ) {
	var self = this;

	for ( var s = 0; s < self.studios.length; ) {
		if ( ! self.studios[s].active ) {
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

function Studio ( data ) {
	var self = this;

	self.id = data.id;
	self.title = data.body.studio_name;
	self.url = data.permalink;
	self.loc = self.latlng(data.body.location.coordinates);

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
		pin = new google.maps.Marker({
			position: self.loc,
			map: map,
			title: self.title,
			animation: google.maps.Animation.DROP
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
	self.active = true;
};

Studio.prototype.close = function( ) {
	var self = true;

	console.log("Closing.");
	self.active = false;
};

Studio.prototype.destroy = function( ) {
	var self = this;

	self.marker.setMap(null);

	console.log("Destroying");
};