<?php
require_once('workflows.php');
$w = new Workflows();

$text = $post['text'] = iconv(mb_detect_encoding($argv[1], mb_detect_order(), true), "UTF-8", $argv[1]);
preg_match_all('/\[([^]]*)\] *\(([^)]*)\)/i', $text,$matches,PREG_OFFSET_CAPTURE);
if(count($matches)){
    $entities = array();
    $entities['links']=array();
    foreach ($matches[0] as $k=>$match) {
        $entities['links'][$k]['pos']=mb_strpos($text,$matches[0][$k][0],0,'UTF-8');
        $text = str_replace($match[0], $matches[1][$k][0], $text);
        $entities['links'][$k]['len']=mb_strlen($matches[1][$k][0],'UTF-8');
        $entities['links'][$k]['url']=$matches[2][$k][0];
    }
    $post['entities']=$entities;
    $post['text']=$text;
}
if(strlen($text)<=256):
    $token =  $w->get( 'token', 'settings.plist' );
    $url='https://alpha-api.app.net/stream/0/posts';
    $headers=array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$token,
        );
    connect(1,$url,$headers,$post);
else:
    echo "Exceeded 256 Characters";
endif;




function connect($type=1,$url,$headers,$query){
        $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$url);
        if($type==1){
            curl_setopt($ch, CURLOPT_POST, 1);
            if(isset($query)){
                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($query));
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $output=json_decode(curl_exec($ch));
        curl_close ($ch);
        if($output->meta->code==200){
            echo 'Posted';
        }else{
            $error = $output->error;
            echo 'Error Posting';        	
        }
    }
