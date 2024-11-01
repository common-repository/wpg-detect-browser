<?php
/*
Plugin Name: WPG Detect browser
Plugin URI: http://www.wpguru.in/plugin/browser-css/
Description: This will add CSS classes in default WordPress body class. You can get browser version, OS and name.
Author: Rakesh Raja
Version: 2.0
Author URI: http://wpguru.in
*/


function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
 
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'ie';
        $ub = "MSIE";
    }
    elseif(preg_match('/Chrome/i',$u_agent) && preg_match('/Safari/i',$u_agent))
    {
        $bname = 'chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}
 
// now try it
 
function wpgbrowser() {
    $ua=getBrowser();
    $ver = $ua['version'];
  $arr = explode(".", $ver, 2);
    $version = $arr[0];
$yourbrowser= $ua['name'] . ' ' . $version . ' ' . $ua['platform'];
return $yourbrowser;
}

$ua=getBrowser();
$wpgbrowser = wpgbrowser();


// Add specific CSS class by filter
add_filter( 'body_class', 'my_class_names' );
function my_class_names( $classes ) {
  // add 'class-name' to the $classes array
  global $wpgbrowser;
  $classes[] = "$wpgbrowser";
  // return the $classes array
  return $classes;
}