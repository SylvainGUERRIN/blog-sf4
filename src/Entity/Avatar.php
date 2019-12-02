<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AvatarRepository")
 */
class Avatar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url_avatar;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="avatar", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlAvatar(): ?string
    {
        return $this->url_avatar;
    }

    public function setUrlAvatar(string $url_avatar): self
    {
        $this->url_avatar = $url_avatar;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newAvatar = null === $user ? null : $this;
        if ($user->getAvatar() !== $newAvatar) {
            $user->setAvatar($newAvatar);
        }

        return $this;
    }
}
