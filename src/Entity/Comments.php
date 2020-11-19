<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    public const status=["PUBLIC"=>"PUBLIC","PRIVATE"=>"PRIVATE"];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userComments;

    /**
     * @ORM\Column(type="string")
     */

    private $commentContent;

    /**
     * @ORM\ManyToOne(targetEntity=Tickets::class, inversedBy="Comments")
     */
    private $tickets;

    /**
     * @ORM\Column(type="json")
     */
    private $status= [];

    public function getId(): ?int
    {
        return $this->id;
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


    public function getCommentContent(): ?string
    {
        return $this->commentContent;
    }

    public function setCommentContent(?string $commentContent): self
    {
        $this->commentContent = $commentContent;

        return $this;
    }

    public function getTickets(): ?Tickets
    {
        return $this->tickets;
    }

    public function setTickets(?Tickets $tickets): self
    {
        $this->tickets = $tickets;

        return $this;
    }

    public function getStatus(): array
    {
        $status=$this->status;
        return array_unique($status);
    }
    public function __construct()
    {
        //$this->roles = array('ROLE_USER');
        $this->setStatus((array)self::status["PUBLIC"]);
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

}
