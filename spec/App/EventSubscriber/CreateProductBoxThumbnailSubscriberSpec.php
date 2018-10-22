<?php

namespace spec\App\EventSubscriber;

use App\Entity\ProductBox;
use App\Entity\ProductBoxImage;
use App\EventSubscriber\CreateProductBoxThumbnailSubscriber;
use Doctrine\Common\EventSubscriber;
use Imagine\Filter\FilterInterface;
use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateProductBoxThumbnailSubscriberSpec extends ObjectBehavior
{
    function let(
        DataManager $dataManager,
        FilterManager $filterManager,
        CacheManager $cacheManager
    ) {
        $this->beConstructedWith($dataManager, $filterManager, $cacheManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateProductBoxThumbnailSubscriber::class);
    }

    function it_implements_event_subscriber_interface(): void
    {
        $this->shouldImplement(EventSubscriber::class);
    }

    function it_creates_thumbnail(
        DataManager $dataManager,
        BinaryInterface $binary,
        BinaryInterface $filteredBinary,
        FilterManager $filterManager,
        ProductBox $box,
        ProductBoxImage $image

    ): void {
        $imagePath = 'image/path.jpg';
        $box->getImage()->willReturn($image);
        $image->getWebPath()->willReturn($imagePath);
        $box->getHeight()->willReturn(100);
        $dataManager->find('product_box', $imagePath)->willReturn($binary);
        $filterManager->applyFilter($binary, 'product_box', Argument::type('array'))->willReturn($filteredBinary);

        $this->createThumbnail($box);
    }
}
