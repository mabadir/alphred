<?php
require_once('workflows.php');
$w = new Workflows();
$q=$argv;
$correct = 0;
$wrong = 0;
$q= explode(" ",$argv[1]);
foreach ($q as $user):
    if(substr($user,0,1)=='@'):
        echo $user;
        $token =  $w->get( 'token', 'settings.plist' );
        $headers=array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token,
            );
        echo $url='https://alpha-api.app.net/stream/0/users/'.$user.'/follow';
        if(connect($url,$headers)):
            $correct++;
        else:
            $wrong++;
        endif;
    else:
        $wrong++;
    endif;
endforeach;

$printer='';

if($correct):
    $printer = 'Followed: '.$correct.' user';
endif;

if($wrong):
    if(strlen($printer)>0):
        $printer .=  ' & Found: '.$wrong.' wrong entries';
    else:
        $printer =  'Found: '.$wrong.' wrong entries';
    endif;
endif;

echo $printer;






function connect($url,$headers){
        $ch = curl_init();
        echo 'Hi';
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $query=array();
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($query));
        $output=json_decode(curl_exec($ch));
        curl_close ($ch);
        if($output->meta->code==200){
            return TRUE;
        }else{
        	return FALSE;
        }
    }
