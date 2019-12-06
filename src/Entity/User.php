<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
// * @Vich\Uploadable()
 * @UniqueEntity(
 *     fields={"mail"},
 *     message="Cet email est déjà utilisé !"
 * )
 */
class User implements UserInterface, \Serializable
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pass;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $token_created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Avatar", inversedBy="user", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     */
    private $articles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

//    /**
//     * @var File
//     * @Assert\Image(
//     *     mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/svg"}
//     * )
//     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatar_file")
//     */
//    private $imageFile;

//    /**
//     * @ORM\Column(type="string", length=255, nullable=true)
//     */
//    private $avatar_file;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
//        $this->avatar = new arrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenCreatedAt(): ?\DateTimeInterface
    {
        return $this->token_created_at;
    }

    public function setTokenCreatedAt(?\DateTimeInterface $token_created_at): self
    {
        $this->token_created_at = $token_created_at;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article)
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article)
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @return []
     */
    public function getRoles(): array
    {
        if($this->getRole() === 'admin'){
            return ['ROLE_ADMIN'];
        }

        return ['ROLE_USER'];
    }

    /**
     * @inheritDoc
     * si sur l'entité pas de getter Password
     * le spécifié ici et retourner le mot de passe de l'entité
     */
    public function getPassword(): ?string
    {
        return $this->pass;
    }

    /**
     * @inheritDoc
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return serialize([$this->id, $this->username, $this->mail, $this->pass]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->username, $this->mail, $this->pass] = unserialize($serialized, ['allowed_classes' => false]);
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

    public function getAvatarFile(): ?string
    {
        return $this->avatar_file;
    }

    public function setAvatarFile(?string $avatar_file): void
    {
        $this->avatar_file = $avatar_file;
    }

//    /**
//     * @return File|null
//     */
//    public function getImageFile(): ?File
//    {
//        return $this->imageFile;
//    }
//
//    /**
//     * @param File|UploadedFile $imageFile
//     * @throws \Exception
//     */
//    public function setImageFile(?File $imageFile = null): void
//    {
//        $this->imageFile = $imageFile;
//        if(null !== $imageFile){
//            $this->updated_at = new \DateTime('now');
//        }
//    }

}
