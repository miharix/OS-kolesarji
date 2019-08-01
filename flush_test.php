<?php
include 'cache_glava.php';
	

for($i=1; $i<10; $i++){
    echo $i;

    //this is for the buffer achieve the minimum size in order to flush data
    //echo str_repeat(' ',1024*64);
sleep(1);
    
}


include 'cache_noga.php';

?>