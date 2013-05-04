<?php

require('workflows.php');
$w = new Workflows();
date_default_timezone_set("Europe/Helsinki");
$token =  $w->get( 'token', 'settings.plist' );
    $url='https://alpha-api.app.net/stream/0/users/me/files?include_file_annotations=1&count=5';
    $headers=array(
        'Authorization: Bearer '.$token,
        );
$output = connect($url,$headers);
$files = $output->data;
foreach ($files as $file){
    if(strtolower($file->kind)=='image'){
        $url='[Image](https://photos.app.net/{post_id}/1)';
    }else{
        $url='';
    }
    $icon='icon.png';
    $date = strtotime($file->created_at);
    $fixed = date('Y-m-d - g:ia', $date);
    $w->result('file', 'file:'.$file->id .' token:'.$file->file_token.' '.$argv[1].' '.$url, $file->name, 'Type: '.$file->kind.', Uploaded: '.$fixed, $icon, 'yes', 'Alfredapp' );
}
	echo $w->toxml();



function connect($url,$headers){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output=json_decode(curl_exec($ch));
    curl_close ($ch);
    if($output->meta->code==200){
        return $output;
    }else{
        $error = $output->error;
        echo 'Error Posting';        	
    }
}