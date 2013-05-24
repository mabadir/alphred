<?php

require('workflows.php');
$w = new Workflows();
date_default_timezone_set("Europe/Helsinki");
$token =  $w->get( 'token', 'settings.plist' );
$url='https://alpha-api.app.net/stream/0/users/me/mentions?include_deleted=0';
$headers=array(
	'Authorization: Bearer '.$token,
);
$output = connect($url,$headers);
$posts = $output->data;
foreach ($posts as $post){
	$arg = $argv[1];
	$source = $post->source;
	$icon='icon.png';
	$date = strtotime($post->created_at);
	$fixed = date('Y-m-d - g:ia', $date);
	$random = rand();
	$w->result('unifiedstream-'.$post->id. '-' .$random, $post->id.' '.$arg, $post->text, "Created " .$fixed. " with " .$source->name, $icon, 'yes', 'Alfredapp' );
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
		echo 'Error Retrieving Posts. Error Code' .$error;
	}
}