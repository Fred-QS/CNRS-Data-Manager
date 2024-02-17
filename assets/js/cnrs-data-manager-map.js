/*********************************************************************************************
 *                              CNRS Data Manager Map JS                              *
 ********************************************************************************************/

let earth;
let sunAngle = 0;
let activeHotspot = null;
let activeLabel = null;
let config;

let updateMapTimer = false;
let lightMask, lightMaskCtx, lightGradient;

window.addEventListener('cnrsMapLoaded', function() {
	prepareWorldMap();
});

function prepareWorldMap() {
	document.querySelectorAll('.cnrs-dm-map').forEach(function(container) {

		const pre = container.querySelector('.cnrs-dm-map-data');
		config = JSON.parse(pre.innerHTML);
		pre.remove();

		if (earth !== null && earth !== undefined) {
			earth.redrawMap();
		}

		let options = {
			location: {
				lat: Number(config.main.lat) || 0,
				lng: Number(config.main.lng) || 0,
			},
			zoom: 0.925,
			dragPolarLimit : 0.5,
			autoRotate : true,
			autoRotateSpeed: 1,
			autoRotateDelay: 0,
			autoRotateStart: 2000
		};

		if (config.sunlight === true) {
			options.sunLocation = { lat: 0, lng: 360 - sunAngle };
			options.light = 'sun';
			options.lightAmbience = 0.8;
			options.shadows = false;
			options.shininess = 0.14;
		}

		if (config.view === 'hologram') {
			options.light = 'none';
			options.mapImage = '/wp-content/plugins/cnrs-data-manager/assets/media/maps/hologram/hologram-map.svg';
			options.transparent = true;
		} else if (config.view === 'news') {
			options.light = 'none';
			options.transparent = true;
			options.mapSeaColor = 'RGBA(255,255,255,0.76)';
			options.mapLandColor = '#383838';
			options.mapBorderColor = '#5D5D5D';
			options.mapBorderWidth = 0.25;
			options.mapHitTest = true;
		} else if (config.view === 'classic') {
			options.light = 'simple';
			options.transparent = true;
			options.mapLandColor = '#7193cb';
			options.mapSeaColor = 'rgba(0,51,153,0.8)';
			options.mapBorderColor = 'rgba(0,51,153,1)';
			options.mapBorderWidth = 0.25;
		} else if (config.view === 'cork') {
			options.lightAmbience = 0.65,
				options.light = 'sun';
			options.sunDirection = { x: -0.2, y: 0.3 };
			options.lightIntensity = 0.55;
			options.shininess = 0.12;
			options.mapSeaColor = '#dfd9d4';
			options.mapLandColor = '#765f4f';
			options.mapBorderColor = '#d2aa8a';
			options.mapBorderWidth = 0.6;
		}

		earth = new Earth(container, options);

		if (config.view === 'cork') {
			earth.addEventListener('drawtexture', function() {
				// draw cork pattern
				earth.mapContext.globalCompositeOperation = 'multiply';
				let cells = 5;
				let size = earth.mapCanvas.height/cells;

				for ( var row = 0; row < cells; row++ ) {
					for ( let col = 0; col < cells * 2; col++ ) {
						earth.mapContext.drawImage(
							document.querySelector('#cnrs-dm-map-cork'),
							0, 0, 256, 256,
							col*size, row*size, size, size
						);
					}
				}
			});
		}
		// add markers when ready
		earth.addEventListener('ready', function() {
			if (config.stars === true || config.black_bg === true) {
				container.classList.add('cnrs-dm-map-stars');
			} else {
				container.classList.remove('cnrs-dm-map-stars');
			}
			let map = this;
			map.startAutoRotate();

			if (config.stars === true) {

				let star_count = 2000;
				let stars = [];

				for (let i= 0; i < star_count; i++) {
					stars.push( {
						location : {lat: getRandomInt(-50,50), lng: getRandomInt(-180,180)},
						offset: getRandomInt(35,60),
						opacity: getRandomInt(20,80)/100,
						color: 'rgb('+ getRandomInt(180,255) +','+ getRandomInt(180,255) +','+ getRandomInt(180,255) +')',
					} );
				}

				map.addPoints({
					points: stars,
					scale: 0.5 + window.innerWidth / 2000
				});
			}

			config.markers.forEach( function(element, i) {
				let label = map.addOverlay( {
					location: {
						lat: Number(element.lat) || 0,
						lng: Number(element.lng) || 0,
					},
					offset: 0.1,
					content : `<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg><strong>${element.title}</strong></span>`,
					depthScale : 0.4,
					visible : false,
					className : (function () {
						if (config.view === 'news') {
							return 'cnrs-dm-map-label-news';
						} else if (config.view === 'classic') {
							return 'cnrs-dm-map-label-classic';
						}  else if (config.view === 'hologram') {
							return 'cnrs-dm-map-label-hologram';
						} else {
							return 'cnrs-dm-map-label';
						}
					})()
				});
				marker = map.addOverlay({
					location: {
						lat: Number(element.lat) || 0,
						lng: Number(element.lng) || 0,
					},
					depthScale: 0.4,
					className: (function () {
						if (config.view === 'hologram') {
							return 'cnrs-dm-map-hotspot-hologram';
						} else if (config.view === 'news') {
							return 'cnrs-dm-map-hotspot-news';
						} else if (config.view === 'classic') {
							return 'cnrs-dm-map-hotspot-classic';
						} else {
							return 'cnrs-dm-map-hotspot';
						}
					})()
				});
				// link
				if (element.link) {
					marker.element.addEventListener('click', function() {
						window.open(this.data.link);
					});
				}

				marker.element.addEventListener( 'click', function() {
					if (activeHotspot) {
						activeHotspot.reverse();
					};
					label.visible = true;
					activeHotspot = marker;
					activeLabel = label;
				});

				marker.reverse = function() {
					activeLabel.visible = false;
				};
			});
			if (config.view === 'space') {
				prepareLightMask();
				updateMapTexture();
			}
		});

		if (config.sunlight === true) {
			document.querySelector("#cnrs-dm-map-sun-slider").addEventListener('input', function () {
				sunAngle = this.value;
				earth.sunLocation = {lat: 0, lng: 360 - this.value};
				// limit redraws
				if (updateMapTimer) return;
				updateMapTimer = setTimeout(updateMapTexture, 100);
			});
		}
	});
}

function prepareLightMask() {

	lightMask = document.createElement('canvas');
	lightMaskCtx = lightMask.getContext('2d');

	var w = earth.mapCanvas.width, h = earth.mapCanvas.height;

	lightMask.width = w * 2;
	lightMask.height = h;

	if (config.sunlight === true) {
		lightGradient = lightMaskCtx.createRadialGradient(
			w / 2, h / 2, h * 0.38,
			w / 2, h / 2, w * 0.28,
		);

		lightGradient.addColorStop(0, "RGBA(0,0,0,1)");
		lightGradient.addColorStop(1, "RGBA(0,0,0,0)");
	}
}


function updateMapTexture() {

	updateMapTimer = false;
	let w = earth.mapCanvas.width, h = earth.mapCanvas.height;

	if (config.sunlight === true) {
		// night background
		let nightImg = document.querySelector('#cnrs-dm-map-night');
		earth.mapContext.drawImage(nightImg, 0, 0, nightImg.width, nightImg.height, 0, 0, w, h);
	} else {
		let nightImg = document.querySelector('#cnrs-dm-map-day');
		earth.mapContext.drawImage(nightImg, 0, 0, nightImg.width, nightImg.height, 0, 0, w, h);
	}

	let offset = sunAngle / 360 * w;

	lightMaskCtx.clearRect(0, 0, w*2, h);
	lightMaskCtx.globalCompositeOperation = 'source-over';
	lightMaskCtx.fillStyle = lightGradient;
	lightMaskCtx.fillRect( 0, 0, w, h );
	lightMaskCtx.drawImage( lightMask, 0, 0, w, h, w, 0, w, h );


	let dayImg = document.querySelector('#cnrs-dm-map-day');
	lightMaskCtx.globalCompositeOperation = 'source-in';
	lightMaskCtx.drawImage( dayImg, 0, 0, dayImg.width, dayImg.height, offset, 0, w, h );

	earth.mapContext.drawImage( lightMask, offset, 0, w, h, 0, 0, w, h );
	earth.updateMap();
}

function getRandomInt(min, max) {
	min = Math.ceil(min);
	max = Math.floor(max);
	return Math.floor(Math.random() * (max - min)) + min;
}
