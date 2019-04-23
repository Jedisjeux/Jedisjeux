<?php

namespace spec\App\Entity;

use App\Entity\CustomerInterface;
use App\Entity\File;
use App\Entity\ProductFile;
use App\Entity\ProductInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductFileSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductFile::class);
    }

    function it_implements_resource_interface(): void
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_extends_a_file_model(): void
    {
        $this->shouldHaveType(File::class);
    }

    function it_has_no_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_initializes_a_code_by_default(): void
    {
        $this->getCode()->shouldNotBeNull();
    }

    function its_code_is_mutable(): void
    {
        $this->setCode('X-files');

        $this->getCode()->shouldReturn('X-files');
    }

    function it_has_no_product_by_default(): void
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product): void
    {
        $this->setProduct($product)->shouldReturn(null);

        $this->getProduct()->shouldReturn($product);
    }

    function it_has_no_author_by_default(): void
    {
        $this->getAuthor()->shouldReturn(null);
    }

    function its_author_is_mutable(CustomerInterface $author): void
    {
        $this->setAuthor($author)->shouldReturn(null);

        $this->getAuthor()->shouldReturn($author);
    }

    function it_has_no_file_by_default(): void
    {
        $this->getFile()->shouldReturn(null);
    }

    function its_file_is_mutable(\SplFileInfo $file): void
    {
        $this->setFile($file);

        $this->getFile()->shouldReturn($file);
        $this->getUpdatedAt()->shouldNotBeNull();
    }

    function it_has_no_title_by_default(): void
    {
        $this->getTitle()->shouldReturn(null);
    }

    function its_title_is_mutable(): void
    {
        $this->setTitle('Awesome file');

        $this->getTitle()->shouldReturn('Awesome file');
    }

    function it_has_no_path_by_default(): void
    {
        $this->getPath()->shouldReturn(null);
    }

    function its_path_is_mutable(): void
    {
        $this->setPath('/path/to/file.txt');

        $this->getPath()->shouldReturn('/path/to/file.txt');
    }

    function it_has_no_creation_date_by_default(): void
    {
        $this->getCreatedAt()->shouldReturn(null);
    }

    function its_creation_date_is_mutable(\DateTime $date): void
    {
        $this->setCreatedAt($date);
        $this->getCreatedAt()->shouldReturn($date);
    }

    function it_has_no_update_date_by_default(): void
    {
        $this->getUpdatedAt()->shouldReturn(null);
    }

    function its_update_date_is_mutable(\DateTime $date): void
    {
        $this->setUpdatedAt($date);
        $this->getUpdatedAt()->shouldReturn($date);
    }
}
