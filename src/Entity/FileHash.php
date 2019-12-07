<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FileHash
{
    /**
     * @var File
     *
     * @ORM\Id()
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    private $hash;

    /**
     * @param File   $file
     * @param string $hash
     */
    public function __construct(File $file, string $hash)
    {
        $this->file = $file;
        $this->hash = $hash;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @param File $file
     *
     * @return FileHash
     */
    public function setFile(File $file): FileHash
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return FileHash
     */
    public function setHash(string $hash): FileHash
    {
        $this->hash = $hash;

        return $this;
    }
}
