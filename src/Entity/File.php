<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace App\Entity;

abstract class File
{
    /**
     * @var \SplFileInfo|null
     */
    private $file;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @return \SplFileInfo|null
     */
    public function getFile(): ?\SplFileInfo
    {
        return $this->file;
    }

    /**
     * @param \SplFileInfo|null $file
     */
    public function setFile(?\SplFileInfo $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }
}
