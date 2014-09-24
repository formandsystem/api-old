<?php

namespace spec\Formandsystem\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiSpec extends ObjectBehavior
{
    public $var = [
      'version' => 'v1'
    ];

    // runs before every test
    function let()
    {
      $this->beConstructedWith(array(
        'url' => 'http://api.formandsystem.com',
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
      $this->getBaseUrl()->shouldReturn('http://api.formandsystem.com/v1/');
    }

    function it_returns_a_valid_request_path()
    {
      // without parameters
      $this->getRequestPath('pages')->shouldReturn('http://api.formandsystem.com/'.$this->var['version'].'/pages?language=en');
      $this->getRequestPath('streams')->shouldReturn('http://api.formandsystem.com/'.$this->var['version'].'/streams?language=en');

      // maximum parameters
      $this->getRequestPath('pages',[
        'format' => 'json',
        'language' => 'de',
        'fields' => 'id,article_id',
        'status' => '1',
        'pathSeparator' => '::'
      ])->shouldReturn('http://api.formandsystem.com/'.$this->var['version'].'/pages?format=json&language=de&fields=id,article_id&status=1&pathSeparator=::');

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
      ])->shouldReturn('http://api.formandsystem.com/'.$this->var['version'].'/streams?format=json&language=de&fields=id,article_id&status=1&limit=20&offset=5&until=2014-07-12&since=2014-02-12&first=false');

    }

    function it_creates_requestPath()
    {
      $this->requestPath->shouldBe(null);
      $this->page('home', ['language' => 'de']);
      $this->requestPath->shouldBe('http://api.formandsystem.com/'.$this->var['version'].'/pages/home?language=de');
    }

    function it_returns_array()
    {
      $response ='{"success":"true","id":1,"article_id":1,"menu_label":"Home","link":"home","status":1,"language":"de","data":[{"class":"section-01","content":[{"type":"default","column":12,"media":[{"src":"banner.jpg","description":"Some optional text"}],"content":"# Kliniken in ganz Deutschland.\nDurch das Copra PMS wird die Arbeite in vielen Kliniken erleichtert.","class":"banner js-banner"}]},{"class":"space-bottom-wide","link":"Vision","content":[{"type":"default","column":12,"content":"#Vision\n>Das Logbuch f\u00fcr jeden Patientenaufenthalt und ein neuer Helfer im behandelnden Team.","class":"space-bottom-wide"},{"type":"default","column":4,"media":[{"src":"icon-connected.svg"}],"content":"##Vernetzt\nCopra erm\u00f6glicht eine leichte Anbindung an viele Drittsysteme, Ger\u00e4te, Exportschnittstellen und Apps.","class":"centered-content padded-column"},{"type":"default","column":4,"media":[{"src":"icon-verfuegbarkeit.svg"}],"content":"##99% verf\u00fcgbar\nEgal ob unterbrochenes Netzwerk, kaputter Server oder das Ger\u00e4t ohne Netzverbindung transportiert wird - f\u00fcr die Dokumentation steht COPRA jederzeit bereit.","class":"centered-content padded-column"},{"type":"default","column":4,"media":[{"src":"icon-customize.svg"}],"content":"##Customizable\nPerfekte Integration in Ihre Prozesse und individuelle Anpassung auf die Vorz\u00fcge eines Hauses.","class":"centered-content padded-column"}]},{"class":"red-section","link":"Anwendungsgebiete","content":[{"type":"default","column":3,"media":[{"src":"icon-doctor.svg"}],"content":"##\u00c4rzte\n- Fachbezogene Unterst\u00fctzung des Verodnungsworkflows\n- Integrierte Interaktionschecks\n- Plausibilit\u00e4tspr\u00fcfung\n- Flexibles Berichtswesen\n- Offlineverf\u00fcgbarkeit des Systems\n\n[Produktdetails](http:\/\/http:\/\/www\/copra\/public\/produkt)","class":"user-features"},{"type":"default","column":3,"media":[{"src":"icon-nurse.svg"}],"content":"##Pflege\n- Fachbezogene Unterst\u00fctzung des Verodnungsworkflows\n- Integrierte Interaktionschecks\n- Plausibilit\u00e4tspr\u00fcfung\n- Flexibles Berichtswesen\n- Offlineverf\u00fcgbarkeit des Systems\n\n[Produktdetails](http:\/\/http:\/\/www\/copra\/public\/produkt)","class":"user-features"},{"type":"default","column":3,"media":[{"src":"icon-management.svg"}],"content":"##Management\n- Fachbezogene Unterst\u00fctzung des Verodnungsworkflows\n- Integrierte Interaktionschecks\n- Plausibilit\u00e4tspr\u00fcfung\n- Flexibles Berichtswesen\n- Offlineverf\u00fcgbarkeit des Systems\n\n[Produktdetails](http:\/\/http:\/\/www\/copra\/public\/produkt)","class":"user-features"},{"type":"default","column":3,"media":[{"src":"icon-it.svg"}],"content":"##IT\n- Fachbezogene Unterst\u00fctzung des Verodnungsworkflows\n- Integrierte Interaktionschecks\n- Plausibilit\u00e4tspr\u00fcfung\n- Flexibles Berichtswesen\n- Offlineverf\u00fcgbarkeit des Systems\n\n[Produktdetails](http:\/\/http:\/\/www\/copra\/public\/produkt)","class":"user-features"}]},{"class":"section-04 section--gray","link":"Neuigkeiten","content":[{"type":"subsection","column":7,"content":[{"type":"default","media":[{"src":"copra-features-teaser.jpg"}],"content":"##Vorteile des Copra Systems\nDer gro\u00dfe Vorteil einer computergest\u00fctzten Dokumentation besteht darin, dass Funktionen genutzt werden, die in\u00a0einer handschriftlichen Dokumentation zu viel Aufwand bedeuten oder schlicht unm\u00f6glich w\u00e4ren.","class":"teaser-card teaser-card--image-right"},{"type":"default","media":[{"src":"copra-features-teaser.jpg"}],"content":"##Integrationsprozess\nDer gro\u00dfe Vorteil einer computergest\u00fctzten Dokumentation besteht darin, dass Funktionen genutzt werden, die in\u00a0einer handschriftlichen Dokumentation zu viel Aufwand bedeuten oder schlicht unm\u00f6glich w\u00e4ren.","class":"teaser-card"}]},{"type":"subsection","column":5,"content":[{"type":"stream","class":"news","stream":"news","mode":"preview"}]}]}],"tags":null,"created_at":"2014-09-23 01:09:12","updated_at":"2014-09-23 01:09:12","deleted_at":null}';

      $this->handleResponse(json_decode($response, true))->shouldBeArray();
      $this->handleResponse(json_decode('{"success":"true","id":1,"article_id":1,"menu_label":"Home","link":"home"}', true))->shouldBeArray();
    }


}
