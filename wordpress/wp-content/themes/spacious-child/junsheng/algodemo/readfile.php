<?php

//http://stackoverflow.com/questions/17851868/browser-shows-time-out-while-server-process-is-still-running
//http://www.php.net/manual/en/function.flush.php

//ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit', '-1');
//error_reporting(E_ALL);
ob_implicit_flush(TRUE);
ob_end_flush();

require_once 'nearestneighbor.php';
require_once 'kmeans.php'; //require other php file
require_once 'dijkstra.php';
//require_once 'heldkarp.php';

//threshold may use slider?
//define('THRESHOLD', 12);



/*$obj = new stdClass();
$obj->lat = 1.352083;
$obj->lng = 103.819836;


$data = generaterandpointsinacirc($obj,100);
file_put_contents("test.json", json_encode($data, JSON_PRETTY_PRINT));*/

//need thing of a way to do this when using url:algodemo.readfileurl at algodemo.js
//the path starts with the folder where readfile is being executed
/*if ( !defined('ABSPATH') ) {
    require_once "../../../../../wp-load.php";
    //require_once "../../../../../wp-admin/includes/file.php";//for request_filesystem_credentials
}*/

//if it is url:algodemo.ajaxurl at algodemo.js, the path starts from wp-admin directory


//if(!function_exists('request_filesystem_credentials'))
    //require_once "/wp-admin/includes/file.php";

//testing wordpress write to file style
/*$url = wp_nonce_url('','');
if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
    return; // stop processing here
}

if ( ! WP_Filesystem($creds) ) {
    request_filesystem_credentials($url, '', true, false, null);
    return;
}

global $wp_filesystem;
$wp_filesystem->put_contents(
  'example.txt',
  'Example contents of a file',
  FS_CHMOD_FILE // predefined mode settings for WP files
);*/


$folderpath = get_stylesheet_directory_uri()."/junsheng/algodemo";


$data = jsonfilereader($folderpath."/test.json");


if(empty($data))
{
    echo 'no tasks needed to be scheduled';
    exit();
}





$couriers = jsonfilereader($folderpath."/couriers.json");

//$couriersavail = $couriers->couriers;//can remove after testing



$sickcouriers = array();

$courierscopy = array();

foreach($couriers->couriers as $crk=>$crv)
{
    if($crv->{"sick status"} == "1")
        $sickcouriers[] = $crv; //equivalent to array_push
        //array_push($sickcouriers,$crv);
    else
        $courierscopy[] = $crv;
        //array_push($courierscopy,$crv);

}

$couriersavail = $courierscopy;


$sickinalloc = array();


//have to restructure this after everything is done
$folderdir = get_stylesheet_directory()."/junsheng/algodemo";

//if(file_exists("allocated.json"))
if(file_exists($folderdir."/allocated.json"))
{

    //if((time()-filemtime("allocated.json")) <= 86400)
    if((time()-filemtime($folderdir."/allocated.json")) <= 86400)//still exits within 30mins, for now 24h in ms
    {

        //$passdata = jsonfilereader($folderpath."/allocated.json", true);
        $passdata = jsonfilereader($folderpath."/allocated.json");

        foreach($passdata[1] as $ak=>$av)
        {
            //if(!isset($coursalloc))
                //$coursalloc = count($passdata[$ak]);


            foreach($av as $aak=>$aav)
            {

                $ex = explode(" ", $aak);

                foreach($sickcouriers as $sck=>$scv)
                {

                    if($ex[0] == $scv->cid)
                    {
                        if($scv->{"sick status"} == "1")
                            //array_push($sickinalloc,$scv);
                            $sickinalloc[] = $crv;

                    }


                    foreach($courierscopy as $cck=>$ccv)
                    {

                        if($ex[0] == $ccv->cid)
                            unset($courierscopy[$cck]);

                    }



                }
            }
        }




        if(!empty($sickinalloc))
        {

            //do random swapping for sick couriers

            if(count($sickinalloc) <= count($courierscopy))
            {
                shuffle($courierscopy);

                foreach($passdata[1] as $ak=>$av)
                {

                    foreach($av as $aak=>$aav)
                    {

                        $ex = explode(" ", $aak);

                        foreach($sickinalloc as $sck=>$scv)
                        {

                            if($ex[0] == $scv->cid)
                            {
                                if($scv->{"sick status"} == "1")
                                {

                                    $ashift = array_shift($courierscopy);

                                    $av->{$ashift->cid." ". $ashift->name} = $aav;

                                    unset($av->$aak);


                                }

                            }



                        }
                    }
                }


                goto processed;

            }
            //else
            //{

                //continue below

            //}


        }
        else
            goto processed;


    }
    else
        unlink($folderdir."/allocated.json");
        //unlink("allocated.json");

}

//data less than 100, threshold how?

if(count($data) <= 100)
{

    $countwccouriers = count($couriers->couriers) - 4;

    //100 - 4
    $countwcdata = 96;
    $threshold = round($countwcdata/$countwccouriers);
}
else
{

$countwcdata = count($data) - 4;

$countwccouriers = count($couriers->couriers) - 4;

$threshold = round($countwcdata/$countwccouriers);

}




//predict in advance
//recalculate if there is enough couriers than expected (given threshold of max each couriers can handle),
//if not enough, unified them

$couriersavialable = count($couriersavail);

$doublethres = $threshold*2; //can be any threshold (expected)

$mincouriers = round($countwcdata/$doublethres);

$mincouriers+=4;



if(!empty($sickcouriers))
{
    //do threshold altering
    $check = round($countwcdata/$threshold);
    $tmpthresh = $threshold;
    $s = true;

    $c = count($sickcouriers);

    while($s)
    {
        $tmpthresh+=1;

        $tmpcheck = round($countwcdata/$tmpthresh);


        if($tmpcheck < $check)
        {
            $check = $tmpcheck;
            --$c;
        }

        if($c == 0)
            $s = false;

    }

    $threshold = $tmpthresh;


}


$zones = array();

$clusters = array();

$ranking = array();

$booluni = false;


//if zero couriers, prompt error
if($couriersavialable < 1)
{
    //display error then exit
    echo "Error: No couriers available to handle jobs!";
    exit(); //die();

}

if($couriersavialable <= $mincouriers)//do something
{
    //forfeit regions, unified the cluster (need to copy to the original readfile, testing purpose do not have this format zones)

    
    $booluni = true;
    $zones["unified"] = array();
    

    /*//$unified = array();
    //need check time first (need specify range)


    $unified = array_merge($unified, $data->date->$today);
    $zones["unified"] = $unified;
    unset($unified);*/

}


//by default this is already done
//cluster into regions


$clusters = array();

$ranking = array();

if(!array_key_exists("Test", $clusters))
    $clusters["Test"] = array();



















/*echo "<pre>";
echo "Count: ";
print_r(count($data));
echo "<br/>";
echo "</pre>";

$starttime = microtime(true);*/

shuffle($couriersavail);

$clusters["Test"] = modifiedKmeans($data, $threshold, $couriersavail);


/*$endtime = microtime(true);
$executiontime = $endtime - $starttime;


echo "<pre>";
echo "Execution time: ";
print_r($executiontime);
echo "</pre>";*/





//for k-means testing

//http://stackoverflow.com/questions/5020202/splitting-up-a-string-in-php-with-every-blank-space

/*$fn = "kmeans++.txt";
$countdata = count($data);
$testrun = 1;

$strline = "";

for($i=0; $i < $testrun; $i++)
{

    //timing
    $starttime = microtime(true);

    shuffle($couriersavail);

    $clusters["Test"] = modifiedKmeans($data, $threshold, $couriersavail);

    $endtime = microtime(true);
    $executiontime = $endtime - $starttime;





    //equality
    $equality = 0;

    foreach($clusters["Test"] as $ck=>$cv)
    {

        //equality among clusters
        foreach($clusters["Test"] as $cck=>$ccv)
        {

            if($ck == $cck)
                continue;


            $equality+=count($cv)/count($ccv);

        }


    }

    $equality/=count($clusters["Test"]); //average equality per clusters


    //spread quality
    $sqerr = 0;

    foreach($clusters["Test"] as $ck=>$cv)
    {

        foreach($cv as $ckk=>$ccv)
            $sqerr+=$ccv->distance;

    }


    $sqerr/=count($clusters["Test"]);
    //$nfsqerr = number_format($sqerr/count($clusters["Test"]), 6, '.', '');//average sq errors








    $strline.=$countdata." $executiontime $equality $sqerr".PHP_EOL;

    $couriersavail = $couriers->couriers;


}

file_put_contents($fn, $strline, FILE_APPEND);*/









//after testing remember to unset distance in kmeans.php (around line 619)

//For Equality Scoring

/*$equality = 0;


foreach($clusters["Test"] as $ck=>$cv)
{

    //equality among clusters
    //first way
    foreach($clusters["Test"] as $cck=>$ccv)
    {

        if($ck == $cck)
            continue;


        $equality+=count($cv)/count($ccv);
        //$equality+=count($ccv)/count($cv);

    }


    //equality towards threshold
    //second way
    //$equality+=count($cv)/$threshold;


    //third way
    //$equality+=abs(count($cv)-$threshold);



}

echo $equality; //total equality
echo "<br/>";
echo $equality/count($clusters["Test"]); //average equality per clusters

return;*/





//another different way (using variance for testing equality among clusters)

/*$sqeqarr = array();

foreach($clusters["Test"] as $ck=>$cv)
{
    $equality = 0;


    if(!array_key_exists($ck, $sqeqarr))
        $sqeqarr[$ck] = array();

    foreach($cv as $cck=>$ccv)
    {
        $equality+=$ccv->distance;

    }


    $sqeqarr[$ck] = $equality;


}

echo "<pre>";
print_r($sqeqarr); //total equality
echo "</pre>";

$equality = 0;

foreach($sqeqarr as $ck=>$cv)
{
    foreach($sqeqarr as $cck=>$ccv)
    {

        if($ck == $cck || $ccv == 0)
            continue;

        $equality+=$cv/$ccv;
        //$equality+=$ccv/$cv;

    }

}


echo $equality; //total equality
echo "<br/>";
echo $equality/count($clusters["Test"]); //average equality per clusters
return;*/








//Sum of Square Errors (Quality/Spread(Variance)/Closeness/Similarity)

/*$sqerr = 0;

foreach($clusters["Test"] as $ck=>$cv)
{


    foreach($cv as $ckk=>$ccv)
        $sqerr+=$ccv->distance;


}


$nfsqerr = number_format($sqerr, 6, '.', '');

echo $nfsqerr;
echo "<br/>";
$nfsqerr = number_format($sqerr/count($clusters["Test"]), 6, '.', '');
echo $nfsqerr;//average sq errors

return;*/














//couriers less than 2 jobs ranking how?




//Nearest Neighbor

/*$rankalgo = "Nearest Neighbor";

foreach($clusters as $regionskey=>$regionsval)
{
    if(!array_key_exists($regionskey, $ranking))
            $ranking[$regionskey] = array();

        $ranking[$regionskey] = nearestneighbor($regionsval);


    //nearestneighbor($regionsval);

}


unset($clusters);*/


/*echo "<pre>";
echo "Count: ";
print_r(count($data));
echo "<br/>";
echo "</pre>";

$starttime = microtime(true);*/

//Dijkstra
$rankalgo = "Dijkstra";

foreach($clusters as $regionskey=>$regionsval)
{


    if(!array_key_exists($regionskey, $ranking))
        $ranking[$regionskey] = array();

    $ranking[$regionskey] = dijkstra($regionsval);


}

/*$endtime = microtime(true);

$executiontime = $endtime - $starttime;

echo "<pre>";
echo "Execution time: ";
print_r($executiontime);
echo "</pre>";*/

unset($clusters);









//Held-Karp

/*$rankalgo = "Held-Karp";

foreach($clusters as $regionskey=>$regionsval)
{


    if(!array_key_exists($regionskey, $ranking))
        $ranking[$regionskey] = array();

    $ranking[$regionskey] = heldkarp($regionsval);


}


unset($clusters);*/








//For client google maps (convert to flat array of objects)
//$ranking is the original
$newarr = array();

$iconcolors = array("red","yellow","green","blue","brown","purple","grey","white","cyan","pink","orange","lime","sky");

foreach($ranking as $key=>$val)
{
    //$key is north etc...
    foreach($val as $kkk => $vvv)
    {
        $color = array_shift($iconcolors);
        array_push($iconcolors, $color);

        foreach($vvv as $vv)
        {

            $clonevv = clone $vv;
            $clonevv->color = $color;
            $vv->color = $color;
            $clonevv->region = $key;
            $vv->region = $key;
            array_push($newarr, $clonevv);
            unset($clonevv);

        }

    }

}


$htmltable = '<h3 style="text-align:center;">'.$rankalgo.' Ranking of Jobs Scheduled</h3>
<table>
<tr>
  <th style="text-align:center;" width="25%">Courier Name(s)</th>
  <th colspan="2" style="text-align:center;" width="75%">Ranked Job(s)</th>
</tr>';

foreach ($ranking as $dk => $dv) 
{

    foreach ($dv as $ddk => $ddv) 
    {


        $i = 1;

        $rowspan = count($dv[$ddk]);//need to count the jobs

        foreach ($ddv as $dddk => $dddv) 
        {
            $htmltable.='<tr>';

            $jobsdes = 'Order no.: '.$dddv->tid.'<br />Postal Code: '.$dddv->postalcode.'<br />Address: '.$dddv->address.'<br />Color code: '.$dddv->color.'<br />Region: '.$dddv->region;

            if($i == 1)
            {
                $name = $ddk;

                $htmltable.= '<td rowspan="'.$rowspan.'" style="text-align:center;">'.$name.'</td>
                <td style="text-align:center;" width="10%">'.$i.'</td><td width="65%">'.$jobsdes.'</td>';

            }
            else
            {
                $htmltable.= '<td style="text-align:center;">'.$i.'</td><td>'.$jobsdes.'</td>';
            }

            $htmltable.='</tr>';


            $i++;
        }

    }

}

$htmltable.='</table>';



$passdata = array();

array_push($passdata, $newarr, $ranking, $htmltable);
//array_push($passdata, $newarr, $ranking, $rankalgo);

//file_put_contents("allocated.json",json_encode($passdata, JSON_PRETTY_PRINT));
file_put_contents($folderdir."/allocated.json",json_encode($passdata, JSON_PRETTY_PRINT));

$htmltable = '<style>
table
{
border-collapse:collapse;
border:1px solid black;
}
th, td
{
border:1px solid black;
}
</style>'.$htmltable;

require_once $folderdir."/tcpdf/tcpdf.php";

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('JS');
//$pdf->SetTitle('TCPDF Example 001');
//$pdf->SetSubject('TCPDF Tutorial');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

if (@file_exists($folderdir.'/tcpdf/lang/eng.php')) {
    require_once($folderdir.'/tcpdf/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
//$pdf->SetFont('dejavusans', '', 14, '', true);
$pdf->SetFont('dejavusans', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $htmltable, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($folderdir.'/results.pdf', 'F');
//$pdf->Output($folderdir.'/results.pdf', 'F');//save to file



processed:

if(isset($_POST['request'])) {

    echo json_encode($passdata);
}



function generaterandpointsinacirc($mean, $numofpoints)
{

    $points = array();
    $br = false;

    for($i=1; $i <= $numofpoints; $i++)
    {

        while(!$br)
        {
            $br = true;
            $angle = mt_rand(0,6283185)/1000000; //0 to 2*pi()
            $radius = mt_rand(1,100000)/1000000;

            $lng = $radius*cos($angle)+$mean->lng;//x
            $lat = $radius*sin($angle)+$mean->lat;//y


            $maxlng = 0.1+$mean->lng;
            $maxlat = 0.1+$mean->lat;

            $minlng = $mean->lng-0.1;
            $minlat = $mean->lat-0.1;

            //if( ($lng < 103.69331 || $lng > 103.970399) || ($lat < 1.266393 || $lat > 1.453986)  )
                //$br = false;

            if( ($lng < $minlng || $lng > $maxlng) || ($lat < $minlat || $lat > $maxlat)  )
                $br = false;



        }


        $p = new stdClass();
        $latlng = new stdClass();
        $p->tid = "T".$i;
        $p->postalcode = "P".$i;
        $p->address = "A".$i;
        $latlng->lat = number_format($lat, 6, '.', '');
        $latlng->lng = number_format($lng, 6, '.', '');
        $p->latlng = $latlng;
        $p->lat = number_format($lat, 6, '.', '');
        $p->lng = number_format($lng, 6, '.', '');
        array_push($points,$p);
        $br = false;

    }

    return $points;

}























?>


