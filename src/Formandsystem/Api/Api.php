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

	// global path
	public $path;

	// global client variable
	public $client;

	public function __construct($config)
	{
		$this->config = $config;
		$this->client = new GuzzleHttp\Client();
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
		return trim($this->config['url']).'/'.trim($this->config['version']).'/';
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
		$errors = json_decode($e->getResponse()->getBody(), true);
		$error = "";
		if( is_array($errors) )
		{
			// cast errors to string
			foreach( $errors['errors'] as $key => $arr )
			{
				$error .= "[".$key."]: ".implode(" ",$arr).' ';
			}
		}
		return array('success' => "false", $e->getCode() => $error );
	}


	/**
	 * call_method
	 *
	 * call_method request
	 *
	 * @access	public
	 */
	public function call_method($fn, $path = null, $config = array(), $returnObj = false)
	{
		try{
			$req = $this->client->$fn(url($this->path($path)), array_merge((array)$this->config, (array)$config) );

			if($returnObj !== true)
			{
				$response = $req->json();

				if( isset($response['content'] ) )
				{
					foreach($response['content'] as $item)
					{
						$item['data'] = json_decode($item['data'], true);
						$result[] = $item;
					}

					$response['content'] = $result;
				}

				return $response;
			}
			return $req;
		}
		catch(GuzzleHttp\Exception\ClientException $e)
		{
			if($e->getCode() == 401)
			{
				return array('success' => "false", $e->getCode() => 'Wrong credentials for Api call to '.$this->path($path));
			}
			elseif( $e->getCode() == 400 )
			{
				// cast errors to string
				$errors = json_decode($e->getResponse()->getBody(), true);
				$error = "";
				foreach( $errors['errors'] as $key => $arr )
				{
					$error .= "[".$key."]: ".implode(" ",$arr).' ';
				}
				return array('success' => "false", $e->getCode() => $error );
			}
			elseif( $e->getCode() == 404 )
			{
				return array('success' => "false", $e->getCode() => 'Page not found: '.$this->path($path));
			}

		}
	}
	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get( $path = null, $config = array(), $returnObj = false )
	{
		return $this->call_method('get', $path, $config, $returnObj);
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
