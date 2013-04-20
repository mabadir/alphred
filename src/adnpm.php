<?php
require('workflows.php');
$w = new Workflows();

$users = '';

$u = explode(" ",$argv[1],2);
while(true):
	if(substr($u[0],0,1)=='@'):
		$users.=' '.$u[0];
		$u = explode(" ",$u[1],2);
	else:
		$text = $u[0].' '.$u[1];
		break;
	endif;
endwhile;
preg_match_all('/\[([^]]*)\] *\(([^)]*)\)/i', $text,$matches,PREG_OFFSET_CAPTURE);
if(count($matches)){
    foreach ($matches[0] as $k=>$match) {
        $text = str_replace($match[0], $matches[1][$k][0], $text);
    }
}
$remaining = 256 - strlen(trim($text));

$w->result('pm', $argv[1], 'Alphred', 'Remaining: '.$remaining.' Press Enter to PM users'.$users, 'icon.png', 'yes', 'Alfredapp' );
echo $w->toxml();