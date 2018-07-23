<?php
if (! function_exists('fixLanguage')) {
    function fixLanguage($lang)
    {
        if($lang == "en"){
            return "English";
        }elseif($lang == "fa"){
            return "Farsi";
        }elseif($lang == "ps"){
            return "Pashto";
        }else{
            return "No language";
        }
    }
}

if(! function_exists('giveMeFileFormat')){
    function giveMeFileFormat($fileFormat)
    {
        $formats = array(
            'application/pdf' => 'PDF',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word 2016',
            'application/msword' => 'Word 2003',
            'text/plain' => 'Text',
            'audio/mpeg' => 'Audio',
            'video/mp4' => 'Video'
        );

        if ( count($formats[$fileFormat]) > 0){
            return $formats[$fileFormat];
        }else{
            return "No file mime detected";
        }
    }
}

if(! function_exists('giveMeResourceIcon')){
    function giveMeResourceIcon($fileFormat)
    {
        $formats = array(
            'application/pdf' => 'fas fa-file-pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'far fa-file-word',
            'application/msword' => 'far fa-file-word',
            'text/plain' => 'fas fa-file-alt',
            'audio/mpeg' => 'fas fa-file-audio',
            'video/mp4' => 'fas fa-video'
        );

        if ( count($formats[$fileFormat]) > 0){
            $theIcon = $formats[$fileFormat];
            return $formats[$fileFormat];
        }else{
            return $formats['application/pdf'];
        }
    }
}

//Abstracts in Drupal installation had /sites/default/files/learn-1044078_960_720_0.jpg type image links
//In here, I am fixing that and applying Laravel's way of showing images
if (! function_exists('fixImage')) {
    function fixImage($abstract)
    {
		//To replace hardcoded url to dynamic base_url
		$abstract = str_replace('http://www.darakhtdanesh.org/', URL::to('/').'/', $abstract);

        preg_match_all('/src="([^"]*)"/',$abstract,$matches);
        if(count($matches[1])> 0){
            $replaceMe = array();
            $originalMe = array();

            for($i=0; $i<count($matches[1]); $i++){
				$absStr = $matches[1][$i];
				if(strpos($absStr, 'youtube') == false){
					$absArray = explode('/',$absStr);
					$imageName = last($absArray);
					$fixedImage = Storage::disk('public')->url($imageName);

					array_push($replaceMe, $fixedImage);
					array_push($originalMe, $absStr);
				}
            }
			$finalFixedStr = str_replace($originalMe, $replaceMe, $abstract);
            return $finalFixedStr;
        }else{
            return $abstract;
        }
    }
}

if (! function_exists('unpackResourceObject')) {
    function unpackResourceObject($resourceObject, $field)
    {
        $str = "";
        foreach($resourceObject AS $obj)
        {
            $str .= $obj->$field.", ";
        }
        $str = rtrim($str, ", ");
        return $str;
    }
}

if (! function_exists('checkUserPassword')) {
    function checkUserPassword($planePassword, $userPassword)
    {
        //include(app_path() . '/support/DrupalPasswordHasher.php');

        if(user_check_password($planePassword, $userPassword)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}

if (! function_exists('isAdmin')) {
    function isAdmin()
    {
        $user = factory(App\User::class)->make();

        if($user->isAdministrator(Auth::id())){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}

if(! function_exists('getImagefromResource')) {
    function getImagefromResource($abstract)
    {
        preg_match('/src="([^"]*)"/',$abstract,$matches);
        if(count($matches)> 0){
            $absStr = $matches[1];
            $absArray = explode('/',$absStr);
            $imageName = last($absArray);
            $fixedImage = Storage::disk('public')->url($imageName);
            return $fixedImage;
        }else{
            return "https://dummyimage.com/250x200/eeeeee/000000.png&text=DDL+Resource";
        }
    }
}

if(! function_exists('formatBytes')){
    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}

if(! function_exists('encodeUrl')){
    function encodeUrl($string) {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }
}

/**
 * Parses a user agent string into its important parts
 *
 * @author Jesse G. Donat <donatj@gmail.com>
 * @link https://github.com/donatj/PhpUserAgent
 * @link http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT
 * @param string|null $u_agent User agent string to parse or null. Uses $_SERVER['HTTP_USER_AGENT'] on NULL
 * @throws \InvalidArgumentException on not having a proper user agent to parse.
 * @return string[] an array with browser, version and platform keys
 */
function parse_user_agent( $u_agent = null ) {
	if( is_null($u_agent) ) {
		if( isset($_SERVER['HTTP_USER_AGENT']) ) {
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
		} else {
			throw new \InvalidArgumentException('parse_user_agent requires a user agent');
		}
	}

	$platform = null;
	$browser  = null;
	$version  = null;

	$empty = array( 'platform' => $platform, 'browser' => $browser, 'version' => $version );

	if( !$u_agent ) return $empty;

	if( preg_match('/\((.*?)\)/im', $u_agent, $parent_matches) ) {
		preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|(Open|Net|Free)BSD|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS|Switch)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);

		$priority = array( 'Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'FreeBSD', 'NetBSD', 'OpenBSD', 'CrOS', 'X11' );

		$result['platform'] = array_unique($result['platform']);
		if( count($result['platform']) > 1 ) {
			if( $keys = array_intersect($priority, $result['platform']) ) {
				$platform = reset($keys);
			} else {
				$platform = $result['platform'][0];
			}
		} elseif( isset($result['platform'][0]) ) {
			$platform = $result['platform'][0];
		}
	}

	if( $platform == 'linux-gnu' || $platform == 'X11' ) {
		$platform = 'Linux';
	} elseif( $platform == 'CrOS' ) {
		$platform = 'Chrome OS';
	}

	preg_match_all('%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|IceCat|Safari|MSIE|Trident|AppleWebKit|
				TizenBrowser|(?:Headless)?Chrome|Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|CriOS|UCBrowser|Puffin|SamsungBrowser|
				Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
				Valve\ Steam\ Tenfoot|
				NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
				(?:\)?;?)
				(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
		$u_agent, $result, PREG_PATTERN_ORDER);

	// If nothing matched, return null (to avoid undefined index errors)
	if( !isset($result['browser'][0]) || !isset($result['version'][0]) ) {
		if( preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result) ) {
			return array( 'platform' => $platform ?: null, 'browser' => $result['browser'], 'version' => isset($result['version']) ? $result['version'] ?: null : null );
		}

		return $empty;
	}

	if( preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $u_agent, $rv_result) ) {
		$rv_result = $rv_result['version'];
	}

	$browser = $result['browser'][0];
	$version = $result['version'][0];

	$lowerBrowser = array_map('strtolower', $result['browser']);

	$find = function ( $search, &$key, &$value = null ) use ( $lowerBrowser ) {
		$search = (array)$search;

		foreach( $search as $val ) {
			$xkey = array_search(strtolower($val), $lowerBrowser);
			if( $xkey !== false ) {
				$value = $val;
				$key   = $xkey;

				return true;
			}
		}

		return false;
	};

	$key = 0;
	$val = '';
	if( $browser == 'Iceweasel' || strtolower($browser) == 'icecat' ) {
		$browser = 'Firefox';
	} elseif( $find('Playstation Vita', $key) ) {
		$platform = 'PlayStation Vita';
		$browser  = 'Browser';
	} elseif( $find(array( 'Kindle Fire', 'Silk' ), $key, $val) ) {
		$browser  = $val == 'Silk' ? 'Silk' : 'Kindle';
		$platform = 'Kindle Fire';
		if( !($version = $result['version'][$key]) || !is_numeric($version[0]) ) {
			$version = $result['version'][array_search('Version', $result['browser'])];
		}
	} elseif( $find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS' ) {
		$browser = 'NintendoBrowser';
		$version = $result['version'][$key];
	} elseif( $find('Kindle', $key, $platform) ) {
		$browser = $result['browser'][$key];
		$version = $result['version'][$key];
	} elseif( $find('OPR', $key) ) {
		$browser = 'Opera Next';
		$version = $result['version'][$key];
	} elseif( $find('Opera', $key, $browser) ) {
		$find('Version', $key);
		$version = $result['version'][$key];
	} elseif( $find('Puffin', $key, $browser) ) {
		$version = $result['version'][$key];
		if( strlen($version) > 3 ) {
			$part = substr($version, -2);
			if( ctype_upper($part) ) {
				$version = substr($version, 0, -2);

				$flags = array( 'IP' => 'iPhone', 'IT' => 'iPad', 'AP' => 'Android', 'AT' => 'Android', 'WP' => 'Windows Phone', 'WT' => 'Windows' );
				if( isset($flags[$part]) ) {
					$platform = $flags[$part];
				}
			}
		}
	} elseif( $find(array( 'IEMobile', 'Edge', 'Midori', 'Vivaldi', 'SamsungBrowser', 'Valve Steam Tenfoot', 'Chrome', 'HeadlessChrome' ), $key, $browser) ) {
		$version = $result['version'][$key];
	} elseif( $rv_result && $find('Trident', $key) ) {
		$browser = 'MSIE';
		$version = $rv_result;
	} elseif( $find('UCBrowser', $key) ) {
		$browser = 'UC Browser';
		$version = $result['version'][$key];
	} elseif( $find('CriOS', $key) ) {
		$browser = 'Chrome';
		$version = $result['version'][$key];
	} elseif( $browser == 'AppleWebKit' ) {
		if( $platform == 'Android' ) {
			// $key = 0;
			$browser = 'Android Browser';
		} elseif( strpos($platform, 'BB') === 0 ) {
			$browser  = 'BlackBerry Browser';
			$platform = 'BlackBerry';
		} elseif( $platform == 'BlackBerry' || $platform == 'PlayBook' ) {
			$browser = 'BlackBerry Browser';
		} else {
			$find('Safari', $key, $browser) || $find('TizenBrowser', $key, $browser);
		}

		$find('Version', $key);
		$version = $result['version'][$key];
	} elseif( $pKey = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser'])) ) {
		$pKey = reset($pKey);

		$platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $pKey);
		$browser  = 'NetFront';
	}

	return array( 'platform' => $platform ?: null, 'browser' => $browser ?: null, 'version' => $version ?: null );
}