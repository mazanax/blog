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
     * @ORM\Id()
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $hash;

    public function __construct(File $file, string $hash)
    {
        $this->file = $file;
        $this->hash = $hash;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): FileHash
    {
        $this->file = $file;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): FileHash
    {
        $this->hash = $hash;

        return $this;
    }
}
