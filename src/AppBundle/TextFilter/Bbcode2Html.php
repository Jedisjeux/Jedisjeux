<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/02/2016
 * Time: 12:46
 */

namespace AppBundle\TextFilter;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Model\ProductVariantInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class Bbcode2Html
{
    /**
     * @var string
     */
    protected $body;

    /**
     * @var Connection
     */
    protected $databaseConnection;

    /**
     * @var EntityRepository
     */
    protected $productVariantRepository;

    /**
     * Bbcode2Html constructor.
     *
     * @param Connection $databaseConnection
     * @param EntityRepository $productVariantRepository
     */
    public function __construct(Connection $databaseConnection, EntityRepository $productVariantRepository)
    {
        $this->databaseConnection = $databaseConnection;
        $this->productVariantRepository = $productVariantRepository;
    }

    /**
     * @return string|null
     */
    public function getFilteredBody()
    {
        $body = $this->body;

        if (null === $body) {
            return null;
        }

        $body = $this->nl2p($body);
        $body = $this->emoticonReplacement($body);
        $body = $this->colorReplacement($body);
        $body = $this->sizeReplacement($body);
        $body = $this->blockReplacement($body);
        $body = $this->quoteReplacement($body);
        $body = $this->imageReplacement($body);
        $body = $this->imageWithIdReplacement($body);
        $body = $this->gameWithIdReplacement($body);
        $body = $this->urlReplacement($body);
        $body = $this->boldReplacement($body);
        $body = $this->italicReplacement($body);
        $body = $this->underlineReplacement($body);
        $body = $this->centerReplacement($body);
        $body = $this->listReplacement($body);
        $body = $this->emptyTagsReplacement($body);

        return $body;
    }

    protected function nl2p($body)
    {
        return "<p>" . str_replace("\n", "</p><p>", trim($body)) . "</p>";
    }

    protected function emptyTagsReplacement($body)
    {
        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function emoticonReplacement($body)
    {
        $pattern = '/\<img src\=\"\{SMILIES_PATH\}\/icon_(.*?)\.gif\" alt\=\"(?P<alt>.*?)\" title\=\"(.*?)\" \/\>/ms';
        $replacement = "$2";
        $body = preg_replace($pattern, $replacement, $body);

        $body = str_replace(':mrgreen:', 'xD', $body);
        $body = str_replace(':wink:', ';)', $body);
        $body = str_replace(':lol:', ':)', $body);

        // star wars emoticons
        $pattern = '/<img src="{SMILIES_PATH}\/(.*?)\.gif" alt="(:light:|:dark:|:boba:|:d2:)" title="(.*?)" \/>/ms';
        $body = preg_replace($pattern, '', $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function quoteReplacement($body)
    {
        $pattern = '/\[quote\=&quot;(?P<title>.*?)&quot;:(.*?)\](?P<body>.*)\[\/quote:(.*?)\]/ms';
        $replacement = <<<EOM
</p>
<div class="quote-one">
    <div class="row">
        <!-- Quote One Item -->
        <div class="quote-one-item">
            <span class="color">“</span>
            <div class="quote-one-right">
                <p>$3</p>
                - $1
            </div>
        </div>
    </div>
</div>
 <p>
EOM;
        $body = preg_replace($pattern, trim($replacement), $body);

        // remove extra quotes
        $body = preg_replace($pattern, "", $body);

        $pattern = '/\[quote:(.*?)\](?P<body>.*?)\[\/quote:(.*?)\]/ms';
        $replacement = <<<EOM
</p>
<div class="quote-one">
    <div class="row">
        <!-- Quote One Item -->
        <div class="quote-one-item">
            <span class="color">“</span>
            <div class="quote-one-right">
                <p>$2</p>
            </div>
        </div>
    </div>
</div>
 <p>
EOM;
        $body = preg_replace($pattern, trim($replacement), $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function imageReplacement($body)
    {
        $pattern = '/\[img:(.*?)\](?P<path>.*?)\[\/img:(.*?)\]/ms';
        $replacement = "<img src=\"$2\" class=\"img-responsive\" />";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     *
     * @return string
     */
    protected function imageWithIdReplacement($body)
    {

        $pattern = '/\[image\=?(?P<properties>.*?):(.*?)\\](?P<id>.*?)\[\/image:(.*?)\\]/ms';
        preg_match_all($pattern, $body, $matches);

        $replacement = "<div class=\"IMAGE-CLASS-$3\"><img src=\"$1-IMAGE-REPLACEMENT-$3\" class=\"img-responsive\" /></div>";
        $body = preg_replace($pattern, $replacement, $body);

        foreach ($matches['id'] as $key => $id) {
            $imageName = $this->getImageNameById($id);
            $properties = $matches['properties'][$key];

            $data = false !== !empty($properties) ? explode(',', $properties) : [];
            $size = isset($data[0]) ? $data[0] : null;
            $position = isset($data[1]) ? $data[1] : null;

            if ('left' === $position) {
                $body = str_replace(sprintf('IMAGE-CLASS-%s', $id), 'pull-left col-md-6 no-padding-left', $body);
            } elseif ('right' === $position) {
                $body = str_replace(sprintf('IMAGE-CLASS-%s', $id), 'pull-right col-md-6 no-padding-right', $body);
            } else {
                $body = str_replace(sprintf('IMAGE-CLASS-%s', $id), '', $body);
            }

            $imagePath = $this->getImageOriginalPath($imageName, $size);
            $body = str_replace(sprintf('%s-IMAGE-REPLACEMENT-%s', $properties, $id), $imagePath, $body);
        }

        return $body;
    }

    /**
     * @param int $id
     *
     * @return string
     */
    protected function getImageNameById($id)
    {
        $query = sprintf('select img_nom from jedisjeux.jdj_images WHERE img_id = %s', $id);

        return $this->databaseConnection->fetchColumn($query);
    }

    /**
     * @param string $body
     *
     * @return string
     */
    protected function gameWithIdReplacement($body)
    {

        $pattern = '/\[jeu:(.*?)\\](?P<id>.*?)\[\/jeu:(.*?)\\]/ms';
        preg_match_all($pattern, $body, $matches);

        $replacement = '<div class="entity" data-entity="game">--$2--</div>';
        $body = preg_replace($pattern, $replacement, $body);

        foreach ($matches['id'] as $key => $id) {
            /** @var ProductVariantInterface $productVariant */
            $productVariant = $this->productVariantRepository->findOneBy(['code' => sprintf('game-%s', $id)]);

            if (null === $productVariant) {
                continue;
            }

            $body = str_replace(sprintf('--%s--', $id), $productVariant->getProduct()->getCode(), $body);
        }

        return $body;
    }

    /**
     * @param string $body
     *
     * @return string
     */
    protected function colorReplacement($body)
    {
        $pattern = '/\[color=(.*?)\](?P<text>.*?)\[\/color:(.*?)\]/ms';
        $replacement = "$2";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     *
     * @return string
     */
    protected function blockReplacement($body)
    {
        $pattern = '/\[bleft:(.*?)\](?P<text>.*?)\[\/bleft:(.*?)\]/ms';
        $replacement = "<div class=\"clearfix\"></div><div style=\"margin-right: 10px\" class=\"pull-left\">$2</div>";
        $body = preg_replace($pattern, $replacement, $body);

        $pattern = '/\[bright:(.*?)\](?P<text>.*?)\[\/bright:(.*?)\]/ms';
        $replacement = "<div class=\"clearfix\"></div><div style=\"margin-left: 10px\" class=\"pull-right\">$2</div>";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function sizeReplacement($body)
    {
        $pattern = '/\[size=(.*?)\](?P<text>.*?)\[\/size:(.*?)\]/ms';
        $replacement = "$2";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function urlReplacement($body)
    {
        $pattern = '/\[url=(?P<path>.*?):(.*?)\](?P<label>.*?)\[\/url:(.*?)\]/ms';
        $replacement = "<a href=\"$1\" target='_blank'>$3</a>";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function boldReplacement($body)
    {
        $pattern = '/\[b:(.*?)\](?P<label>.*?)\[\/b:(.*?)\]/ms';
        $replacement = "<strong>$2</strong>";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function italicReplacement($body)
    {
        $pattern = '/\[i:(.*?)\](?P<label>.*?)\[\/i:(.*?)\]/ms';
        $replacement = "<em>$2</em>";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function underlineReplacement($body)
    {
        $pattern = '/\[u:(.*?)\](?P<label>.*?)\[\/u:(.*?)\]/ms';
        $replacement = "<strong>$2</strong>";
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function centerReplacement($body)
    {
        $pattern = '/\[center:(.*?)\](?P<label>.*?)\[\/center:(.*?)\]/ms';
        $replacement = '$2';
        $body = preg_replace($pattern, $replacement, $body);

        return $body;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function listReplacement($body)
    {
        $pattern = '/\[list:(.*?)\](?P<label>.*?)\[\/list:(.*?)\]/ms';
        $replacement = '</p><ul class="list-6">$2</ul><p>';
        $body = preg_replace($pattern, $replacement, $body);


        $pattern = '/\[\*:(.*?)\](?P<label>.*?)\[\/\*:(.*?)\]/ms';
        $replacement = "<li>$2</li>";
        $body = preg_replace($pattern, $replacement, $body);

        $body = str_replace("</li></p><p>", "</li>", $body);

        return $body;
    }

    /**
     * @param string $name
     * @param string|null $size
     *
     * @return string
     */
    protected function getImageOriginalPath($name, $size = null)
    {
        $size = null !== $size ? $size : '800';

        return "http://www.jedisjeux.net/img/" . $size . "/" . $name;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}
