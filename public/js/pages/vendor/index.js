let gmap, infoWindow, marker, pos, input, inputGeolocation;

function initMap() {
    pos = { lat: -6.2088, lng: 106.8456 }
    gmap = new google.maps.Map(document.getElementById("geolocation"), {
        center: pos,
        zoom: 13,
        streetViewControl: false,
        mapTypeControl: false,
        mapTypeId: "roadmap",
    });
    marker = new google.maps.Marker({
        position: pos,
        map: gmap,
        draggable: true
    });

    input = document.getElementById("pac-input");
    inputGeolocation = document.getElementById("input-geolocation");
    const autocomplete = new google.maps.places.Autocomplete(input);
    const geocoder = new google.maps.Geocoder();
    autocomplete.bindTo("bounds", gmap);
    autocomplete.setFields(["place_id", "geometry"]);
    gmap.controls[google.maps.ControlPosition.TOP_LEFT].push(input)

    const locationButton = document.createElement("div");
    locationButton.textContent = "Lokasi Saat ini";
    locationButton.classList.add("custom-map-control-button");
    gmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(locationButton);

    infoWindow = new google.maps.InfoWindow();
    try{
        if (inputGeolocation.value && inputGeolocation.value.trim().length) {
            pos = {
                lat: parseFloat(inputGeolocation.value.split(',')[0]),
                lng: parseFloat(inputGeolocation.value.split(',')[1]),
            };
            infoWindow.setContent(pos.lat+","+pos.lng);
            document.getElementById("info-geolocation").innerText = "Latitude: "+pos.lat+", Longitude: "+pos.lng
            var currectPosition = new google.maps.LatLng(pos.lat, pos.lng);
            marker.setPosition(currectPosition);
            infoWindow.open(gmap,marker);
        }
    }catch (e) {
        console.log("Edit vendor init map error!")
    }
    infoWindow.setPosition(pos);
    gmap.setCenter(pos);

    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();

        if (!place.place_id) {
            inputGeolocation.value=''
            return;
        }
        geocoder.geocode({ placeId: place.place_id }, (results, status) => {
            if (status !== "OK" && results) {
                window.alert("Geocoder failed due to: " + status);
                inputGeolocation.value=''
                return;
            }

            marker.setPosition(results[0].geometry.location);
            pos = {
                lat: marker.position.lat(),
                lng: marker.position.lng(),
            };
            inputGeolocation.value=pos.lat+','+pos.lng
            infoWindow.setPosition(pos);
            infoWindow.setContent(pos.lat+","+pos.lng);
            document.getElementById("info-geolocation").innerText = "Latitude: "+pos.lat+", Longitude: "+pos.lng
            infoWindow.open(gmap,marker);
            gmap.setCenter(pos);
        });
    });

    locationButton.addEventListener("click", (e) => {
        e.stopPropagation()
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    input.value="";
                    pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    inputGeolocation.value=pos.lat+','+pos.lng
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(pos.lat+","+pos.lng);
                    document.getElementById("info-geolocation").innerText = "Latitude: "+pos.lat+", Longitude: "+pos.lng
                    var currectPosition = new google.maps.LatLng(pos.lat, pos.lng);
                    marker.setPosition(currectPosition);
                    infoWindow.open(gmap,marker);
                    gmap.setCenter(pos);
                },
                () => {
                    inputGeolocation.value=''
                    handleLocationError(true, infoWindow, gmap.getCenter());
                }
            );
        } else {
            // Browser doesn't support Geolocation
            inputGeolocation.value=''
            handleLocationError(false, infoWindow, gmap.getCenter());
        }
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        input.value="";
        pos = {
            lat: this.getPosition().lat(),
            lng: this.getPosition().lng(),
        };
        inputGeolocation.value=pos.lat+','+pos.lng
        infoWindow.setPosition(pos);
        infoWindow.setContent(pos.lat+","+pos.lng);
        document.getElementById("info-geolocation").innerText = "Latitude: "+pos.lat+", Longitude: "+pos.lng
        infoWindow.open(gmap,marker);
        gmap.setCenter(pos);
    });

    google.maps.event.addListener(gmap, 'click', function( event ){
        input.value="";
        pos = {
            lat: event.latLng.lat(),
            lng: event.latLng.lng(),
        };
        inputGeolocation.value=pos.lat+','+pos.lng
        infoWindow.setPosition(pos);
        infoWindow.setContent(pos.lat+","+pos.lng);
        document.getElementById("info-geolocation").innerText = "Latitude: "+pos.lat+", Longitude: "+pos.lng
        var currectPosition = new google.maps.LatLng(pos.lat, pos.lng);
        marker.setPosition(currectPosition);
        infoWindow.open(gmap,marker);
        gmap.setCenter(pos);
    });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation."
    );
    infoWindow.open(gmap);
}

$(document).ready(function(){
    $("#region-list").select2({
        placeholder: 'Pilih Wilayah',
        "language": {
            "noResults": function(){
                return "Wilayah tidak ditemukan...";
            }
        },
    }).on('select2:select', function (e) {
        let data = e.params.data;
        let regionId = data.id;
        $("#action-loading").show();
        $.ajax({
            url: '/region/district/' + regionId,
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: "GET",
            success: (function (data) {
                try{
                    if($.trim(data.status).toUpperCase()==="SUCCESS"){
                        setDistrict(data.data);
                    }else{
                        resetDistrict();
                        Swal.fire({
                            title: "Opps.. Error!",
                            html: data.status,
                            type: "error",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                    }
                }catch (e) {
                    resetDistrict();
                    Swal.fire({
                        title: "Opps.. Catch Error!",
                        html: response,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                $("#action-loading").hide();
            }),
            error: function (xhr, status, error) {
                resetDistrict();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseText,
                    type: "error",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-error"
                });
                $("#action-loading").hide();
            }
        });
    });
    let districtList = $("#district-list").select2({
        placeholder: 'Pilih Kecamatan',
        "language": {
            "noResults": function(){
                return "Kecamatan tidak ditemukan pada Wilayah yang Anda pilih";
            }
        },
    });

    function setDistrict(data){
        districtList.select2('destroy').empty().select2({
            placeholder: 'Pilih Kecamatan',
            "language": {
                "noResults": function(){
                    return "Kecamatan tidak ditemukan pada Wilayah yang Anda pilih";
                }
            },
            data: data
        });
    }

    function resetDistrict(){
        districtList.select2('destroy').empty().select2({
            placeholder: 'Pilih Kecamatan',
            "language": {
                "noResults": function(){
                    return "Kecamatan tidak ditemukan pada Wilayah yang Anda pilih";
                }
            },
            data: {"id":"","text":"Pilih Kecamatan"}
        });
    }
});
