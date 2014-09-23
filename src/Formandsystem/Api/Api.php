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

	// global requestPath
	public $requestOptions;

	// global request
	public $request;

	// global client variable
	public $client;

	public function __construct($config, $defaults = [])
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
		return trim($this->config['url']).'/v'.trim($this->config['version']).'/';
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
	* createRequest
	*
	* @access	public
	*/
	public function createRequest($type, $parameters = [])
	{
		// preapre response options array
		$requestOptions['auth'] = [ $this->config['username'], $this->config['password'] ];
		// if type == get, use parameters
		$type === 'get' ? $requestOptions['query'] = $parameters : '';
		// if type == get, use parameters
		$type === 'put' || $type === 'post' ? $requestOptions['body'] = $parameters : '';
		// make request
		$this->request = $this->client->createRequest($type, $this->requestPath, $requestOptions);

		return $this;
	}


	/**
	* sendRequest
	*
	* @access	public
	*/
	public function sendRequest()
	{
		try{
			return $this->handleResponse($this->client->send($this->request)->json());
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
		return $this->createRequest($type, $parameters)->sendRequest();
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
		$errors = array($e->getCode() => [$e->getMessage()]) + json_decode($e->getResponse()->getBody(), true)['errors'];

		return array('success' => "false", 'errors' => $errors);
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


}
