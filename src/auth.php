<?php
require_once('workflows.php');
$w = new Workflows();
$q=$argv;

$q= explode(" ",$argv[1],2);

if(substr($q[0], 0,4)=='user'):
	$username = substr($q[0], 5);
endif;

if(substr($q[1], 0,4)=='pass'):
	$password = substr($q[1], 5);
endif;

if(isset($username) && isset($password) ):
	$url = 'https://account.app.net/oauth/access_token';
	$query='client_id=2CzUFaajaryPuVsUV7GQeNQ9HzkcZRV7&password_grant_secret=e4t3JLjUwMptYvusGvzL4Bp95L3PbGbp&grant_type=password&username='.$username.'&password='.urlencode($password).'&scope='.urlencode('stream write_post follow messages files');
	connect($url,$query,$w);
else:
	echo 'Wrong data, please enter data in the format user:<user> pass:<password>';

endif;







function connect($url,$query,$w){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $output=json_decode(curl_exec($ch));
        curl_close ($ch);
        $error = $output->error;
        if($error!=''){
            echo $error; 
        }else{
            $token = $output->access_token;
        	$w->set( 'token', $token, 'settings.plist' );
            echo 'Alphred Authenticated';       	
        }
    }
