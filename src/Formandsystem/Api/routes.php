<?php namespace Formandsystem\Api;

\Route::get('fsclearcache/{key?}', function($key){
  return \Api::clearCache($key);
});
