const MAPBOX_TOKEN = "pk.eyJ1Ijoia2FhbWRhYXIiLCJhIjoiY2wwNWhwNjl5MDhjMjNjbnoxajMxOXExMCJ9.KDuSWfD7invXCiRTg_WMfg";
mapboxgl.accessToken = MAPBOX_TOKEN;

var showModal;
var reqLocation = document.querySelector(".req-loc p");

(function initMapModal() {
	showModal = () => 
	{
		document.getElementById('modal').style.display = "block";
	}

	window.onclick = (e) => {
		if(window.event.target == modal)
			modal.style.display = "none";
	}
})();

function initMap(start)
{
	const map = new mapboxgl.Map({
		container: 'map',
		style: 'mapbox://styles/mapbox/streets-v11',
		center: start,
		zoom: 15
	});

	longlat = map.getCenter();

	map.addControl(new mapboxgl.NavigationControl({
			visualizePitch: true
		}),
		"bottom-right");

	let userLocControl = new mapboxgl.GeolocateControl({
		positionOptions: {
			enableHighAccuracy: true
		},
		trackUserLocation: true,
		showUserHeading: true
	});

	map.addControl(userLocControl, "bottom-right");

	var markerDetails = createMarker(map, start);
	var markerElem = document.getElementsByClassName(markerDetails[0])[0];
	var pin = markerDetails[1];

	map.on("click", (e) => {
		longlat = e.lngLat;
		pin.setLngLat(longlat);
		mapFlyTo(map, pin.getLngLat());
	});

	map.on("drag", (e) => {
		longlat = map.getCenter();
		pin.setLngLat(longlat);
	});

	map.on("dragend", (e) => {
		longlat = map.getCenter();
	});

	pin.on("dragend", (e) => {
		longlat = pin.getLngLat();
		mapFlyTo(map, longlat);
		reqLocation.innerText = reverseGeocode();
		showModal();
	});
}

function mapFlyTo(map, loc)
{
	let zoom = map.getZoom();
	map.flyTo({
		center: loc,
		zoom: zoom,
		speed: 0.5,
		curve: 1
	});
}

function animatePinDrop(pin)
{

}

function reverseGeocode()
{
	let geocodeUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${longlat.lng},${longlat.lat}.json?access_token=${MAPBOX_TOKEN}`;
	let xhr = new XMLHttpRequest();

	var location = "";
	xhr.onreadystatechange = function() {
		if(xhr.readyState == XMLHttpRequest.DONE)
		{
			var data = JSON.parse(xhr.responseText);
			location = data.features[0]?.place_name;
		}
	}

	xhr.open("GET", geocodeUrl, false);
	xhr.send();
	return location;
}

function createMarker(map, latlong)
{
	const el = document.createElement('div');
	el.className = 'marker';
	el.style.backgroundSize = '100%';

	const marker = new mapboxgl.Marker(el, {
		draggable: true
	}).setLngLat(latlong).addTo(map);
	return [el.className, marker];
}

function onLocationAccessSuccess(pos)
{
	const userLoc = [pos.coords.longitude, pos.coords.latitude];
	longlat = userLoc;
	initMap(userLoc);
}

function onLocationAccessError(err)
{
	console.warn(`ERROR(${err.code}): ${err.message}`);

	const ktmLatLong = [85.3240, 27.7172];
	initMap(ktmLatLong);
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
		console.log("Geolocation is not supported by this browser.");
}

requestAndLoadMap();