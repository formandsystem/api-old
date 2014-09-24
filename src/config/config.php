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
    'url' => 'http://api.formandsystem.com',
    'version' => 1.0
    'username' => $_ENV['FS_API_USER'],
    'password' => $_ENV['FS_API_PASSWORD'],
  ),

  // set defaults
  'defaults' => array(
    'language' => 'en'
  ),


);
