<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\App\Entity;

use App\Entity\Article;
use App\Entity\Block;
use App\Entity\BlockImage;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class BlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Block::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_code_is_mutable()
    {
        $this->setCode('Awesome_Code');

        $this->getCode()->shouldReturn('Awesome_Code');
    }

    function it_has_no_title_by_default()
    {
        $this->getTitle()->shouldReturn(null);
    }

    function its_title_is_mutable()
    {
        $this->setTitle('Awesome title');

        $this->getTitle()->shouldReturn('Awesome title');
    }

    function it_has_no_body_by_default()
    {
        $this->getBody()->shouldReturn(null);
    }

    function its_body_is_mutable()
    {
        $this->setBody('<p>Subject body</p>');

        $this->getBody()->shouldReturn('<p>Subject body</p>');
    }

    function it_has_no_image_position_by_default()
    {
        $this->getImagePosition()->shouldReturn(null);
    }

    function its_image_position_is_mutable()
    {
        $this->setImagePosition(Block::POSITION_LEFT);

        $this->getImagePosition()->shouldReturn(Block::POSITION_LEFT);
    }

    function it_has_no_class_by_default()
    {
        $this->getClass()->shouldReturn(null);
    }

    function its_class_is_mutable()
    {
        $this->setClass("well");

        $this->getClass()->shouldReturn("well");
    }

    function it_has_no_image_by_default()
    {
        $this->getImage()->shouldReturn(null);
    }

    function its_image_is_mutable(BlockImage $image)
    {
        $this->setImage($image);

        $this->getImage()->shouldReturn($image);
    }

    function it_has_no_article_by_default()
    {
        $this->getArticle()->shouldReturn(null);
    }

    function its_article_is_mutable(Article $article)
    {
        $this->setArticle($article);

        $this->getArticle()->shouldReturn($article);
    }
}
