<?php namespace Formandsystem\Api;
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

use GuzzleHttp;
use Exception;

class Api {

	// global config
	public $config;

	// global requestPath
	public $requestPath;

	// global request
	public $request;

	// global client variable
	public $client;

	// global cache variable
	public $cache;

	public function __construct($config, $cache)
	{
		$this->config = $config;
		$this->client = new GuzzleHttp\Client();
		$this->cache = $cache;
	}


	/**
	* getBaseUrl
	*
	* return base url
	*
	* @access	public
	*/
	public function getBaseUrl()
	{
		return trim($this->config['url']).'/v'.trim(trim($this->config['version']), 'v').'/';
	}

	/**
	 * path
	 *
	 * build path
	 *
	 * @access	public
	 */
	public function setRequestPath( $path, $parameters = [] )
	{
		$this->requestPath = $this->getBaseUrl().trim($path);
	}

	/**
	* get path
	*
	* get path
	*
	* @access	public
	*/
	public function getRequestPath( )
	{
		return $this->requestPath;
	}

	/**
	* createRequest
	*
	* @access	public
	*/
	public function createRequest($type, $requestPath, $parameters = [],
			$headers = array('Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json')
	)
	{
		$type = strtoupper($type);

		if( $type === 'GET' )
		{
			$params['query'] = $parameters;
		}
		else
		{
			$params['body'] = $parameters;
		}

		$options = array_merge([
			'headers' => $headers,
			"debug" => false
		], $params);

		// make request
		$this->request = $this->client->createRequest($type, $requestPath, $options);

		return $this;
	}


	/**
	* sendRequest
	*
	* @access	public
	*/
	public function sendRequest()
	{
		// @TODO: clean up
		if($this->request->getMethod() == 'GET')
		{
			$key = sha1($this->request->getUrl());
			if( $this->cache->has($key) )
			{
				return $this->cache->get($key);
			}
		}

		try{
			$request = $this->client->send($this->request)->json();

			if($this->request->getMethod() == 'GET')
			{
				$this->cache->forever($key, $request);
				$keys = $this->cache->get('cache.fsapi');
				$keys[] = $key;
				$this->cache->forever('cache.fsapi', $keys);
			}

			return $request;

		}
		catch(GuzzleHttp\Exception\ClientException $e)
		{
			return $this->handleExceptions($e);
		}
	}


	/**
	* makeRequest
	*
	* @access	public
	*/
	public function makeRequest($type, $parameters = [])
	{
		return $this->createRequest($type, $this->getRequestPath(), $this->makeParameters($parameters))->sendRequest();
	}

	/**
	* makeParameters
	*
	* @access	public
	*/
	public function makeParameters($parameters = [])
	{
		return array_merge($parameters, $this->accessToken());
	}

	/**
	* accessToken
	*
	* @access	public
	*/
	public function accessToken()
	{
		if ( ! $this->cache->has('access_token') )
		{
			$tokenRequest = $this->createRequest(
				'POST',
				$this->getBaseUrl().'oauth/access_token',
				[
					"grant_type" 		=> "client_credentials",
					"client_id"  		=>	$this->config['client_id'],
					"client_secret"	=>	$this->config['client_secret'],
					"scope"					=>	$this->config['scope'],
			])->sendRequest();
			// cache access token
			$this->cache->put('access_token', $tokenRequest['access_token'], ((int) $tokenRequest['expires_in']-60)/60);
		}


		return ['access_token' => $this->cache->get('access_token')];
	}

	/**
	* handleExceptions
	*
	* @access	public
	*/
	public function handleExceptions( $e )
	{
		if( $e->getResponse() )
		{
			return json_decode($e->getResponse());
		}
		return $e->getStatusCode().': '.$e->getMessage();
	}


	/**
	* page
	*
	* @access	public
	*/
	public function page($id = "")
	{
		$this->setRequestPath('pages/'.$id);

		return $this;
	}
	public function pages($id = "")
	{
		return $this->page($id);
	}


	/**
	* stream
	*
	* @access	public
	*/
	public function stream($id = "")
	{
		$this->setRequestPath('streams/'.$id);

		return $this;
	}
	public function streams($id, $parameters = "")
	{
		return $this->stream($id, $parameters);
	}


	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get($parameters = [])
	{
		return $this->makeRequest('get', $parameters);
	}
	/**
	 * delete
	 *
	 * delete request
	 *
	 * @access	public
	 */
	public function delete()
	{
		return $this->makeRequest('delete');
	}
	/**
	 * post
	 *
	 * post request
	 *
	 * @access	public
	 */
	public function post($parameters = [])
	{
		return $this->makeRequest('post', $parameters);
	}
	public function create($parameters = [])
	{
		return $this->post($parameters);
	}
	/**
	 * put
	 *
	 * put request
	 *
	 * @access	public
	 */
	public function put($parameters = [])
	{
		return $this->makeRequest('put', $parameters);
	}
	public function edit($parameters = [])
	{
		return $this->put($parameters);
	}
	/**
	 * client
	 *
	 * get guzzle client
	 *
	 * @access	public
	 */
	public function client()
	{
		return $this->client;
	}

	/**
	 * clear cache
	 *
	 * @method clearCache
	 *
	 * @param  [type]     $key
	 */
	public function clearCache( $key )
	{
		foreach($this->cache->get('cache.fsapi', []) as $id => $key)
		{
			$this->cache->forget($key);
		}
		$this->cache->forget('cache.fsapi');
		
		\Event::fire('cache.cleared', array($key));
	}
}
