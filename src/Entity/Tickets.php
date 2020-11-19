<?php

namespace App\Entity;

use App\Repository\TicketsRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @ORM\Entity(repositoryClass=TicketsRepository::class)
 */
class Tickets
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */

    private $ticketStatus;

    /**
     * @ORM\Column(type="integer")
     */
    private $ticketPriority;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $assignedTo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Content;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="tickets")
     */
    private $Comments;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closingTime;


    public function __construct()
    {
        $this->Comments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTicketStatus(): ?string
    {
        return $this->ticketStatus;
    }

    public function setTicketStatus(string $ticketStatus): self
    {
        $this->ticketStatus = $ticketStatus;

        return $this;
    }

    public function getTicketPriority(): ?int
    {
        return $this->ticketPriority;
    }

    public function setTicketPriority(int $ticketPriority): self
    {
        $this->ticketPriority = $ticketPriority;

        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->Comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->Comments->contains($comment)) {
            $this->Comments[] = $comment;
            $comment->setTickets($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->Comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTickets() === $this) {
                $comment->setTickets(null);
            }
        }

        return $this;
    }


    public function getClosingTime(): ?\DateTimeInterface
    {
        return $this->closingTime;
    }

    public function setClosingTime(?\DateTimeInterface $closingTime): self
    {
        $this->closingTime = $closingTime;

        return $this;
    }

    public function closeTicket()
    {
        $this->closingTime = new DateTime();
        $this->ticketStatus ='closed';
        $this->sendMail();
        return $this;
    }

    public function openTicket()
    {
        $this->ticketStatus ='open';
        $this->setAssignedTo(null);
        $this->setClosingTime(null);
        $this->sendMail();
        return $this;
    }

    public function sendMail()
    {   $mail=$this->getCreatedBy()->getEmail();
        $to      = $mail;
        $subject = 'Your ticket has been updated';
        $message = 'Your ticket has been updated. Please login to your account.';
        mail($to, $subject, $message);
        return $this;
    }




}
