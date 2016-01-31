<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 20/01/2016
 * Time: 13:45
 */

namespace AppBundle\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContent extends StaticContent
{
    /**
     * state constants
     */
    const WRITING = "writing";
    const NEED_A_REVIEW = "need_a_review";
    const READY_TO_PUBLISH = "ready_to_publish";
    const PUBLISHED = "published";

    /**
     * @var string
     */
    protected $state = null;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->state = self::WRITING;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }
}