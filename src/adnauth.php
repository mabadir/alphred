<?php

require('workflows.php');
$w = new Workflows();

$w->result('authenticate', $argv[1], 'Authenticate Alphred', 'Alphred will be able to: View Stream, Write Posts, Follow Users, Send Messages', 'icon.png', 'yes', 'Alfredapp' );
	echo $w->toxml();