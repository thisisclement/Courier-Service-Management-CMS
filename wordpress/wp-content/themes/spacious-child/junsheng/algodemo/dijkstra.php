<?php


if (!function_exists('computeEuclideanDistanceBetween')) {
    require_once 'nearestneighbor.php';
}

//http://en.wikipedia.org/wiki/Dijkstra%27s_algorithm

function dijkstra($data)
{


    /*echo "<pre>";
    print_r($data);
    echo "</pre>";*/

    $dist = array();
    //$neighbours = array();
    //use priority queue style

    $pq = array();

    $shortestpath = array();
    //$previous = array();



    /*echo "<pre>";
    echo "Neighbours:";
    print_r($neighbours);
    echo "</pre>";*/


    foreach($data as $dkey=>$dval)
    {

        /*echo "<pre>";
        echo "Before:";
        print_r($dval);
        echo "</pre>";*/

        //for testing only
        //if($dkey != "Cluster4")
            //continue;



        if(!array_key_exists($dkey, $shortestpath))
            $shortestpath[$dkey] = array();


        //no point doing calculation if there are less or equal to 2 points
        if(count($dval) <= 2)
        {
            $shortestpath[$dkey] = $dval;
            continue;
        }





        shuffle($dval);
        $sourcekey = key($dval);//key will always get the first item, so index will always be 0
        $dist[$sourcekey] = 0;

        /*echo "<pre>";
        print_r($dval);
        echo "</pre>";*/

        //$vertices[$dkey] = $dval;




        //add neighbours
        /*foreach($dval as $nkk=>$nvv)
        {
            //foreach($dval as $nk=>$nv)
            //{

                //if($nkk == $nk)
                    //continue;

                //$neighbours[$dkey][$nkk][$nk] = $nv;

            //}

            $neighbours[$dkey][$nkk] = $dval;
            unset($neighbours[$dkey][$nkk][$nkk]);

        }*/






        foreach($dval as $dk=>$dv)
        {

            if($sourcekey != $dk)
            {
                $dist[$dk] = INF;
                //$previous[$dk] = null;
            }

            //http://stackoverflow.com/questions/5166550/php-retrieve-min-object-from-multidimentional-object-array
            //http://stackoverflow.com/questions/2189479/get-the-maximum-value-from-an-element-in-a-multidimensional-array
            $pq[$dk] = $dist[$dk];

        }


        //if(!array_key_exists($dkey, $shortestpath))
            //$shortestpath[$dkey] = array();


        while(!empty($pq))
        {

            /*echo "<pre>";
            echo "PQ:<br/>";
            print_r($pq);
            echo "</pre>";*/

            $removekey = array_keys($pq, min($pq));

            //array_push($shortestpath[$dkey],$dval[$removekey[0]]);
            //array_push($shortestpath[$dkey],$vertices[$dkey][$removekey[0]]);

            //if(count($removekey)>1)
                //shuffle($removekey);

            //unset($pq[$removekey[0]]);

            /*echo "<pre>";
            print_r($vertices[$dkey][$removekey[0]]);
            echo "</pre>";*/

            /*echo "<pre>";
            print_r($removekey[0]);
            echo "</pre>";*/


            /*echo "<pre>";
            echo "Neighbours:";
            print_r($neighbours[$dkey][$removekey[0]]);
            echo "</pre>";*/

            //$neighbours[$dkey][$removekey[0]] = $dval;
            //unset($neighbours[$dkey][$removekey[0]][$removekey[0]]);


            if(count($pq) > 1)
            {

                //foreach($neighbours[$dkey][$removekey[0]] as $nkey=>$nval)
                //{
                foreach($dval as $nkey=>$nval)
                {

                    if(!array_key_exists($nkey, $pq))
                        continue;

                    if($removekey[0] == $nkey)
                        continue;

                    //uncomment this after testing (need it!)
                    //if($vertices[$dkey][$removekey[0]]->postalcode == $nval->postalcode)
                    if($dval[$removekey[0]]->postalcode == $nval->postalcode)
                        $alt = $dist[$removekey[0]];
                    else
                        $alt = $dist[$removekey[0]] + computeEuclideanDistanceBetween($dval[$removekey[0]]->latlng,$nval->latlng);
                        //$alt = $dist[$removekey[0]] + computeEuclideanDistanceBetween($vertices[$dkey][$removekey[0]]->latlng,$nval->latlng);

                    /*echo "<pre>";
                    echo "dist:<br/>";
                    print_r($dist[$nkey]);
                    echo "<br/>";
                    echo "alt:<br/>";
                    print_r($alt);
                    echo "</pre>";*/


                    if($alt < $dist[$nkey])
                    {
                        $dist[$nkey] = $alt;
                        //$previous[$nkey] = $vertices[$dkey][$removekey[0]];
                        //$previous[$nkey] = $dval[$removekey[0]];
                        $pq[$nkey] = $alt;
                    }





                }

            }


            array_push($shortestpath[$dkey],$dval[$removekey[0]]);

            unset($pq[$removekey[0]]);


            //unset($neighbours);

        }


        /*if(!array_key_exists($dkey, $shortestpath))
            $shortestpath[$dkey] = array();

        $shortestpath[$dkey] = $previous;

        echo "<pre>";
        print_r($shortestpath);
        echo "</pre>";

        return;*/

        unset($dist);
        //unset($neighbours);
        //unset($previous);
        //unset($pq);


        /*echo "<pre>";
        echo "After:";
        print_r($dval);
        echo "<br/>";
        print_r($sourcekey);
        echo "</pre>";*/



    }









    //return;
    return $shortestpath;
    //return data;
}




//if want to preserve the key of shuffle
//http://changelog.ca/log/2012/02/16/php_shuffle_array_preserve_keys

//Fisher-Yates shuffle algorithm
//http://pastebin.com/V1PBCHB8







?>