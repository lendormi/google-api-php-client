<?php
/**
 * @author Dany RALANTONISAINANA <lendormi1984@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version 1.0.0
 */

$Module = array( "name" => "googleanalytics" );

$ViewList = array();
$ViewList["example"] = array(
    "script" => "example.php",
    'unordered_params'  => array( 'profile_id' => 'profile_id')
);
$ViewList["example_realtime"] = array(
    "script" => "example_realtime.php",
    'unordered_params'  => array( 'profile_id' => 'profile_id')
);
