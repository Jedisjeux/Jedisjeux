<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/02/2016
 * Time: 12:46
 */

namespace AppBundle\TextFilter;


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
     * @return string
     */
    public function getFilteredBody()
    {
        $body = $this->body;
        $body = $this->emoticonReplacement($body);
        $body = $this->quoteReplacement($body);
        $body = $this->imageReplacement($body);
        $body = $this->urlReplacement($body);
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
        //var_dump($body);
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
    protected function urlReplacement($body)
    {
        $pattern = '/\[url=(?P<path>.*?)\](?P<label>.*?)\[\/url:(.*?)\]/ms';
        $replacement = "<a href=\"$1\" target='_blank'>$2</a>";
        $body = preg_replace($pattern, $replacement, $body);
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