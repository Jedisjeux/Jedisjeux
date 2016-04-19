<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 08/03/2016
 * Time: 10:12
 */

namespace AppBundle\Entity;

use AppBundle\Document\ArticleContent;
use AppBundle\Model\Identifiable;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_article")
 */
class Article implements ResourceInterface
{
    use Identifiable;

    /**
     * @var integer
     *
     * @ORM\Column(type="string")
     */
    protected $documentId;

    /**
     * @var ArticleContent
     */
    protected $document;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     */
    protected $product;

    /**
     * @return int
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * @param int $documentId
     *
     * @return $this
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;

        return $this;
    }

    /**
     * @return ArticleContent
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param ArticleContent $document
     *
     * @return $this
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }
}