function getUserAddressByLatLon(ll)
{
    var request = new XMLHttpRequest();
    var address = "";
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var data = JSON.parse(request.responseText);
            let add = data.address;
            address = `${add.suburb}, ${add.road}, ${add.municipality}, ${add.city}, ${add.country}`;
        }
    };

    var url = `https://us1.locationiq.com/v1/reverse.php?key=pk.f0f26e342feac68b21745fb60341fda4&lat=${ll.latitude}&lon=${ll.longitude}&format=json`;
    request.open("GET", url, false);
    request.send();
    return address;
}