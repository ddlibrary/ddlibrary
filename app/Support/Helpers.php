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

if(! function_exists('giveMeCC')){
    function giveMeCC($cc)
    {
        $ccTypes = array(
            0 => 'None',
            1 => 'CC BY-SA',
            2 => 'CC BY-NC-SA',
            3 => 'CC BY-NC-ND',
            4 => 'Public Domain'
        );

        if ( count($ccTypes[$cc]) > 0){
            return $ccTypes[$cc];
        }else{
            return "No CC License Found";
        }
    }
}

//Abstracts in Drupal installation had /sites/default/files/learn-1044078_960_720_0.jpg type image links
//In here, I am fixing that and applying Laravel's way of showing images
if (! function_exists('fixImage')) {
    function fixImage($abstract)
    {
        preg_match_all('/src="([^"]*)"/',$abstract,$matches);
        if(count($matches[1])> 0){
            $replaceMe = array();
            $originalMe = array();
            $folders = array('icons');
            for($i=0; $i<count($matches[1]); $i++){
                $absStr = $matches[1][$i];
                $absArray = explode('/',$absStr);
                $imageName = last($absArray);
                $fixedImage = Storage::disk('public')->url($imageName);

                array_push($replaceMe, $fixedImage);
                array_push($originalMe, $absStr);
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