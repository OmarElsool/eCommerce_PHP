<?php

function lang($phrase){
    
    static $lang = array(
        'Home_Admin'    => 'Home',
        'Categories'    => 'Categories',
        'Items'         => 'Items',
        'Members'       => 'Members',
        'Comments'       => 'Comments',
        'Statistics'    => 'Statistics',
        'Logs'          => 'Logs'
    );

    return $lang[$phrase];
}