<?php
require('workflows.php');
$w = new Workflows();


$text = $argv[1];
preg_match_all('/\[([^]]*)\] *\(([^)]*)\)/i', $text,$matches,PREG_OFFSET_CAPTURE);
if(count($matches)){
    foreach ($matches[0] as $k=>$match) {
        $text = str_replace($match[0], $matches[1][$k][0], $text);
    }
}
$remaining = 256 - strlen(trim($text));
if($remaining>=0):
	$w->result('post', $argv[1], 'Alphred', 'Remaining: '.$remaining.'. Press Enter to post to App.net', 'icon.png', 'yes', 'Alfredapp' );
else:
	$w->result('error', '', 'Alphred', 'Exceeded 256 characters', 'icon.png', 'yes', 'Alfredapp' );
endif;
echo $w->toxml();


