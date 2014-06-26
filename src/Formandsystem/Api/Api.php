<?php namespace Lukasoppermann\Optimization;
/*
 * API
 *
 * (c) Lukas Oppermann – vea.re
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @version
 */

use Config;
use Log;
use App;
use URL;

class Api {
	
	// credentials
	public static $config = array(
		'url' => Config::get('package::url'),
		'user' => Config::get('package::user'),
		'password' => Config::get('package::password'),
	);
	
	// global client variable
	public static $client;
	
	public function __construct()
	{
		$this->client = new GuzzleHttp\Client();
	}
	/**
	 * config
	 *
	 * set config 
	 *
	 * @access	public
	 */
	public function config( $config )
	{
		$this->config = array_merge($config, $this->config);
	}
	/**
	 * merge config
	 *
	 * merge config settings
	 *
	 * @access	public
	 */
	public function merge_config( $path, $config )
	{
		$config = array_merge($config, $this->config);
		$config['url'] = (substr($path, 0, 4) == 'http' ? $path : $config['url'].$path;
		
		return $config;
	}
	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get( $path, $config )
	{
		$config = $this->merge_config($path, $config);
		return $client->get(url($path), ['auth' =>  [$config['user'], $config['password']]]);
	}
	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get( $path )
	{
		return $client->get(url('http://www/formandsystem/public/api/v1/stream/navigation.json'), ['auth' =>  ['lukas@vea.re', 'lukas']]);
	}
	
}