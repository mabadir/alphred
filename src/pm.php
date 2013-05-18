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
	$post['text'] = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
    preg_match_all('/\[([^]]*)\] *\(([^)]*)\)/i', $text,$matches,PREG_OFFSET_CAPTURE);
    if(count($matches[0])>0){
        $entities = array();
        $entities['links']=array();
        $m = 0;
        foreach ($matches[0] as $k=>$match) {
            $entities['links'][$k]['pos']=mb_strpos($text,$matches[0][$k][0],0,'UTF-8');
            $text = str_replace($match[0], $matches[1][$k][0], $text);
            $entities['links'][$k]['len']=mb_strlen($matches[1][$k][0],'UTF-8');
            $entities['links'][$k]['url']=$matches[2][$k][0];
            $m = $k+1;
        }
        $links = processText($text,$token);
        foreach ($links as $link){
            $entities['links'][$m]['pos']=$link->pos;
            $entities['links'][$m]['len']=$link->len;
            $entities['links'][$m]['url']=$link->url;
            $m++;
        }
        $post['entities']=$entities;
        $post['text']=$text;
    }

    if(strlen($text)<=256){
        $post['destinations'] = $users;
    	connect($url,$headers,$post);
    }else{
        echo 'Exceeded 256 Characters';
    }

}else{
	echo 'Reauthorize Alphred using adnauth';
}

function processText($text,$token){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://alpha-api.app.net/stream/0/text/process');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode(array('text'=>$text)));
    $headers=array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$token,
        );
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output=json_decode(curl_exec($ch));
    $output = $output->data;
    curl_close ($ch);
    return($output->entities->links);

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
        print_r($output);
        if($output->meta->code==200){
            echo 'PM sent';
        }else{
        	echo 'There is was an error';
        }
    }