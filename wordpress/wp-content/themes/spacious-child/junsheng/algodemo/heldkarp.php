<?php


if (!function_exists('computeEuclideanDistanceBetween')) {
    require_once 'nearestneighbor.php';
}

//ini_set('max_execution_time', 300);


function heldkarp($data){


    //$q = array();

    //need to get a random point as starting vertex
    //remove the starting vertex from the set of data
    foreach($data as $dkey=>$dval)
    {



        shuffle($dval);


        $dupdval = $dval;

        /*echo "<pre>";
        print_r($dval);
        echo "</pre>";*/


        //need to get key
        //$startvertex = key($dval);



        $dist = array();

        //calculate all distances between data points
        foreach($dupdval as $dk=>$dv)
        {

            foreach($dupdval as $k=>$v)
            {

                //if($dk == $k)
                    //continue;

                $dist[$dk][$k] = computeEuclideanDistanceBetween($dv->latlng, $v->latlng);

            }

        }

        /*echo "<pre>";
        print_r($dist);
        echo "</pre>";*/


        //don't know why cannot get key here after the for loop above, I have to use $dupdval to duplicate
        $startvertex = key($dval);
        //print_r($startvertex);
        unset($dval[$startvertex]);


        /*echo "<pre>";
        //print_r($startvertex);
        //echo "<br/>";
        print_r($dval);
        echo "</pre>";*/

        //$root = array();
        $root = new stdClass();

        $cost = getMinCostPath($startvertex, $dval, $root, $dist);

        /*echo "<pre>";
        print_r($dval);
        echo "</pre>";*/

        $q[$dkey] = array();
        //array_push($q[$dkey], $startvertex);

        //do tree traversal to get the path

        echo "<pre>";
        print_r($root);
        echo "</pre>";


        unset($dist);
        unset($root);





    }

    //$cost = getMinCostPath();

    return;//return the data

}


//have to check this recursive function
function getMinCostPath($startvertex, $set, &$root, &$dist)
{


    //return distance of itself
    if(count($set) == 0)
        return 0;

    //need deep copy
    foreach($set as $sk=>$sd)
    {
        $set[$sk] = clone $sd;
        $set[$sk]->selected = 0;
    }

    /*echo "<pre>";
    print_r($set);
    echo "</pre>";*/

    $minCost = INF; //default
    //$i = 0;
    //$selectedindex = $i;

    //$selectedindex = null;

    //if(!array_key_exists($startvertex, $root))
        //$root[$startvertex] = array();
        $root->child = array();


    foreach($set as $sk=>$sd)
    {

        //$root[$startvertex][$sk] = $sd;
        $root->child[$sk] = $sd;
        $currvertexcost = $dist[$startvertex][$sk];

        $setdup = $set;
        unset($setdup[$sk]);

        //$costfromhere = getMinCostPath($sk,$setdup,$root,$dist);
        $costfromhere = getMinCostPath($sk,$setdup,$root->child[$sk],$dist);
        $newcost = $currvertexcost + $costfromhere;

        if($newcost < $minCost)
        {
            $minCost = $newcost;
            $selectedindex = $sk;

        }


    }


    //$root[$startvertex][$selectedindex]->selected = 1;
    $root->child[$selectedindex]->selected = 1;


    return $minCost;//return the min cost path
}





?>