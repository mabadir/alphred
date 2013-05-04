<?php
require_once('workflows.php');
$w = new Workflows();

$file = $argv[1];

if(file_exists($file)){
    $token =  $w->get( 'token', 'settings.plist' );
    $url='https://alpha-api.app.net/stream/0/files';
    $headers=array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$token,
        );
    $post['name']=basename($file);
    $post['type']='co.bizmascot.alphred.file';
    $post['public']='false';
    if(substr(mime_content_type($file),0,5)=='image'){
        $post['kind']='image';
    }else{
        $post['kind']='other';
    }
    $id = connect($url,$headers,$post);
    if($id){
        if(put_file($id,$file,$token)){
            echo get_file($id,$token);
        }else{
            echo 'Error Uploading file';
        }
    }else{
        echo 'Error Uploading file';
    }
}else{
    echo "Incorrect File";
    }


function connect($url,$headers,$query){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($query));
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $file_output=json_decode(curl_exec($ch));
        curl_close ($ch);

        if($file_output->meta->code==200){
            return $file_output->data->id;
        }else{
            return FALSE;
        }
    }

function put_file($id,$file,$token){

            $ch = curl_init();
            $url = 'https://alpha-api.app.net/stream/0/files/'.$id.'/content';
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_PUT, 1);
            $fp = fopen ($file, "r"); 
            $headers=array(
            'Content-Type: '.mime_content_type($file),
            'Authorization: Bearer '.$token,
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            curl_setopt($ch, CURLOPT_INFILE, $fp); 
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file)); 
            $output = curl_exec($ch); 
            $error = curl_error($ch); 
            $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE); 
            curl_close($ch); 
            fclose($fp);
            if(!$error){
                return TRUE;
            }else{
                return FALSE;
            }
}

function get_file($id,$token){
    $ch = curl_init();
    $url='https://alpha-api.app.net/stream/0/files/'.$id;
    curl_setopt($ch, CURLOPT_URL,$url);
    $headers=array(
    'Authorization: Bearer '.$token,
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $file_output=json_decode(curl_exec($ch));
    curl_close ($ch);
    if($file_output->meta->code==200){
        return 'File Uploaded';
    }else{
        return 'Error retrieving the file URL';
    }

}
