<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractImage implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     * @Assert\Image
     */
    private $file;

    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @return $this
     */
    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
        $this->updatedAt = new \DateTime();
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * return the public url to the path.
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    /**
     * get the absolute path to the upload directory.
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../public/'.$this->getUploadDir();
    }

    /**
     * The path to the  path files.
     */
    protected function getUploadDir(): string
    {
        return 'uploads/img';
    }

    /**
     * This function uploads the file to the server.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function upload()
    {
        //Checks if the path is null
        if (null === $this->file) {
            return;
        }

        $hash = uniqid('', true);
        $extension = $this->file->getClientOriginalExtension();
        $newFilename = $hash.'.'.$extension;

        $this->file->move($this->getUploadRootDir(), $newFilename);
        $this->path = $newFilename;

        // Clean the path file
        $this->file = null;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("default")
     * @JMS\Type("LiipSerializer")
     * @JMS\Groups({"Default", "Detailed"})
     */
    public function getDefaultSerialize()
    {
        if (null === $this->getWebPath()) {
            return null;
        }

        return [
            'filename' => $this->getWebPath(),
            'filter' => 'default',
        ];
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("thumbnail")
     * @JMS\Type("LiipSerializer")
     * @JMS\Groups({"Default", "Detailed"})
     */
    public function getThumbnailSerialize()
    {
        if (null === $this->getWebPath()) {
            return null;
        }

        return [
            'filename' => $this->getWebPath(),
            'filter' => 'thumbnail',
        ];
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("magazine_item")
     * @JMS\Type("LiipSerializer")
     * @JMS\Groups({"Default", "Detailed"})
     */
    public function getMagazineItemSerialize()
    {
        if (null === $this->getWebPath()) {
            return null;
        }

        return [
            'filename' => $this->getWebPath(),
            'filter' => 'magazine_item',
        ];
    }
}
