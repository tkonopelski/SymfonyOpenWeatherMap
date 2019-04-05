jQuery(document).ready(function () {

    console.log('main');

    }
);

function initMap() {

    var uluru = {lat: 50.34984208, lng: 18.89167786};
    var map = new google.maps.Map(document.getElementById('mainMap'), {zoom: 10, center: uluru});
    //var marker = new google.maps.Marker({position: uluru, map: map});

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