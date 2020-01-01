//jQuery.noConflict(); //http://learn.jquery.com/using-jquery-core/avoid-conflicts-other-libraries/

//window.initialz();

//id use #, class use .
jQuery(document).ready(function ($) {

    $('#execbtn').click(function(e){

	   e.preventDefault();
	   e.stopPropagation();
	   $("#testing").html('Processing...');
	   initialz($);//either use jQuery or $

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
     icon: algodemo.iconsurl+"small_"+data[i].color+".png"
     });

     google.maps.event.addListener(marker, 'click', (function(marker, i) {
     return function() {
     infowindow.setContent(data[i].address);
     infowindow.open(map, marker);
     }
     })(marker, i));

     }





    /**old code **/

    //http://stackoverflow.com/questions/8073673/how-can-i-add-new-array-elements-at-the-top-of-an-array-in-javascript
    /* var temp = -1.7976931348623157E+10308;//negative infinity
     var iconcolors = ["red","yellow","green","blue","brown","purple","grey","white","cyan","pink"];
     var color, marker;
     //var i=0;

     for(var key in data) {
     var obj = data[key];

     for(var prop in obj) {
     // important check that this is objects own property
     // not from prototype prop inherited

     color = iconcolors.shift();
     iconcolors.push(color);

     //if(prop != temp)
     //{
     //color = iconcolors.shift();
     //iconcolors.push(color);
     //}

     //temp = prop;


     if(obj.hasOwnProperty(prop)){
     //alert(prop + " = " + obj[prop]);

     //change keys
     //for(var a in obj[prop])
     //{
     //if(i != a)
     //{
     // obj[prop][i] = obj[prop][a];
     //delete obj[prop][a];
     // }

     // i++;
     // }

     for(var o in obj[prop])
     {
     //obj[prop][o].color = color;
     marker = new google.maps.Marker({
     position: new google.maps.LatLng(obj[prop][o].lat, obj[prop][o].lng),
     map: map,
     icon: "icons/small_"+color+".png"
     });


     }




     }


     }

     }*/



}



function initialz($)
{

    $.ajax({
        //url: algodemo.readfileurl,
        url: algodemo.ajaxurl,
        data: {action: 'readfile', request: ''},
        type: 'post',
        dataType: 'json',
        success: function(data) {

            console.log("successful");
            /*console.log(data);

             var temp = -1.7976931348623157E+10308;//negative infinity
             var icons = ["red","yellow","green","blue","brown","purple","grey","white","cyan","pink"];
             var color;


             for(var key in data) {
             var obj = data[key];
             for(var prop in obj) {
             // important check that this is objects own property
             // not from prototype prop inherited

             color = icons.shift();
             icons.push(color);

             //if(prop != temp)
             //{
             //color = icons.shift();
             //icons.push(color);
             //}

             //temp = prop;

             if(obj.hasOwnProperty(prop)){
             //alert(prop + " = " + obj[prop]);

             for(var o in obj[prop])
             {
             obj[prop][o].color = color;
             //console.log(obj[prop][o].lat);
             }

             }


             }

             console.log(obj);


             }*/



            drawMap(data[0]);
            //drawMap(data[1]);//for old code

            //var s = JSON.stringify(data, null, 4);
            //var s = JSON.stringify(data[1], null, '\t');//to beautify the json
            var s = data[2];

            //http://stackoverflow.com/questions/508269/how-do-i-break-a-string-across-more-than-one-line-of-code-in-javascript
            //$("#testing").html(data[2]+" Ranking: <p/>"+s);//.append, .html, or .text
            $("#testing").html(s);

        },

        error: function(err)
        {
            console.log(err.responseText);
        }
    });






    /*$.ajax({
     url: '/fyp/readfile.php',
     data: {request: 'request data'},
     type: 'post',
     dataType: 'json',//return type
     success: function(data) {

     //console.log(data);
     //processing(data);


     },

     error: function(err)
     {
     console.log(err.responseText);
     }
     });*/


}



























function processing(data)
{


    //console.log("success");

    //var count = Object.keys(data).length
    //console.log(count);

    /*var count = data['count'][0];
     delete data['count'];
     console.log(data);
     console.log(count);*/

    var count = data.count[0];
    delete data.count;
    //console.log(data);
    //console.log(count);



    translateGeo(data, function(addr){

        /*var res = addr;
         console.log(res);
         console.log(res.length);

         res.sort(function(a, b){
         return a.postalcode - b.postalcode;
         });

         address.sort(function(a, b){
         return a.p - b.p;
         });


         for(var i = 0; i < res.length; i++)
         {
         if(res[i].postalcode == address[i].p)
         {
         res[i] = {postalcode:res[i].postalcode, latlng:res[i].latlng, name:address[i].name, lat:res[i].lat, lng:res[i].lng};
         }
         }

         console.time('kmean timer');

         kmean(res, 5);

         console.timeEnd('kmean timer');*/


    });







}



function translateGeo(address, mycallback)
{

    /*var geocoder = new google.maps.Geocoder();
     var postal;

     for (var key in address) {
     if (address.hasOwnProperty(key)) {
     for(var k in address[key])
     {
     geocoder.geocode( { address: address[key][k].postalcode, componentRestrictions: { country: 'SG'}}, function(results, status) {

     if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {

     sleep(2);

     }
     else if (status == google.maps.GeocoderStatus.OK) {
     ++count;
     postal = results[0].address_components[0].long_name;
     address[key][k].postalcode = postal;
     address[key][k].latlng = results[0].geometry.location;
     address[key][k].lat = results[0].geometry.location.lat();
     address[key][k].lng = results[0].geometry.location.lng();


     } else {
     alert('Geocode was not successful for the following reason: ' + status);

     }

     });
     }
     }
     }*/















}









//self-sleep timer function
function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
        }
    }
}










//for TESTING Only!
/*	var jsonData = {
 "results": {
 "RESULT1-Node1": {
 "Network.MS": "405",
 "Down_time": "131"

 },
 "RESULT4-Node2": {
 "Network.MS": "451",
 "Down_time": "141"                         }
 }
 };


 for (var resultBank in jsonData.results) {
 var rootType = resultBank ;
 console.log(rootType );
 for(var result in jsonData.results[resultBank]) {

 console.log(jsonData.results[resultBank][result]);

 }
 }*/


/*//get object size
 Object.size = function(obj) {
 var size = 0, key;
 for (key in obj) {
 if (obj.hasOwnProperty(key)) size++;
 }
 return size;
 };

 console.log(Object.size(data));*/

