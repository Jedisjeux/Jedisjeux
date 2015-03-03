<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 26/02/15
 * Time: 23:23
 */

namespace JDJ\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Image
 * @package JDJ\CoreBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_image")
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }



    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * return the public url to the path
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    /**
     * get the absolute path to the upload directory
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * The path to the  path files
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads/img';
    }

    /**
     * This function uploads the file to the server
     *
     */
    public function upload()
    {
        //Checks if the path is null
        if (null === $this->file) {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        $this->path = $this->file->getClientOriginalName();

        // Clean the path file
        $this->file = null;
    }
} 