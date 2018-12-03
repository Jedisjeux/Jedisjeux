<?php

namespace spec\App\Entity;

use App\Entity\AbstractImage;
use App\Entity\Avatar;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Avatar::class);
    }

    function it_extends_abstract_image()
    {
        $this->shouldBeAnInstanceOf(AbstractImage::class);
    }

    function its_upload_dir_is_moved_on_an_avatar_directory()
    {
        $this->setPath('image.jpg');
        $this->getAbsolutePath()->shouldContain('avatar');
    }

    function its_path_is_mutable()
    {
        $this->setPath('image.jpg');
        $this->getPath()->shouldReturn('image.jpg');
    }

    function its_file_is_mutable()
    {
        $file = new UploadedFile(__FILE__, basename(__FILE__));
        $this->setFile($file);
        $this->getFile()->shouldReturn($file);
    }
}
