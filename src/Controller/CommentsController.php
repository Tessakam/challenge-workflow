<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tickets;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/comments")
 */
class CommentsController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/", name="comments_index", methods={"GET"})
     */
    public function index(CommentsRepository $commentsRepository): Response
    {
        return $this->render('comments/index.html.twig', [
            'comments' => $commentsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comments_new", methods={"GET","POST"})
     * @param Request $request
     * @param $security
     */
    public function new(Request $request, Security $security): Response
    {
        $id = $request->query->get('ticketId');
        $ticket = $this->getDoctrine()->getRepository(Tickets::class)->find($id);
        $user = $this->security->getUser();
        $comment = new Comments();
        $comment->setUserComments($user);
        $comment->setTickets($ticket);
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($user->getRoles() == ['ROLE_AGENT_ONE'] || $user->getRoles() == ['ROLE_AGENT_TWO']) && $comment->getStatus() == ['PUBLIC']) {
                $ticket->setTicketStatus('Waiting for customer feedback');
                $ticket->setAssignedTo($user);
                $ticket->sendMail();
                $this->getDoctrine()->getManager()->flush();
            }
            if ($user->getRoles() == ['ROLE_USER']  && $ticket->getTicketStatus() =='Waiting for customer feedback') {
                $ticket->setTicketStatus('in progress');
                $this->getDoctrine()->getManager()->flush();
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('tickets_show', ['id' => $id,]);
        }

        return $this->render('comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comments_show", methods={"GET"})
     */
    public function show(Comments $comment): Response
    {
        return $this->render('comments/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comments_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comments $comment): Response
    {
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comments_index');
        }

        return $this->render('comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comments_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comments $comment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comments_index');
    }
}
