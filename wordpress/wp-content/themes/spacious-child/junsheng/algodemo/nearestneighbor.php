<?php



//will return a ranking, but not always the most optimal
function nearestNeighbor($data){


    /*print_r('<pre>before:<br/>');
    print_r($data);*/

    $visited = array();


    foreach($data as $dkey=>$dval)
    {

        $init_dist = INF;

        if(!array_key_exists($dkey, $visited))
                $visited[$dkey] = array();


        //no point doing calculation if there are less or equal to 2 points
        if(count($dval) <= 2)
        {
            $visited[$dkey] = $dval;
            continue;
        }


        shuffle($dval);
        //echo '<br/>shuffled: <br/>';

        $v = array_shift($dval);

        array_push($visited[$dkey], $v);

        /*print_r('<pre>');
            print_r($v);*/

        while(!empty($dval)){


            if(count($dval) == 1)
            {
                array_push($visited[$dkey], array_shift($dval));
                break;
            }


            foreach($dval as $key=>$val)
            {

                /*echo '<br/>removed: <br/>';
                print_r($val);*/


                //uncomment this after testing (need it!)
                if($v->postalcode == $val->postalcode)
                {
                    $tempkey = $key;
                    break;
                }



                //$dist = computeHaversineDistanceBetween($v->latlng, $val->latlng);
                $dist = computeEuclideanDistanceBetween($v->latlng, $val->latlng);

                if($dist < $init_dist)
                {
                    $init_dist = $dist;
                    $tempkey = $key;
                }


            }


            $v = $dval[$tempkey];
            array_push($visited[$dkey], $dval[$tempkey]);
            //array_splice($dval, $tempkey, 1);//remove from array
            unset($dval[$tempkey]);




            $init_dist = INF;//reset




        }





    }


    unset($data);

    /*print_r('<pre>after: <br/>');
    print_r($visited);*/


    return $visited;
		
}






?>