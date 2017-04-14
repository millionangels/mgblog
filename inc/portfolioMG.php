<?php

// ***** get the current url *****//
$here = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$encodeHere = htmlentities($here);
// ***** detect mobile *****//
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();
$ismobile = $detect->isMobile();
$istablet = $detect->isTablet();

$UA = htmlentities($_SERVER['HTTP_USER_AGENT']);

// ***** build feature project *****//
$featuretext = "";
$featureimgs = "";
if (isset($_GET['id'])) {
    // get local file if it exists
    $project_url = "./inc/projects/" . $_GET['id'] . ".json";
    if (! file_exists($project_url)) {
        $saveNew = 1;
        $project_url = "http://www.behance.net/v2/projects/" . $_GET['id'] . "?api_key=IwNVrJ9EUzG0RGZzrlC3V7woAoGM2OPC";
//
    }
    // if it doesn't exist, call api, and write it 
    // to check: can I write a file on live server?
    
    if (file_exists($project_url)) {
        $project_string = file_get_contents($project_url); 
    }
   
    if ($project_string !== false) {
        $project_string = utf8_encode($project_string); 
            
        $pResults = json_decode($project_string, true ); 
        $project = $pResults['project'];
//    field names in behance json: id, privacy (public or private), name, url, fields (array), covers (assoc array), modules (array of custom, type image or text)
        //confirm that this is my project before writing file or showing project
//        print_r($project['owners'][0]['id']);
        if ($project['owners'][0]['id'] == 3631941) {
            if($saveNew == 1) {
                file_put_contents("./inc/projects/" . $_GET['id'] . ".json", $project_string);
            }
        } else {
            //redirect to the home page
            header("Location: home.php");
        }
        $featuretext .= "<h1>" . $project['name'] . "</h1>";
        $featuretext .= "<h3>";
        $featuretext .= join(", ", $project['fields']);
        $featuretext .= "</h3>";
        $featuretext .= "<h2>" . $project['description'] . "</h2>";
        $count = 0;
        // *** cap the number of images to load
        if ($ismobile) {
            $perload = 6;
        } else {
            $perload = 10;   
        }
        foreach ($project['modules'] as $id => $mod) {
            if ($count == $perload) {
                break;
            }
            if($mod['type'] == "image") {
                $count ++;
                 if($ismobile) {
                    $imagesrc = $mod['sizes']['disp']; 
                 } else {
                     // if no 1240, then use original
                    if ($mod['sizes']['max_1240']) {
                        $imagesrc = $mod['sizes']['max_1240']; 
                    } else {
                        $imagesrc = $mod['sizes']['original']; 
                    }
                 }
                 $featureimgs .= "<img class='mod' src='" . $imagesrc . "' alt='" . $project['name'] . " [designer: megan goodacre]'>";
            } // end if image
            if($mod['type'] == "text") {
                $featuretext .= "<p>" . $mod['text_plain'] . "</p>";
            }
        } // end foreach module
        if (count($project['modules']) > $perload) {
            $featureimgs .= "<p><a class='getmore' href='" . $project['url'] . "'>See more images on Behance</a></p>";
        } else {
            $featureimgs .= "<p><a class='getmore' href='" . $project['url'] . "'>View on Behance</a></p>";
        }
        $featuretext .= "<div class='share behance'><a href='" . $project['url'] . "' target='_blank'>Behance</a></div>";
        $featuretext .= "<div class='share facebook'><a href='https://www.facebook.com/sharer/sharer.php?u=" . $encodeHere. "' target='_blank'>Facebook</a></div>";
        $featuretext .= "<div class='share twitter'><a href='https://twitter.com/intent/tweet?url=" . $encodeHere . "' target='_blank'>Twitter</a></div>";
        $featuretext .= "<div class='share pinterest'><a href='javascript:pinIt();'>Pinterest</a></div>";
    } else {
        header("Location: home.php");
    }
}

// ***** build thumbs ***** //
// ***** map their fields to my fields ***** //
$myFields = array(
    'Web & UI'           => array('Web Design', 'UI/UX', 'Web Development'),
    'Identity'           => array('Branding'),
    'Print'              => array('Print Design', 'Exhibition Design', 'Packaging'),
    'Photography'        => array('Photography'),
    'Art & Illustration' => array('Painting', 'Illustration'),
);

// ***** get the json, map my fields to theirs, build thumb html, then filter by field ***** //
$thumbs_url = "./inc/behance.json";
$contents = file_get_contents($thumbs_url); 
$thumbs = "";
if ($contents !== false) {
    $contents = utf8_encode($contents); 
    $results = json_decode($contents, true); 
    $projects = $results['projects'];
    // sort projects by name
    uasort($projects, 'mgsort' );
    $fieldMap = array();
    $thumbArray = array();
// ***** loop through projects, build fieldMap and thumbhtml ***** //
    foreach ($projects as $key => $val) {
        $thumbHtml = "";
        foreach($val['fields'] as $field) {
            foreach($myFields as $mine => $theirs) {
                if(in_array($field, $theirs)) {
                    $fieldMap[$val['id']] = $mine;
                }
            }
        } // end foreach theirfield
        $thumbHtml .= "<li>";
        $thumbHtml .= "<a href='project.php?id=" . $val['id']. "'><img src='" . $val['covers']['404'] . "' alt='" . $val['name'] . " [designer: megan goodacre]'></a>";
        $thumbHtml .= "<a href='project.php?id=" . $val['id']. "'><h2>" . $val['name'] . "</h2></a>";
        $thumbHtml .= "</li>";
        $thumbArray[$val['id']] = $thumbHtml;
    } //end foreach project
    
// ***** group the thumbs by field ***** //
    if (isset($_GET['id'])) {
        // if this is the project page, only show thumbs from the same field, and don't include this one
        $fieldtofilter = $fieldMap[$_GET['id']];
        $idtofilter = $_GET['id'];
        $thumbs .= "<h1>other work in: " . $fieldtofilter . "</hl>";
        $thumbs .= "<ul><a name='" . $fieldtofilter . "'></a>";
        unset( $fieldMap[ $idtofilter ]);
        $filtered = array_filter( $fieldMap, 'mgfilter' );
        $thumbs .= join("",array_intersect_key($thumbArray, $filtered));
        $thumbs .="</ul>";
    } else { // otherwise show all the thumbs
        foreach($myFields as $key => $val) {
            $thumbs .= "<h1>" . $key . "</hl>";
            $thumbs .= "<ul><a name='" . $key . "'  class='bumpit'></a>";
            $fieldtofilter = $key;
            $filtered = array_filter($fieldMap, 'mgfilter');
            $thumbs .= join("",array_intersect_key($thumbArray, $filtered));
            
            $thumbs .="</ul>";
            $thumbs .= "<div><a href='#top' class='getmore'>back to top</a></div>";
        } //end foreach my Field
    }
} else { // no json to loop through
    $thumbs = "<li>oops, something's hinky with the data, sorry about that</li>";
}



function mgsort($a, $b) {
	return strcmp($a["name"], $b["name"]);
}
function mgfilter($v) {
	global $fieldtofilter;
	return $v == $fieldtofilter;   
}


/*
function mgfilter($v) {
	global $fieldtofilter, $idtofilter;
	return $v == $fieldtofilter && $k != $idtofilter;   
}
*/
