window.addEventListener( "cnrsMapLoaded", function() {
	document.querySelectorAll('.cnrs-dm-map').forEach( function(container) {
		const pre = container.querySelector('.cnrs-dm-map-data');
		const config = JSON.parse(pre.innerHTML);
		pre.remove();
		// set earth options
		let earth_options = {
			location: {
				lat: Number(config.main.lat) || 0,
				lng: Number(config.main.lng) || 0,
			},
			// define options from data parameters or based on id/classes
			mapLandColor : config.main.land || '#EEE',
		};
		// create earth
		let earth = new Earth( container, earth_options );
		// add markers when ready
		earth.addEventListener( 'ready', function() {
			let earth = this;
			config.markers.forEach( function(element) {
				// set marker options
				let marker_options = {

					mesh : 'Pin3',
					hotspot: true,
					hotspotHeight: 0.2,

					location: {
						lat: Number(element.lat) || 0,
						lng: Number(element.lng) || 0,
					},

					color: element.color || '#FF0000',
					scale: Number(element.scale) || 1.5,
					// apply options from parameters
					data: element // reference to the complete dataset
				};
				let marker = earth.addMarker(marker_options);
				// link
				if ( element.link ) {
					marker.addEventListener('click', function() {
						window.open(this.data.link);
					});
				}
				// title tooltip
				if ( element.title ) {
					marker.addEventListener('mouseover', showTitleOverlay);
					marker.addEventListener('mouseout', hideTitleOverlay);
				}
			});
		});
	});
});

// handle overlays
function showTitleOverlay() {
	// create an overlay for the markers on this earth on first use
	if (!this.earth.myOverlay ) {
		this.earth.myOverlay = this.earth.addOverlay({
			transform: ''
		});
	}
	this.earth.myOverlay.content = this.data.title;
	this.earth.myOverlay.location = this.location;
	this.earth.myOverlay.visible = true;
}

function hideTitleOverlay() {
	if (this.earth.myOverlay) this.earth.myOverlay.visible = false;
}
