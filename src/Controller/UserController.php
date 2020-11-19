<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'roles' => User::roles,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    // Create new agent with role "Agent One/Two"
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        //var_dump($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    // show specific agent profile
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'roles' => User::roles,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    // edit specific agent profile
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'roles' => User::roles,
        ]);
    }

    /**
     * @Route("/{id}", name="reopen", methods={"reopen"})
     */
    // delete specific agent profile
    public function reopen(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('reopen' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    // delete specific agent profile
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/", name="reopen", methods={"GET","POST"})
     */
    //public function reopenTicket(10): response

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    // dashboard for manager
    public function dashboard(): response
    {
        $openTickets = 0;
        $closedTickets = 0;
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $allTickets = $this->getDoctrine()->getRepository(Tickets::class)->findAll();
        //var_dump($openTickets);

        // $em = entity manager: charge of saving objects and fetching them back out.
        $em = $this->getDoctrine()->getRepository(Tickets::class);
        $openTickets = $em->findBy(['ticketStatus' => 'open']);
        $closedTickets = $em->findBy(['ticketStatus' => 'closed']);

        $numberOpenTickets =count($openTickets);
        $numberClosedTickets =count($closedTickets);
        $numberReopenTickets =count($closedTickets);

        return $this->render('user/index.html.twig', [
            "users" => $users,
            'dashboardOpenTickets' => $openTickets,
            'dashboardClosedTickets' => $closedTickets,
            'dashboardTotalOpenTickets' => $numberOpenTickets,
            'dashboardTotalClosedTickets' => $numberClosedTickets,
          ]);
    }


    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not be complete yet.
        // Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/{id}/assign/inprogress", name="inprogress", methods={"GET","POST"})
     */
    //the manager can reopen tickets
    public function inprogress(Request $request, Tickets $ticket): Response
    {
        $ticket->setAssignedTo(null);
        $ticket->setTicketStatus('open');
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('agent_index');
    }
}
