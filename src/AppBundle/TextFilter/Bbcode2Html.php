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

    public function getFilteredBody()
    {
        $body = $this->body;

        // blockquote
        $pattern = '/\[quote\=&quot;(?P<title>.*?)&quot;\:(.*?)\](?P<body>.*?)\[\/quote(.*?)\]/';
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