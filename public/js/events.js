/* OnLoad of the page */
$(document).ready(function () {
    if (Cookies.get("search") == 0 || $('#search').length == 0) {
        getLocation();
        console.log('Start the geolocalization task!');
    }
});

/* ========  GEO LOCATION TASK ======== */

function getLocation() {
    if (navigator.geolocation) {
        // timeout at 5000 milliseconds (5 seconds)
        var options = {maximumAge: 60000, timeout: 60000, enableHighAccuracy: true};
        navigator.geolocation.getCurrentPosition(showLocation,
            errorHandler,
            options);
    } else {
        alert("Sorry, browser does not support geo location option!");
    }
}

function showLocation(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    $.post("/events/index/savecoords", {
        lat: latitude,
        lng: longitude
    }).success(function (data) {
        if (data) {
            getEvents();
        } else {
            console.log('No event has been found');
        }
    });
}


function getEvents() {
    $.ajax("/events/index/getevents", {
        type: "POST",
        beforeSend: function () {
            $('#eventsclosetome').html("Please wait ... ");
        },
        error: function () {
            $('#eventsclosetome').html('Sorry, no close events found.');
        },
        success: function (data) {
            $('#eventsclosetome').html(data);
            return true;
        }
    });
}

//error.code can be:
// 0: unknown error
// 1: permission denied
// 2: position unavailable (error response from location provider)
// 3: timed out
function errorHandler(err) {
    if (err.code == 1) {
        $('#eventsclosetome').html("Error: Access to the localisation is denied by your browser!");
    } else if (err.code == 2) {
        $('#eventsclosetome').html("Error: Position is unavailable! Please type the name of your city or the event name in the search box.");
    } else {
        $('#eventsclosetome').html("Error Code: " + err.code);
    }
}

/* ========  SEARCH FORM TASK ======== */

$(".findnearme").click(function () {

    getLocation();

    Cookies.set("content", "");
    Cookies.set("country_id", "");
    Cookies.set("category_id", "");
    Cookies.set("city", "");
    Cookies.set("search", 0);

    $('#eventsclosetome').html("Please wait ... ");

    $("#search input[name=content]").val("");
    $('#search select[name=country_id] option:eq(0)').prop('selected', true);
    $('#search select[name=category_id] option:eq(0)').prop('selected', true);
    $("#search input[name=city]").val("");
});

$(function () {
    if (Cookies.get("toggle-state")) {
        $(".searchfrm").toggle((!!!Cookies.get("toggle-state")) || Cookies.get("toggle-state") === 'true');
    }else{
        $(".searchfrm").toggle(false);
    }

    $("#search input[name=content]").val(Cookies.get("content"));
    $("#search select[name=country_id]").val(Cookies.get("country_id"));
    $("#search select[name=category_id]").val(Cookies.get("category_id"));
    $("#search input[name=city]").val(Cookies.get("city"));

    $('.searchbtn').on('click', function () {
        $(".searchfrm").toggle();
        Cookies.set("toggle-state", $(".searchfrm").is(':visible'));
    });


    if (Cookies.get("search")) {
        onFormRefresh();
    }

});

function onFormRefresh(that) {
    if (Cookies.get("content") || Cookies.get("country_id") || Cookies.get("category_id") || Cookies.get("city")) {
        if($('#search').length > 0){
            onSearch($('#search'));
            console.log('Start search request...');
        }
    }
}

function onSearch(that) {
    var formdata = JSON.stringify($('#search').serializeArray());

    $.ajax('/events/search', {
        type: "POST",
        data: {
            'query': formdata
        },
        beforeSend: function () {
            $('#eventsclosetome').html("Please wait ... ");

            if ($("input[name=content]").val() || $("select[name=country_id] option:selected").val() || $("select[name=category_id] option:selected").val() || $("input[name=city]").val()) {
                Cookies.set("content", $("input[name=content]").val());
                Cookies.set("country_id", $("select[name=country_id] option:selected").val());
                Cookies.set("category_id", $("select[name=category_id] option:selected").val());
                Cookies.set("city", $("input[name=city]").val());
                Cookies.set("search", 1);
                console.log('Search data saved...');
            } else {
                console.log('Reset search request ...');
                Cookies.set("search", 0);
                getLocation();
            }
        },
        error: function () {
            $('#eventsclosetome').html('Sorry, no close events found.');
            console.log('There was an error while loading the search data result!');
        },
        success: function (data) {
            $('#eventsclosetome').html(data);
            console.log(formdata);
            console.log('Search data displayed!');
            return true;
        }
    });
}

/* ========  VARIOUS ======== */


$(".snippetcode").click(function () {
    var content = CKEDITOR.instances['content'].getData();
    content += $(this).val();
    CKEDITOR.instances['content'].setData(content);
});

$("#help").click(function () {
    $("#helpdescription").slideToggle("slow", function () {
        $("#help").toggleClass('btn-success btn-warning');
    });
});

/* START UPLOAD System */
$("#file").fileinput({
    'showUpload': false,
    'previewFileType': 'any'
});

/* START Date Time Picker tool configuration */
$('.datetime').datetimepicker({
    format: 'd/m/Y H:i',
    minDate: new Date(),
});
