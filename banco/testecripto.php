<?php
$current_dir = dirname(__DIR__, 1);
require_once($current_dir . '/WavesKit-master/vendor/autoload.php');
use deemru\WavesKit;
$wk = new WavesKit();
echo $wk->base58Encode( 'masterstarcoin' );
echo '</br>';
echo $wk->base58Encode( 'masterstarcoin!@345' );
echo '</br>';
echo $wk->base58Encode( 'dtbstarcoin' );

?>