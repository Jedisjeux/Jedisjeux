<?php

namespace spec\App\Factory;

use App\Factory\BggProductFactory;
use App\Entity\BggProduct;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BggProductFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(BggProduct::class, 'https://boardgamegeek.com/xmlapi/boardgame');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BggProductFactory::class);
    }

    function it_can_create_bgg_product_by_path()
    {
        // The BGG XML API now requires an authorization token
        throw new SkippingException('Requires access to the BoardGameGeek API');

        $bggProduct = $this->createByPath('https://boardgamegeek.com/boardgame/3076/puerto-rico');

        $bggProduct->shouldHaveType(BggProduct::class);
        $bggProduct->getName()->shouldReturn('Puerto Rico');
        $bggProduct->getArtists()->shouldNotHaveCount(0);
        $bggProduct->getDesigners()->shouldNotHaveCount(0);
        $bggProduct->getArtists()->shouldNotHaveCount(0);
        $bggProduct->getMechanisms()->shouldNotHaveCount(0);
    }

    function it_creates_bgg_product_from_bookmarklet_payload()
    {
        $payload = rtrim(strtr(base64_encode(gzdeflate(json_encode([
            'name' => 'Puerto Rico',
            'releasedAtYear' => 2002,
            'minDuration' => '90',
            'maxDuration' => '150',
            'age' => 12,
            'minPlayerCount' => 3,
            'maxPlayerCount' => 5,
            'description' => 'In &quot;Puerto Rico&quot;,<br/>players&#10;assume roles.',
            'imagePath' => 'https://cf.geekdo-images.com/abc__original/img/def=/0x0/filters:format(jpeg)/pic158548.jpg',
            'mechanisms' => ['Variable Phase Order'],
            'designers' => ['Andreas Seyfarth'],
            'artists' => ['Harald Lieske', 'Franz Vohwinkel'],
            'publishers' => ['alea'],
        ]))), '+/', '-_'), '=');

        $bggProduct = $this->createFromPayload($payload);

        $bggProduct->getName()->shouldReturn('Puerto Rico');
        $bggProduct->getReleasedAtYear()->shouldReturn('2002');
        $bggProduct->getMinDuration()->shouldReturn('90');
        $bggProduct->getMaxDuration()->shouldReturn('150');
        $bggProduct->getAge()->shouldReturn('12');
        $bggProduct->getMinPlayerCount()->shouldReturn('3');
        $bggProduct->getMaxPlayerCount()->shouldReturn('5');
        $bggProduct->getDescription()->shouldReturn("In \"Puerto Rico\",\nplayers\nassume roles.");
        $bggProduct->getImagePath()->shouldReturn('https://cf.geekdo-images.com/abc__original/img/def=/0x0/filters:format(jpeg)/pic158548.jpg');
        $bggProduct->getMechanisms()->shouldReturn(['Variable Phase Order']);
        $bggProduct->getDesigners()->shouldReturn(['Andreas Seyfarth']);
        $bggProduct->getArtists()->shouldReturn(['Harald Lieske', 'Franz Vohwinkel']);
        $bggProduct->getPublishers()->shouldReturn(['alea']);
    }

    function it_ignores_images_not_hosted_by_bgg()
    {
        $payload = base64_encode(gzdeflate(json_encode([
            'name' => 'Puerto Rico',
            'imagePath' => 'http://localhost/secret.jpg',
        ])));

        $this->createFromPayload($payload)->getImagePath()->shouldReturn(null);
    }

    function it_throws_an_exception_when_payload_is_invalid()
    {
        $this->shouldThrow(BadRequestHttpException::class)->during('createFromPayload', ['not a payload']);
    }
}
