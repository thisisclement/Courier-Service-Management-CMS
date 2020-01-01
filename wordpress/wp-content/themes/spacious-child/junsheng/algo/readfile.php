<?php


/*echo ABSPATH;
echo "<br/>";
echo get_stylesheet_directory();
echo "<br/>";
echo __FILE__;
echo "<br/>";
echo dirname(__FILE__);
exit();*/


date_default_timezone_set("Asia/Singapore");

$today = date("d/m/Y");
$timeclick = date("H:i:s");

//include 'kmeans.php';//include other php file
require_once 'nearestneighbor.php';
require_once 'kmeans.php'; //require other php file
require_once 'dijkstra.php';

//threshold may use slider?
//define('THRESHOLD', 12);

//need thing of a way to do this
if ( !defined('ABSPATH') ) {
    require_once "../../../../../wp-load.php";
}


$folderpath = get_stylesheet_directory_uri()."/data";


$data = jsonfilereader($folderpath."/jobs.json");


if(empty($data->date))
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
$folderdir = get_stylesheet_directory()."/data";


//for testing
goto here;


if(file_exists($folderdir."/allocated.json"))
{

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


                //goto processed;

            }
            //else
            //{

                //continue below

            //}


        }
        //else
           // goto processed;


    }
    else
        unlink($folderdir."/allocated.json");

}

here:


//data less than 100, threshold how?

if(count($data->date->$today) <= 100)
{

    $countwccouriers = count($couriers->couriers) - 4;

    //100 - 4
    $countwcdata = 96;
    $threshold = round($countwcdata/$countwccouriers);
}
else
{

$countwcdata = count($data->date->$today) - 4;

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

//3 time slots
$timeslotcutoffstart = array("10:00:00","14:00:00","17:00:00");
$timeslotcutoffend = array("12:01:00","17:01:00","19:01:00");

foreach($data->date as $dk=>$dval)
{

    if($dk == $today)
    {
        foreach($data->date->$today as $ddk=>$ddv) 
        {



            //need check time first (need specify range)
            for($t = 0; $t < count($timeslotcutoffstart); $t++)
            {

                if( ($timeclick <= $timeslotcutoffend[$t]) && ($timeclick >= $timeslotcutoffstart[$t]) )
                    $timetocheck = $timeslotcutoffend[$t];

            }

            $timetocheck[4] = "0";//converts e.g. "12:01:00" to "12:00:00" at 4th position starting from left 0th

            if($timetocheck == ($ddv->{$ddv->type."Time"}) )
            {

                if($booluni)
                {
                    /*if(!array_key_exists("unified", $zones))
                        $zones["unified"] = array();*/

                    $zones["unified"][] = $ddv;


                }
                else
                {

                    if(!array_key_exists($ddv->zone->{$ddv->type}, $zones))
                        $zones[$ddv->zone->{$ddv->type}] = array();

                    //array_push($zones[$ddv->zone->{$ddv->type}],$ddv);
                    $zones[$ddv->zone->{$ddv->type}][] = $ddv;


                }
            }






        }


    }

}


echo "<pre>";
print_r($zones);
echo "</pre>";











?>


