<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Form\AgentTicketsType;
use App\Form\TicketsType;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AgentController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/agent", name="agent_index", methods={"GET"})
     */
    public function index(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('/tickets/index_agent.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="tickets_show", methods={"GET"})
     */
    public function show(Tickets $ticket): Response
    {
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/edit/agent", name="agent_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tickets $ticket): Response
    {

        //


        $form = $this->createForm(AgentTicketsType::class, $ticket);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agent_index');
        }

        return $this->render('tickets/edit_agent.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/assign/agent", name="agent_assign", methods={"GET","POST"})
     */
    public function assign(Request $request, Tickets $ticket, Security $security): Response
    {
        $user = $this->security->getUser();
        $role=$user->getRoles();
        $status=$ticket->getTicketStatus();
        if($role==['ROLE_AGENT_ONE']){
            if($status!='second line'){
                $ticket->setAssignedTo($user);
                $ticket->setTicketStatus('in progress');
                $this->getDoctrine()->getManager()->flush();
            }
            else{$this->addFlash('forSecondLine', 'Ticket has to be assigned to second line agent.');}
        }
        if($role==['ROLE_AGENT_TWO']){
            if($status=='second line'){
                $ticket->setAssignedTo($user);
                $ticket->setTicketStatus('in progress');
                $this->getDoctrine()->getManager()->flush();
            }
            else{$this->addFlash('forFirstLine', 'Ticket has to be assigned to first line agent.');}
        }

        return $this->redirectToRoute('agent_index');

    }
//

    /**
     * @Route("/{id}/second-line/agent", name="agent_second_line", methods={"GET","POST"})
     */
    public function tosecond(Request $request, Tickets $ticket, Security $security): Response
    {
        $ticket->setTicketStatus('second line');
        $ticket->setAssignedTo(null);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('agent_index');

    }

    /**
     * @Route("/{id}/close/agent", name="agent_close", methods={"GET","POST"})
     */
    public function close(Request $request, Tickets $ticket, Security $security): Response
    {   $messaseCantClose='';
        $comments = $ticket->getComments();
        $commentsAuthors = array();
        foreach ($comments as $comment) {
            $author=$comment->getUserComments();
            $role=$author->getRoles();
            $status=$comment->getStatus();
            if($status==['PUBLIC'] && ($role==['ROLE_AGENT_ONE'] || $role==['ROLE_AGENT_TWO'])){
            array_push($commentsAuthors,$comment );}
        }
        if(!empty ($commentsAuthors)){
            $ticket->setTicketStatus('closed');
            $ticket->setAssignedTo(null);
            $this->getDoctrine()->getManager()->flush();
        }
        else{ $this->addFlash('alert', 'Ticket can not be close, no agent offered a solution.');}

        return $this->redirectToRoute('agent_index');
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
