jQuery(document).ready(function () {

    console.log('main');
    $('#goldChartMain').goldChart();

});

var map;

function initMap() {
	
	//return false;

    var uluru = {lat: 50.34984208, lng: 18.89167786};
    map = new google.maps.Map(document.getElementById('mainMap'), {zoom: 10, center: uluru});
    //var marker = new google.maps.Marker({position: uluru, map: map});


    initAutocomplete(map);

    google.maps.event.addListener(map, "click", function (event) {
        var latitude = event.latLng.lat();
        var longitude = event.latLng.lng();

        dataPost = {};
        dataPost.latitude = latitude;
        dataPost.longitude = longitude;




        $('#mapSpinner').show();

        $.ajax({
            type: "POST",
            url: "/mapapi/getwheather",
            data: dataPost,
            success: function(data) {

                if (data.status == 'success') {
                    $('#mapResults .modal-body').html(data.view);
                    $('#mapResults').modal();
                } else {
                    $('#mapResults .modal-body').html('<h1>ERROR</h1>'); // TODO
                    $('#mapResults').modal();
                }
                $('#mapSpinner').hide();
            },
            error: function() {
                console.log('error handling here'); // TODO
                $('#mapResults .modal-body').html('<h1>ERROR (2)</h1>'); // TODO
                $('#mapResults').modal();
                $('#mapSpinner').hide();
            }
        });
    });
}



function initAutocomplete(map) {

    console.log('initAutocomplete');

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });





}




