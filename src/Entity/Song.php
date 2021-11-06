<?php

namespace App\Entity;

use App\Repository\SaveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SaveRepository::class)
 */
class Song
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $spotifyId;

    /**
     * @ORM\Column(type="integer")
     */
    private $ranking;

    /**
     * @ORM\ManyToOne(targetEntity=Save::class, inversedBy="songs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $save;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $TimeRange;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSpotifyId(): ?string
    {
        return $this->spotifyId;
    }

    public function setSpotifyId(string $spotifyId): self
    {
        $this->spotifyId = $spotifyId;

        return $this;
    }

    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(int $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }

    public function getSave(): ?Save
    {
        return $this->save;
    }

    public function setSave(?Save $save): self
    {
        $this->save = $save;

        return $this;
    }

    public function getTimeRange(): ?string
    {
        return $this->TimeRange;
    }

    public function setTimeRange(string $TimeRange): self
    {
        $this->TimeRange = $TimeRange;

        return $this;
    }
}
