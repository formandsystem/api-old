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

	// global defaults
	public $defaults;

	// global requestPath
	public $requestPath;

	// global client variable
	public $client;

	public function __construct($config, $defaults = [])
	{
		$this->config = $config;
		$this->client = new GuzzleHttp\Client();
		$this->defaults = array_merge(array(
			'language' => 'en'
		),(array) $defaults);
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
		return trim($this->config['url']).'/v'.trim($this->config['version']).'/';
	}

	/**
	 * path
	 *
	 * build path
	 *
	 * @access	public
	 */
	public function getRequestPath( $path, $parameters = [] )
	{
		// prepare parameters
		foreach($parameters as $key => $value)
		{
			$parameters[$key] = $key.'='.$value;
		}

		// set needed parameters if missing
		foreach($this->defaults as $key => $value)
		{
			if( !isset($parameters[$key]) )
			{
				$parameters[$key] = $key.'='.$value;
			}
		}

		return trim($this->getBaseUrl().trim($path).'?'.implode("&",$parameters),'?');
	}

	/**
	* makeRequest
	*
	* @access	public
	*/
	public function makeRequest($type, $path)
	{
		try{
			// create request
			$response = $this->client->$type($path, ['auth' => [$this->config['username'], $this->config['password']]]);
			return $this->handleResponse($response->json());
		}
		catch(GuzzleHttp\Exception\ClientException $e)
		{
			return $this->handleExceptions($e);
		}
	}


	/**
	* handleResponse
	*
	* @access	public
	*/
	public function handleResponse( $response )
	{
		if( isset($response['content'] ) )
		{
			foreach($response['content'] as $item)
			{
				foreach( $item as $field => $value )
				{
					if( is_array(json_decode($value, true)) )
					{
						$item[$field] = json_decode($value, true);
					}
				}
				$result[] = $item;
			}

			$response['content'] = $result;
		}

		return $response;
	}

	/**
	* handleExceptions
	*
	* @access	public
	*/
	public function handleExceptions( $e )
	{
		// get default error message;
		$error = $e->getMessage();
		$errors = array($errors[$e->getCode()] => $e->getMessage()) + json_decode($e->getResponse()->getBody(), true)['errors'];

		return array('success' => "false", 'errors' => $errors);
	}


	/**
	* page
	*
	* @access	public
	*/
	public function page($id, $parameters = [])
	{
		$this->requestPath = $this->getRequestPath('pages/'.$id, $parameters);

		return $this;
	}

	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get()
	{
		return $this->makeRequest('get', $this->requestPath);
	}
	/**
	 * delete
	 *
	 * delete request
	 *
	 * @access	public
	 */
	public function delete( $path, $config = array(), $returnObj = false )
	{
		return $this->call_method('delete', $path, $config, $returnObj);
	}
	/**
	 * post
	 *
	 * post request
	 *
	 * @access	public
	 */
	public function post( $path, $config = array(), $returnObj = false )
	{
		return $this->call_method('post', $path, $config, $returnObj);
	}
	/**
	 * put
	 *
	 * put request
	 *
	 * @access	public
	 */
	public function put( $path, $config = array(), $returnObj = false )
	{
		return $this->call_method('put', $path, $config, $returnObj);
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


}
