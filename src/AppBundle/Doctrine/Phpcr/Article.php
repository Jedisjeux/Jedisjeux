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
class Article extends StaticContent
{
    /**
     * state constants
     */
    const WRITING = "WRITING";
    const NEED_A_REVIEW = "NEED_A_REVIEW";
    const READY_TO_PUBLISH = "READY_TO_PUBLISH";
    const PUBLISHED = "PUBLISHED";

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