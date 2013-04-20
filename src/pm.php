<?php

require_once('workflows.php');
$w = new Workflows();


$users = array();
$u = explode(" ",$argv[1],2);
while(true):
	if(substr($u[0],0,1)=='@'):
		$users[]=$u[0];
		$u = explode(" ",$u[1],2);
	else:
		$text = $u[0].' '.$u[1];
		break;
	endif;
endwhile;
$token =  $w->get( 'token', 'settings.plist' );

if($token != ''){
	$headers=array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token,
		);
	$url='https://alpha-api.app.net/stream/0/channels/pm/messages';
	$post['text']=$text;
	preg_match_all('/\[([^]]*)\] *\(([^)]*)\)/i', $text,$matches,PREG_OFFSET_CAPTURE);
    if(count($matches)){
        $entities = array();
        foreach ($matches[0] as $k=>$match) {
            $text = str_replace($match[0], $matches[1][$k][0], $text);
            $entities=array();
            $entities['links']=array();
            $entities['links'][$k]['pos']=$matches[1][$k][1] - 1;
            $entities['links'][$k]['len']=strlen($matches[1][$k][0]);
            $entities['links'][$k]['url']=$matches[2][$k][0];
        }
    }

    if(strlen($text)<=256){
    	$query=array('destinations'=>$users,'text'=>$text,'entities'=>$entities);
    	connect($url,$headers,$query);
    }else{
        echo 'Exceeded 256 Characters';
    }

}else{
	echo 'Reauthorize Alphred using adnauth';
}


function connect($url,$headers,$query){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($query));
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $output=json_decode(curl_exec($ch));
        curl_close ($ch);

        if($output->meta->code==200){
            echo 'PM sent';
        }else{
        	echo 'There is was an error';
        }
    }