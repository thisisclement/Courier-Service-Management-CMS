<?php


$filetoread = array("kmeans++","convexhull","random","bruteforce");

$avg = array();

$div = 8;

while(!empty($filetoread))
{

    $total = array();


    $filename = array_shift($filetoread);

    $handle = fopen($filename . ".txt", "r");

    if ($handle) {

        while (($line = fgets($handle)) !== false) {
            // process the line read.

            $linearr = explode(" ",$line);

            if(!array_key_exists($linearr[0], $total))
            {
                $total[$linearr[0]] = array();
                $obj = new stdClass();
                $obj->name = $filename;
                $obj->points = $linearr[0];
                $obj->time = $linearr[1];
                $obj->equality = $linearr[2];
                $obj->closness = $linearr[3];
                $total[$linearr[0]] = $obj;
            }
            else
            {
                $total[$linearr[0]]->time += $linearr[1];
                $total[$linearr[0]]->equality += $linearr[2];
                $total[$linearr[0]]->closness += $linearr[3];
            }


        }


    }
    else {
        // error opening the file.
    }
    fclose($handle);


    foreach($total as $tk=>$tv)
    {

        if(!array_key_exists($tk, $avg))
            $avg[$tk] = array();

        /*if($tk == "40000" && $total[$tk]->name == "bruteforce")
        {

            $var = 8; //special divide by 8 for now

            $total[$tk]->time /= $var;
            $total[$tk]->equality /= $var;
            $total[$tk]->closness /= $var;
            goto here;
        }*/

        $total[$tk]->time /= $div;
        $total[$tk]->equality /= $div;
        $total[$tk]->closness /= $div;

        //here:
        array_push($avg[$tk],$total[$tk]);

    }



    unset($total);



}


//into graph format array

/*
['Points', 'Kmeans++', 'Convexhull', 'Random', 'Bruteforce'],
['100', 1000, 400, 323, 342],
['1000', 1170, 460, 564, 432],
['10000', 1170, 460, 123, 312],
['40000', 1170, 460, 413, 434]
*/

$grapharr = array();

array_push($grapharr, ['Points', 'Kmeans++', 'Convexhull', 'Random', 'Bruteforce']);


//$a = array();
$json[0] = array();
$json[1] = array();
$json[2] = array();

foreach($avg as $ak=>$av)
{
    $a[0] = array();
    $a[1] = array();
    $a[2] = array();

    foreach($av as $aak=>$aav)
    {

        array_push($a[0],$aav->time);
        array_push($a[1],$aav->equality);
        array_push($a[2],$aav->closness);

    }

    array_unshift($a[0],$ak);
    array_unshift($a[1],$ak);
    array_unshift($a[2],$ak);

    array_push($json[0], $a[0]);
    array_push($json[1], $a[1]);
    array_push($json[2], $a[2]);

    unset($a[0]);
    unset($a[1]);
    unset($a[2]);

}


/*echo "<pre>";
print_r($json);
echo "</pre>";*/


if(isset($_POST['request'])) {

    echo json_encode($json);
}


?>