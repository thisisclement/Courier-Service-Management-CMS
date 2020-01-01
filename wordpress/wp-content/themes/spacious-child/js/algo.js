//jQuery.noConflict(); //http://learn.jquery.com/using-jquery-core/avoid-conflicts-other-libraries/

//window.initialz();

//id use #, class use .
jQuery(document).ready(function ($) {
$('#execbtn').click(function(e){
       e.preventDefault();
       e.stopPropagation();
       $("#testing").html('Processing...');
       initialz($);

   });
   
   
});



function drawMap(data)
{
    var map = new google.maps.Map(document.getElementById('googft-mapCanvas'), {
        zoom: 11,
        center: new google.maps.LatLng(1.344925307949575, 103.83301507568353),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

      var infowindow = new google.maps.InfoWindow();

     var marker, i;

     for(i=0; i < data.length; i++)
     {
     marker = new google.maps.Marker({
     position: new google.maps.LatLng(data[i].lat, data[i].lng),
     map: map,
     icon: algo.iconsurl+"small_"+data[i].color+".png"
     });

     google.maps.event.addListener(marker, 'click', (function(marker, i) {
     return function() {
     infowindow.setContent(data[i].address);
     infowindow.open(map, marker);
     }
     })(marker, i));

     }




}



function initialz($)
{

    $.ajax({
        url: algo.readfileurl,
        data: {request: ''},
        type: 'post',
        dataType: 'json',
        success: function(data) {

            console.log("successful");



            drawMap(data[0]);
            //drawMap(data[1]);//for old code

            //var s = JSON.stringify(data, null, 4);
            var s = JSON.stringify(data[1], null, '\t');//to beautify the json

            //http://stackoverflow.com/questions/508269/how-do-i-break-a-string-across-more-than-one-line-of-code-in-javascript
            $("#testing").html(data[2]+" Ranking: <p/>"+s);//.append, .html, or .text

        },

        error: function(err)
        {
            console.log(err.responseText);
        }
    });




}
