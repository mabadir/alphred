<?php
require_once('workflows.php');
$w = new Workflows();
$q=$argv;

$q= explode(" ",$argv[1],2);
$text = $post['text']=$argv[1];
preg_match_all('/\[([^]]*)\] *\(([^)]*)\)/i', $text,$matches,PREG_OFFSET_CAPTURE);
if(count($matches)){
    $entities = array();
    foreach ($matches[0] as $k=>$match) {
        $text = str_replace($match[0], $matches[1][$k][0], $text);
        $entities=array();
        $entities['links']=array();
        $entities['links'][$k]['pos']=$matches[1][$k][1] - 1;
        $entities['links'][$k]['len']=strlen(utf8_decode($matches[1][$k][0]));
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
