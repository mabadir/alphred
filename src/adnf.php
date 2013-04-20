<?php
require('workflows.php');
$w = new Workflows();

$w->result('follow', $argv[1], 'Alphred', 'Press Enter to Follow user '.$argv[1], 'icon.png', 'yes', 'Alfredapp' );
echo $w->toxml();