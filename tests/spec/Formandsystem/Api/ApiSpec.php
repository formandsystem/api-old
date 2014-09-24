<?php

namespace spec\Formandsystem\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiSpec extends ObjectBehavior
{
    public $var = [
      'version' => 'v1',
      'url' => 'http://api.formandsystem.local/',
    ];

    // runs before every test
    function let()
    {
      $this->beConstructedWith(array(
        'url' => 'http://api.formandsystem.local',
        'version' => '1',
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
      $this->getBaseUrl()->shouldReturn($this->var['url'].$this->var['version'].'/');
    }

    function it_creates_requestPath_using_page()
    {
      $this->requestPath->shouldBe(null);
      $this->page('home');
      $this->requestPath->shouldBe($this->var['url'].$this->var['version'].'/pages/home');
    }

    function it_creates_requestPath_using_pages()
    {
      $this->requestPath->shouldBe(null);
      $this->pages('home');
      $this->requestPath->shouldBe($this->var['url'].$this->var['version'].'/pages/home');
    }

    function it_creates_requestPath_using_stream()
    {
      $this->requestPath->shouldBe(null);
      $this->stream('news');
      $this->requestPath->shouldBe($this->var['url'].$this->var['version'].'/streams/news');
    }

    function it_creates_requestPath_using_streams()
    {
      $this->requestPath->shouldBe(null);
      $this->stream('news');
      $this->requestPath->shouldBe($this->var['url'].$this->var['version'].'/streams/news');
    }

    function it_should_run_a_valid_page_get_request()
    {
      $this->pages('home');
      $this->createRequest('get', ['language' => 'en']);
      $this->request->getUrl()->shouldBe($this->var['url'].$this->var['version'].'/pages/home?language=en');
      $this->request->getMethod()->shouldBe('GET');
    }

    function it_should_run_a_valid_stream_get_request()
    {
      $this->stream('news');
      $this->createRequest('get', ['language' => 'de']);
      $this->request->getUrl()->shouldBe($this->var['url'].$this->var['version'].'/streams/news?language=de');
      $this->request->getMethod()->shouldBe('GET');
    }

    function it_should_run_a_valid_delete_request()
    {
      $this->pages('1');
      $this->createRequest('delete');
      $this->request->getUrl()->shouldBe($this->var['url'].$this->var['version'].'/pages/1');
      $this->request->getMethod()->shouldBe('DELETE');
    }

    function it_should_run_a_valid_post_request()
    {
      $this->pages('1');
      $this->createRequest('post', ['status' => 2, 'tags' => 'some, new, tags']);
      $this->request->getUrl()->shouldBe($this->var['url'].$this->var['version'].'/pages/1');
      $this->request->getMethod()->shouldBe('POST');
      $this->request->getBody()->getFields()->shouldHaveCount(2);
      $this->request->getBody()->getFields()->shouldHaveKey('status');
      $this->request->getBody()->getFields()->shouldHaveKey('tags');
    }

    function it_should_run_a_valid_put_request()
    {
      $this->pages('1');
      $this->createRequest('put', ['status' => 2, 'tags' => 'some, new, tags']);
      $this->request->getUrl()->shouldBe($this->var['url'].$this->var['version'].'/pages/1');
      $this->request->getMethod()->shouldBe('PUT');
      $this->request->getBody()->getFields()->shouldHaveCount(2);
      $this->request->getBody()->getFields()->shouldHaveKey('status');
      $this->request->getBody()->getFields()->shouldHaveKey('tags');
    }

    function it_returns_array()
    {
      $response ='{
        "success": "true",
        "id": 1,
        "article_id": 1,
        "menu_label": "Home",
        "link": "home",
        "status": 1,
        "language": "de",
        "data": [
            {
                "class": "section-01",
                "content": [
                    {
                        "type": "default",
                        "column": 12,
                        "media": [
                            {
                                "src": "banner.jpg",
                                "description": "Some optional text"
                            }
                        ],
                        "content": "# Kliniken in ganz Deutschland.\nDurch das Copra PMS wird die Arbeite in vielen Kliniken erleichtert.",
                        "class": "banner js-banner"
                    }
                ]
            }
        ],
        "tags": null,
        "created_at": "2014-09-23 01:09:12",
        "updated_at": "2014-09-23 01:09:12",
        "deleted_at": null
    }';

      $this->handleResponse(json_decode($response, true))->shouldBeArray();
      $this->handleResponse(json_decode('{"success":"true","id":1,"article_id":1,"menu_label":"Home","link":"home"}', true))->shouldBeArray();
    }


}
