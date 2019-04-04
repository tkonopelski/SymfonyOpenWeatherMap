
var spinnerHtml = '<div class="spinner-border text-success text-center" role="status"><span class="sr-only">Loading...</span></div>';


jQuery(document).ready(function () {

    console.log('main');

    jQuery('body').on('click', '[data-toggle="modal-url"]', function(){

        var remonte = jQuery(this).data("remote");
        var target = jQuery(this).data("target") + ' .modal-body';

        jQuery(target).html(spinnerHtml);

        //console.log( remonte );

        jQuery(target).load(remonte);

        jQuery('#invoiceItemsEditModal').modal();
    });

    jQuery('body').on('click', '#invoice_item_form_submit', function(){

        var id = jQuery(this).data('id');

        console.log('sss ' + id);

        invoiceItemSave(id);

    });

    }
);

function initMap() {

    //var uluru = {lat: -25.344, lng: 131.036};
    var uluru = {lat: 50.34984208, lng: 18.89167786};

    var map = new google.maps.Map(document.getElementById('mainMap'), {zoom: 10, center: uluru});

    var marker = new google.maps.Marker({position: uluru, map: map});

    google.maps.event.addListener(map, "click", function (event) {
        var latitude = event.latLng.lat();
        var longitude = event.latLng.lng();

        console.log( latitude + ', ' + longitude );

        dataPost = {};
        dataPost.latitude = latitude;
        dataPost.longitude = longitude;

        $('#mapSpinner').show();

        $.ajax({
            type: "POST",
            url: "/mapapi/getwheather",
            data: dataPost,
            success: function(data) {

                //console.log(data);
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
                console.log('error handling here');
            }
        });

    });
}