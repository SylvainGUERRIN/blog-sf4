<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AvatarRepository")
 * @Vich\Uploadable()
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
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/svg"}
     * )
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="url_avatar")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_avatar;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="avatar", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlAvatar()
    {
        return $this->url_avatar;
    }

    public function setUrlAvatar(?string $url_avatar)
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

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|UploadedFile $imageFile
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if(null !== $imageFile){
            $this->updated_at = new \DateTime('now');
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getUrlAvatar();
    }
}
