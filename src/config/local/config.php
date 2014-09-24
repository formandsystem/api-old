<?php

return array(

  /*
  |--------------------------------------------------------------------------
  | Api Config
  |--------------------------------------------------------------------------
  |
  | configuration for the api
  |
  */

  'api' => array(
    'url' => 'http://api.formandsystem.local',
    'version' => '1',
    'username' => $_ENV['FS_API_USER'],
    'password' => $_ENV['FS_API_PASSWORD'],
  ),

  // set defaults
  'defaults' => array(
    'language' => 'en'
  ),


);
