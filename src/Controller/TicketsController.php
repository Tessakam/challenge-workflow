<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\User;
use App\Form\CommentWontfixeType;
use App\Form\PriorityTicketType;
use App\Form\TicketsType;
use App\Form\WontFixTicketsType;
use App\Repository\TicketsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/tickets")
 */
class TicketsController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/", name="tickets_index", methods={"GET"})
     */
    public function index(TicketsRepository $ticketsRepository): Response
    {
        $user = $this->security->getUser();
        $createdBy = $user->getId();
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findBy(
                ['createdBy' => $createdBy]
            )
        ]);
    }

    /**
     * @Route("/new", name="tickets_new", methods={"GET","POST"})
     * @param Request $request
     * @param $security
     * @return Response
     */
    public function new(Request $request, Security $security): Response
    {
        $user = $this->security->getUser();
        $ticket = new Tickets();
        $ticket->setCreatedBy($user);
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('tickets_index');
        }

        return $this->render('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tickets_show", methods={"GET"})
     */
    public function show(Tickets $ticket): Response
    {
        $comments = $ticket->getComments();
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
            'comments' => $comments,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="tickets_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tickets_index');
        }

        return $this->render('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/priority", name="ticket_priority", methods={"GET","POST"})
     */
    public function prioritize(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(PriorityTicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agent_index');
        }

        return $this->render('tickets/prioritize.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/wont-fix", name="ticket_cannot_be_fixed", methods={"GET","POST"})
     */
    public function wontFix(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(WontFixTicketsType::class, $ticket);
        $form->handleRequest($request);
        //$formcomment = $this->createForm(CommentWontfixeType::class, $ticket);
       // $formcomment->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agent_index');
        }

        return $this->render('tickets/wontfix.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/open", name="tickets_open", methods={"GET","POST"})
     */
    public function reopen(Request $request, Tickets $ticket): Response
    {
        $currentDate = new DateTime();
        $closingDate = $ticket->getClosingTime();
        $interval = $closingDate->diff($currentDate);
        $id = $ticket->getId();
        if ($interval->format('h') <= 1) {
            $ticket->openTicket();
            $this->getDoctrine()->getManager()->flush();
        } else {
            $this->addFlash('tooLateReopen', 'You can not open this ticket anymore please submit a new ticket.');
        }
        return $this->redirectToRoute('tickets_show', ['id' => $id,]);


    }

    /**
     * @Route("/{id}", name="tickets_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tickets $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tickets_index');
    }
}
