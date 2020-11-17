<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $commentsId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userComments;

    /**
     * @ORM\ManyToOne(targetEntity=Tickets::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $relatedTicket;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentContent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentsId(): ?int
    {
        return $this->commentsId;
    }

    public function setCommentsId(int $commentsId): self
    {
        $this->commentsId = $commentsId;

        return $this;
    }

    public function getUserComments(): ?User
    {
        return $this->userComments;
    }

    public function setUserComments(?User $userComments): self
    {
        $this->userComments = $userComments;

        return $this;
    }

    public function getRelatedTicket(): ?Tickets
    {
        return $this->relatedTicket;
    }

    public function setRelatedTicket(?Tickets $relatedTicket): self
    {
        $this->relatedTicket = $relatedTicket;

        return $this;
    }

    public function getCommentContent(): ?string
    {
        return $this->commentContent;
    }

    public function setCommentContent(?string $commentContent): self
    {
        $this->commentContent = $commentContent;

        return $this;
    }
}
