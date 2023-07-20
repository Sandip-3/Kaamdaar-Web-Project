const MAPBOX_TOKEN = "pk.eyJ1Ijoia2FhbWRhYXIiLCJhIjoiY2wwNWhwNjl5MDhjMjNjbnoxajMxOXExMCJ9.KDuSWfD7invXCiRTg_WMfg";
mapboxgl.accessToken = MAPBOX_TOKEN;

function initMap(center)
{
	var mapOptions = {
		container: 'map',
		// style: 'mapbox://styles/mapbox/satellite-streets-v11',
		style: 'mapbox://styles/kaamdaar/cl05hz32x001d14o3p6emqazs',
		zoom: 0, // default is 0; no zoom
		center
	};

	// main map object
	const map = new mapboxgl.Map(mapOptions);
	map.addControl(new mapboxgl.NavigationControl({
			visualizePitch: true
		}),
		"bottom-right"); // Add zoom and rotation controls to the map.

	let userLocControl = new mapboxgl.GeolocateControl({
		positionOptions: {
			enableHighAccuracy: true
		},
		trackUserLocation: true,
		// Draw an arrow next to the location dot to indicate which direction the device is heading.
		showUserHeading: true
	});

	map.addControl(userLocControl, "bottom-right");

	for(let m of markerArray)
	{
		addMarker(map, m);
		console.log(m);
	}
}

function addMarker(map, latlong)
{
	const el = document.createElement('div');
	el.className = 'marker';

	let requestType = "plumber";
	switch(requestType)
	{
		case "plumber":
		el.style.backgroundImage = "";
		break;
	}

	const marker = new mapboxgl.Marker(el, {
									color: "#FF0000",
									draggable: true
								})
					.setLngLat([latlong[0], latlong[1]])
					.addTo(map);

	marker.on("dragstart", () => {
		console.log("Drag start");
	});

	marker.on("dragend", () => {
		console.log("Drag end");
	});
}

function onLocationAccessSuccess(pos)
{
	// init map with users current location
	// mapbox is kinda weird, it takes longitude first and then latitude
	const userLoc = [pos.coords.longitude, pos.coords.latitude];
	initMap(userLoc);
}

function onLocationAccessError(err)
{
	console.warn(`ERROR(${err.code}): ${err.message}`);

	const ktmLatLong = [85.3240, 27.7172];
	initMap(ktmLatLong); // default to ktm lat long
}

function requestAndLoadMap()
{
	if(navigator.geolocation)
	{
		let locOptions = {
			maximumAge: 0,
			timeout: 40000,
			enableHighAccuracy: true
		};

		navigator.geolocation.getCurrentPosition(
												onLocationAccessSuccess,
												onLocationAccessError,
												locOptions
											);
	}
	else
		console.log("Geolocation not supported by this browser.");
}

requestAndLoadMap();