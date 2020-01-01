//https://google-developers.appspot.com/chart/interactive/docs/gallery
//https://developers.google.com/chart/interactive/docs/points

google.load("visualization", "1", {packages:["corechart"]});
//google.setOnLoadCallback(drawChart);

//var path = "wp-content/themes/spacious-child/";

jQuery(document).ready(function ($) {

    $.ajax({

        url:  graphresults.calavgurl, //at functions.php wp_localize_script
        data: {request: ''},
        type: 'post',
        dataType: 'json',
        success: function(data) {

            google.setOnLoadCallback(drawChart(data));



        },

        error: function(err)
        {
            console.log(err.responseText);
        }



    });

});




function drawChart(arrdata) {


    var data1 = google.visualization.arrayToDataTable([

        ['Points', 'Kmeans++', 'Convexhull', 'Random', 'Bruteforce'],
        arrdata[0][0],
        arrdata[0][1],
        arrdata[0][2],
        arrdata[0][3]

    ]);

    var options1 = {
        title: 'Time Performance',
        curveType: 'function',
        pointSize: 6,
        hAxis: {title: 'Points'},
        vAxis: {title: 'Time (in secs)'}
    };

    var data2 = google.visualization.arrayToDataTable([

        ['Points', 'Kmeans++', 'Convexhull', 'Random', 'Bruteforce'],
        arrdata[1][0],
        arrdata[1][1],
        arrdata[1][2],
        arrdata[1][3]

    ]);


    var options2 = {
        title: 'Equality Performance',
        curveType: 'function',
        pointSize: 6,
        hAxis: {title: 'Points'},
        vAxis: {title: 'Equality (lower value is better)'}
    };

    var data3 = google.visualization.arrayToDataTable([

        ['Points', 'Kmeans++', 'Convexhull', 'Random', 'Bruteforce'],
        arrdata[2][0],
        arrdata[2][1],
        arrdata[2][2],
        arrdata[2][3]

    ]);


    var options3 = {
        title: 'Closeness Performance',
        curveType: 'function',
        pointSize: 6,
        hAxis: {title: 'Points'},
        vAxis: {title: 'Closeness (lower value is better)'}
    };



    var chart1 = new google.visualization.LineChart(document.getElementById('chart_div1'));
    /*google.visualization.events.addListener(chart1, 'ready', function () {
     dlchart1.innerHTML = '<img src="' + chart1.getImageURI() + '">';
     });*/
    chart1.draw(data1, options1);

    var chart2 = new google.visualization.LineChart(document.getElementById('chart_div2'));
    /*google.visualization.events.addListener(chart2, 'ready', function () {
     dlchart2.innerHTML = '<img src="' + chart2.getImageURI() + '">';
     });*/
    chart2.draw(data2, options2);

    var chart3 = new google.visualization.LineChart(document.getElementById('chart_div3'));
    /*google.visualization.events.addListener(chart3, 'ready', function () {
     dlchart3.innerHTML = '<img src="' + chart3.getImageURI() + '">';
     });*/
    chart3.draw(data3, options3);


}