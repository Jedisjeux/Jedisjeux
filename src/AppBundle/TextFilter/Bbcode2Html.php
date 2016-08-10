<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/02/2016
 * Time: 12:46
 */

namespace AppBundle\TextFilter;
use Doctrine\DBAL\Connection;


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
     * Bbcode2Html constructor.
     *
     * @param Connection $databaseConnection
     */
    public function __construct(Connection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    /**
     * @return string
     */
    public function getFilteredBody()
    {
        $body = $this->body;
        $body = $this->nl2p($body);
        $body = $this->emoticonReplacement($body);
        $body = $this->colorReplacement($body);
        $body = $this->sizeReplacement($body);
        $body = $this->quoteReplacement($body);
        $body = $this->imageReplacement($body);
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
        return "<p>".str_replace("\n", "</p><p>", trim($body))."</p>";
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
        $body = str_replace(':light:', '', $body);
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
        $pattern = '/\[url=(?P<path>.*?)\](?P<label>.*?)\[\/url:(.*?)\]/ms';
        $replacement = "<a href=\"$1\" target='_blank'>$2</a>";
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