<?php

namespace spec\Formandsystem\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiSpec extends ObjectBehavior
{
    // runs before every test
    function let()
    {
      $this->beConstructedWith(array(
        'url' => 'http://api.formandsystem.com',
        'version' => 'v1.0',
        'username' => 'lukas@vea.re',
        'password' => 'lukas',
      ));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystem\Api\Api');
    }

    function it_returns_the_base_url()
    {
      $this->getBaseUrl()->shouldReturn('http://api.formandsystem.com/v1.0/');
    }

    function it_returns_a_valid_request_path()
    {
      // without parameters
      $this->getRequestPath('pages')->shouldReturn('http://api.formandsystem.com/v1.0/pages');
      $this->getRequestPath('streams')->shouldReturn('http://api.formandsystem.com/v1.0/streams');

      // maximum parameters
      $this->getRequestPath('pages',[
        'format' => 'json',
        'language' => 'de',
        'fields' => 'id,article_id',
        'status' => '1',
        'pathSeparator' => '::'
      ])->shouldReturn('http://api.formandsystem.com/v1.0/pages?format=json&language=de&fields=id,article_id&status=1&pathSeparator=::');

      $this->getRequestPath('streams',[
        'format' => 'json',
        'language' => 'de',
        'fields' => 'id,article_id',
        'status' => 1,
        'limit' => 20,
        'offset' => 5,
        'until' => '2014-07-12',
        'since' => '2014-02-12',
        'first' => 'false'
      ])->shouldReturn('http://api.formandsystem.com/v1.0/streams?format=json&language=de&fields=id,article_id&status=1&limit=20&offset=5&until=2014-07-12&since=2014-02-12&first=false');

    }

    function it_does_a_valid_request()
    {
      $this->makeRequest('get', $this->getRequestPath('pages', ['language' => 'de']))->shouldHaveType('\GuzzleHttp\Message\ResponseInterface');
    }

}
