<?php

//constants
//define('EARTH_RADIUS', 6371000); //constant in meters
//define('OFFSET', 0);
//define('KEYNAME', 'Cluster');
//define('BF_THRESH', 100);//for brute force threshold, can change

function modifiedKmeans($data, $threshold, &$couriers, $iterations = INF)
{
    //$duplicate = $data;//clone new copy array shallow

    $centroids = array();

    $cour_needed = round(count($data)/$threshold);
    //$cour_needed = ceil(count($duplicate)/$threshold);


    if($cour_needed <= 1)
    {

       $courout = array_shift($couriers);

       //$centroids[KEYNAME."1"] = $data;
       $centroids[$courout->cid." ".$courout->name] = $data;
       return $centroids;
    }


    //deep copy array of objects
    $duplicate = array();
    $dup = array();
    foreach($data as $dk=>$dv)
    {
        $duplicate[$dk] = clone $dv;
        $dup[$dk] = clone $dv;
        $dup[$dk]->key = $dk;
    }

	
	$init_dist = INF; //infinity

    //$change = true;

    //http://stackoverflow.com/questions/5466323/how-exactly-does-k-means-work



    //remove duplicates for starting points
    $tmp = array();
    foreach($dup as $k => $v)
        $tmp[$k] = $v->postalcode;

    // Find duplicates in temporary array
    $tmp = array_unique($tmp);

    foreach($dup as $k => $v)
    {
        if (!array_key_exists($k, $tmp))
            unset($dup[$k]);
    }




    //just in case if the initial k points are less than expected (use the maximum it can offer)
    if(count($dup) < $cour_needed)
        $cour_needed = count($dup);





    //k-means++ selection (option 1)

    shuffle($dup);

    $sel = array_shift($dup);
    //unset($sel->key);


    //$sum = array();
    //$weight = array();

    for($c=1; $c <= $cour_needed; $c++)
    {

        //if($c == 1)
        //{

            //$k = array_rand($dup);

            //unset($dup[$k]->key);

            //$sel = $dup[$k];

            //unset($dup[$k]);

        //}


        $courout = array_shift($couriers);



        $centroids[$courout->cid." ".$courout->name][$courout->cid." ".$courout->name] = $sel;
        //$centroids[KEYNAME.$c][KEYNAME.$c] = $sel;


        if($c == $cour_needed)
            break;


        //first way (weighted probability variance)
        /*//$sum = 0;


        foreach($centroids as $cenk=>$cenv)
        {
            if(!array_key_exists($cenk,$sum))
                $sum[$cenk] = array();

            $sum[$cenk] = 0;
        }


        foreach($dup as $dk=>$dv)
        {


            //$dv->distance = computeSqEuclideanDistanceBetween($sel->latlng,$dv->latlng);
            //$sum+=$dv->distance;

            foreach($centroids as $cenk=>$cenv)
                $sum[$cenk]+=computeSqEuclideanDistanceBetween($cenv[$cenk]->latlng,$dv->latlng);

        }



        //$weight = 1/$sum;

        foreach($sum as $sk=>$sv)
        {


            if(!array_key_exists($sk,$weight))
                $weight[$cenk] = array();

            $weight[$cenk] = 1/$sum[$cenk];


        }


        $probmin = -INF;


        foreach($dup as $dk=>$dv)
        {

            //$prob = $dv->distance*$weight;

            //if($prob > $probmin)
            //{
                //$probmin = $prob;
                //$tmpk = $dk;
            //}


            //another way (check through all selected points to other points to find the next point)
            foreach($centroids as $cenk=>$cenv)
            {
                $prob = computeSqEuclideanDistanceBetween($cenv[$cenk]->latlng,$dv->latlng)*$weight[$cenk];

                if($prob > $probmin)
                {
                    $probmin = $prob;
                    $tmpk = $dk;
                }
            }






        }*/


        //2nd way (variance/distance)
        $varinf = -INF;

        foreach($dup as $dk=>$dv)
        {

            $var = computeSqEuclideanDistanceBetween($sel->latlng,$dv->latlng);

            if($var > $varinf)
            {
                $varinf = $var;
                $tmpk = $dk;
            }


            //another way (check through all selected points to other points to find the next point)
            /*foreach($centroids as $cenk=>$cenv)
            {


                $var = computeSqEuclideanDistanceBetween($cenv[$cenk]->latlng,$dv->latlng);

                if($var > $varinf)
                {
                    $varinf = $var;
                    $tmpk = $dk;
                }
            }*/


        }



        unset($dup[$tmpk]->key);

        //unset($dup[$tmpk]->distance);//comment for 2nd  way only

        $sel = $dup[$tmpk];

        unset($dup[$tmpk]);

    }

    unset($dup);







    //convex hull (option 2)
    /*$chpts = convexHull::calConvexHull($dup);

    unset($dup);

    //if points are same as needed, don't border to sort (since all are needed)
    if(count($chpts) > $cour_needed || count($chpts) < $cour_needed)
        $chpts = getAllDistancebyDescOrder($chpts);

    //just in case if the initial k points are less than expected (use the maximum it can offer)
    if(count($chpts) < $cour_needed)
        $cour_needed = count($chpts);


    //echo "<pre>";
    //print_r($chpts);
    //echo "</pre>";

    for($c=1; $c <= $cour_needed; $c++)
    {
        $popf = array_shift($chpts);
        //$centroids[$popf->key][$popf->key] = $duplicate[$popf->key];
        //$centroids[KEYNAME.$c][KEYNAME.$c] = $duplicate[$popf->key];
        $courout = array_shift($couriers);
        $centroids[$courout->cid." ".$courout->name][$courout->cid." ".$courout->name] = $duplicate[$popf->key];
    }

    unset($chpts);*/






    //check all distance (option 3)
    /*$bfpts = getAllDistancebyDescOrder($dup);

    unset($dup);

    //echo "<pre>";
    //print_r($bfpts);
    //echo "</pre>";


    for($c=1; $c <= $cour_needed; $c++)
    {
        $popf = array_shift($bfpts);
        //$centroids[$popf->key][$popf->key] = $duplicate[$popf->key];
        //$centroids[KEYNAME.$c][KEYNAME.$c] = $duplicate[$popf->key];
        $courout = array_shift($couriers);
        $centroids[$courout->cid." ".$courout->name][$courout->cid." ".$courout->name] = $duplicate[$popf->key];
    }

    unset($bfpts);*/







    //do random dynamic (option 4)

    /*shuffle($dup);

    $rands = array_slice($dup,0,$cour_needed);

    unset($dup);

    foreach($rands as $randv)
    {

        unset($randv->key);

        //$centroids[KEYNAME.$c][KEYNAME.$c] = $randv;
        $courout = array_shift($couriers);
        $centroids[$courout->cid." ".$courout->name][$courout->cid." ".$courout->name] = $randv;

    }

    unset($rands);*/



    //array_rand not very random
    /*$keys = array_rand($dup, $cour_needed);

    foreach($keys as $kv)
    {

        unset($dup[$kv]->key);

        //$centroids[KEYNAME.$c][KEYNAME.$c] = $dup[$kv];
        $courout = array_shift($couriers);
        $centroids[$courout->cid." ".$courout->name][$courout->cid." ".$courout->name] = $dup[$kv];

    }


    //if every options done, then unset($dup);

    unset($dup);*/



    /*for($c=1; $c <= $cour_needed; $c++)
    {

        $k = array_rand($dup);

        //have to make sure the numbers pick are not pick again

        unset($dup[$k]->key);

        //$centroids[KEYNAME.$c][KEYNAME.$c] = $dup[$k];
        $courout = array_shift($couriers);
        $centroids[$courout->cid." ".$courout->name][$courout->cid." ".$courout->name] = $dup[$k];

        unset($dup[$k]);

    }


    //if every options done, then unset($dup);

    unset($dup);*/









	//$diff = true;
	$br = false;

	
	
	//$index = 0;
	
	//$old_centroids = array();
	
	//$iter = 0;//to track 1st time and the others because the centroids are not included as a 'point' itself after 1st iteration
	
	$step = 0;

	//while($diff)
    while($step < $iterations)
	{

        ++$step;


		//$change = false;
		
		//$iter++;
		
				
		foreach($duplicate as $key=>$duplicate_pts)
		{
			
			//$duplicate[$key]->distance = 0;//default initialize
			
			
			
			/*if(array_key_exists($key, $centroids))//a similar key is already pick as centroid
			{
					//$cen_key = $key
					//$centroids[$cen_key][$key]->distance = 0;
					$centroids[$key][$key]->distance = 0;
					continue;
			}*/
			

			foreach($centroids as $cen_key=>$cen_pts)
			{
	
				


                //uncomment this after testing (need it!)
                if(($cen_pts[$cen_key]->lat == $duplicate_pts->lat) && ($cen_pts[$cen_key]->lng == $duplicate_pts->lng))
                {
                    $tempkey = $cen_key;
                    $duplicate[$key]->distance = 0;
                    break;
                }

			
				//$dist = computeHaversineDistanceBetween($cen_pts[$cen_key]->latlng, $duplicate_pts->latlng);
                $dist = computeSqEuclideanDistanceBetween($cen_pts[$cen_key]->latlng, $duplicate_pts->latlng);


                //for testing purposes (can comment it after testing)
                /*if($dist == 0)
                {
                    $tempkey = $cen_key;
                    $duplicate[$key]->distance = $dist;
                    break;
                }*/
				
				
				
				if($dist < $init_dist)
				{
					/*echo '<pre>';
					echo $dist;
					echo '</pre>';*/
					
					$init_dist = $dist;
					//$change = true;
					$tempkey = $cen_key;
					$duplicate[$key]->distance = $dist;
					
				}
				

			}


            $centroids[$tempkey][$key] = $duplicate[$key];


            //array_push($centroids[$tempkey],$duplicate[$key]);


			$init_dist = INF; //set back after each loop

			

		}



        /*echo "<pre>";
        print_r($centroids);
        echo "</pre>";*/





        //trying to make equal cluster (May not need this, causing more problem than it seems, does not seem feasible and guaranteed, also may results in infinite loop)

        //http://stackoverflow.com/questions/7433569/php-sort-a-multidimensional-array-by-number-of-items
        //use uasort instead of uksort (to preserve keys)
        /*uasort($centroids, function($a, $b) { return count($b) - count($a); });

        //may have to sort by desc distance

        foreach($centroids as $key=>$pts)
        {

            $ini_d = INF;
            $in = false;

            foreach($pts as $k=>$v)
            {

                if(count($centroids[$cenkey])-1 <= ($threshold))
                    continue;


                if($key == $k)
                    continue;


                foreach($centroids as $cenkey=>$cenval)
                {


                    if($key == $cenkey)
                        continue;

                    //if(count($centroids[$cenkey])-1 >= ($threshold))
                        //continue;


                    $d = computeSumOfSquareDistanceBetween($pts[$k]->latlng, $cenval[$cenkey]->latlng);

                    if($d < $ini_d)
                    {
                        $ini_d = $d;
                        $tempcenkey = $cenkey;
                        $pts[$k]->distance = $d;
                        $in = true;
                    }


                }

                if($in == true)
                {
                    //insert to new
                    $centroids[$tempcenkey][$k] = $pts[$k];

                    unset($centroids[$key][$k]);

                    $in = false;
                }

                $ini_d = INF;

            }


        }*/

        //need to recalculate centroid... and go back to the loop


        /*echo "<pre>";
        print_r($centroids);
        echo "</pre>";*/



		$old_centroids = $centroids;


		//if($diff)
		//{

            //if empty cluster, show error? (Can't really tackle this problem)
            //using various initialisation options for starting points is the best I can think of to minimize having empty cluster


			//if($index > 0)
			//{
				//for($i = 1; $i <= count($centroids); $i++)
					//unset($centroids[$i][$i]);
					//unset($centroids[KEYNAME.$i][KEYNAME.$i]);
				
				//$index = 0;
			//}



            foreach($centroids as $ck=>$cv)
                unset($centroids[$ck][$ck]);


            $centroidscopy = array();


			foreach($centroids as $cens_key=>$cens_val)
			{
                //$index++;

                /*if(count($centroids[$cens_key]) == 0)
                {
                    unset($centroids[$cens_key]);
                    continue;
                }*/

				$newlat = 0;
				$newlng = 0;
				$newlatlng = new stdClass();
				$newObj = new stdClass();

				foreach($cens_val as $ck=>$cv)
				{
					
					$newlat+=$cv->lat;
					$newlng+=$cv->lng;
				}
				
				
				$newlat = $newlat/count($centroids[$cens_key]);
				$newlng = $newlng/count($centroids[$cens_key]);
				
				
				
				$newObj->lat = $newlat;
				$newObj->lng = $newlng;
				
				$newlatlng->lat = $newlat;
				$newlatlng->lng = $newlng;
				
				$newObj->latlng = $newlatlng;


                unset($centroids[$cens_key]);

				//$centroids[$index][$index] = $newObj;
                //$centroids[KEYNAME.$index][KEYNAME.$index] = $newObj;
                //$centroidscopy[KEYNAME.$index][KEYNAME.$index] = $newObj;
                $centroidscopy[$cens_key][$cens_key] = $newObj;


			}

			//$change = true;
			
			//$counting++;

            $centroids = $centroidscopy;

            unset($centroidscopy);


            //set default values before comparing old centroids and new centroids
            $br = true;
            $n = 1;



            foreach($old_centroids as $oldkey=>$oldval)
            {



                //check if centroids are different (compare to only centroid from the same cluster)
                //if( ($oldval[$oldkey]->lat !== $centroids[KEYNAME.$n][KEYNAME.$n]->lat) && ($oldval[$oldkey]->lng !== $centroids[KEYNAME.$n][KEYNAME.$n]->lng))
                if( ($oldval[$oldkey]->lat !== $centroids[$oldkey][$oldkey]->lat) && ($oldval[$oldkey]->lng !== $centroids[$oldkey][$oldkey]->lng))
                {
                    //$diff = true;
                    $br = false;

                    /*echo "<pre>";
                    echo "mean didn't converge";
                    echo "</pre>";*/
                }


                $n++;



            }




            /*echo "<pre>";
            print_r($centroids);
            echo "</pre>";*/


		//}



        if($br)
            break;




			
	}

    $centroids = $old_centroids;
    unset($old_centroids);
    unset($duplicate);
    unset($data);



    //remove the centroid (not needed, only want the points)
	//if($index > 0)
	//{
		//for($i = 1; $i <= count($centroids); $i++)
			//unset($centroids[$i][$i]);
            //unset($centroids[KEYNAME.$i][KEYNAME.$i]);
	//}

    foreach($centroids as $ck=>$cv)
        unset($centroids[$ck][$ck]);
        //unset($cv[$ck]);


    /*echo "<pre>";
    print_r($centroids);
    echo "</pre>";*/



    //sort by keys (ascending)
    //ksort($centroids);


    $formattedarr = array();

	foreach($centroids as $k1=>$v1)
	{
		if(!array_key_exists($k1, $formattedarr))
			$formattedarr[$k1] = array();
				
		foreach($v1 as $k2=>$v2)
		{
			array_push($formattedarr[$k1],$v2);
			//unset($v2->distance);//uncomment this, if for testing purpose comment it
		}
		
	}


    /*echo '<pre>';
    echo 'formatted:<br/>';
    print_r($formattedarr);
    echo '</pre>';*/

	
	//unset($centroids);
	
	$centroids = &$formattedarr;// using &, means reference/pointer (maintains a copy)

    $centroids = $formattedarr;

	/*echo '<pre>';
	print_r(json_encode($centroids, JSON_PRETTY_PRINT));
	echo '</pre>';*/


    /*echo '<pre>';
    print_r($centroids);
    echo '</pre>';*/



    //if test for quality + equality, may have to return a new array of 2 things
    //use sum of square errors, inter/intra similarity, by count of points in a cluster divide by other clusters? (Have to think)
	
	
	return $centroids;
	

}




















//can also use Euclidean or Vincenty Distance
//similar to Google Maps API computeDistanceBetween
/*function computeHaversineDistanceBetween($latLngFrom, $latLngTo)//source, destination
{
  // convert from degrees to radians
  $latFrom = deg2rad($latLngFrom->lat);
  $lngFrom = deg2rad($latLngFrom->lng);
  $latTo = deg2rad($latLngTo->lat);
  $lngTo = deg2rad($latLngTo->lng);

  $latDelta = $latTo - $latFrom;
  $lngDelta = $lngTo - $lngFrom;

  //great circle haversine distance formula:
  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + 
			cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
  
  return $angle * EARTH_RADIUS;
}*/


/*function computeEuclideanDistanceBetween($latLngFrom, $latLngTo)//source, destination
{
    // convert from degrees to radians
    $latFrom = deg2rad($latLngFrom->lat);
    $lngFrom = deg2rad($latLngFrom->lng);
    $latTo = deg2rad($latLngTo->lat);
    $lngTo = deg2rad($latLngTo->lng);

    //$latDelta = pow($latTo - $latFrom, 2);
    //$lngDelta = pow($lngTo - $lngFrom, 2);

    $latDelta = ($latTo - $latFrom)*($latTo - $latFrom);
    $lngDelta = ($lngTo - $lngFrom)*($lngTo - $lngFrom);

    //great circle euclidean distance formula:
    $dis = sqrt($latDelta + $lngDelta);

    return $dis;
}*/


function computeSqEuclideanDistanceBetween($latLngFrom, $latLngTo)//source, destination
{
    // convert from degrees to radians
    $latFrom = deg2rad($latLngFrom->lat);
    $lngFrom = deg2rad($latLngFrom->lng);
    $latTo = deg2rad($latLngTo->lat);
    $lngTo = deg2rad($latLngTo->lng);

    //$latDelta = pow($latTo - $latFrom, 2);
    //$lngDelta = pow($lngTo - $lngFrom, 2);

    $latDelta = ($latTo - $latFrom)*($latTo - $latFrom);
    $lngDelta = ($lngTo - $lngFrom)*($lngTo - $lngFrom);

    //great circle euclidean distance formula:
    $dis = $latDelta + $lngDelta;

    return $dis;
}


function getAllDistancebyDescOrder($points){

    //search furthest points
    $max = -INF;

    foreach($points as $key=>$val)
    {
        foreach($points as $k=>$v)
        {
            if($val == $v)
                continue;

            $dis = computeSqEuclideanDistanceBetween($val->latlng, $v->latlng);
            if($dis >= $max)
            {
                $val->distance = $dis;//can remove use for checking only
                $val->towho = $v->key;//can remove use for checking only
                $max = $dis;
            }

            //ob_flush();
            //flush();
            //sleep(2);

        }

        $max = -INF;//reset

    }


    //sort by descending distance (key not important here because I already stored in the object)
    usort($points, function ($a, $b){
        if($a->distance == $b->distance)
            return 0;

        return ($a->distance > $b->distance) ? -1 : 1;
    });


    return $points;
}








//my self-class for convexhull
class convexHull{

    public static function calConvexHull($points)
    {

        $p = array();
        foreach($points as $pk=>$pv)
        {
            $p[$pk] = clone $pv;
        }

        //http://stackoverflow.com/questions/2286597/using-usort-in-php-to-sort-an-array-of-objects
        //use uasort instead to preserve keys (sort by ascending)
        //lng = y axis, lat = x axis
        uasort($p, function ($a, $b){
            if($a->lng == $b->lng)
                return $a->lat - $b->lat;

            return ($a->lng < $b->lng) ? -1 : 1;
        });


        //can put but in my case, won't happen
        /* if(count($points)<=1)
             return $points;*/

        //for lower hull
        $lowerhull = self::callowerupper($p);



        //for upper hull
        //array_reverse($points);
        //http://stackoverflow.com/questions/1618398/given-a-set-of-points-how-do-i-find-the-two-points-that-are-farthest-from-each
        //http://stackoverflow.com/questions/17137962/find-the-point-furthest-away-from-n-other-points
        $upperhull = self::callowerupper(array_reverse($p));

        return array_merge( array_slice($lowerhull, 0, count($lowerhull)-1),  array_slice($upperhull, 0, count($upperhull)-1) );

    }


    private static function cross($o, $a, $b){

        return ($a->lng - $o->lng) * ($b->lat - $o->lat) - ($a->lat - $o->lat) * ($b->lng - $o->lng);

    }


    private static function callowerupper($points){

        $hull = array();

        foreach($points as $key=>$val)
        {
            //$val->key = $key;//to store the key

            while( (count($hull) >= 2) && (self::cross($hull[count($hull)-2],$hull[count($hull)-1],$val) <= 0) )
                array_pop($hull);

            array_push($hull,$val);

        }

        return $hull;

    }





}























?>