<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use AppBundle\Form\CommandType;
use AppBundle\Form\TicketType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();

        $command = new Command();

        $form = $this->createForm(CommandType::class, $command);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('command', $command);

            return $this->redirectToRoute('command');

        }

        return $this->render('index.html.twig', array("data_form" => $form->createView()));
    }

    /**
     * @Route("/command", name="command")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandAction (Request $request)
    {
        $session = $request->getSession();

        if ($session->get('command') == null || $session->get('command')->getVisitDay() == null) {
            return $this->redirectToRoute('homepage');
        }
        else {
            $ticket = new Ticket();

            $form = $this->createForm(TicketType::class, $ticket);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tickets_counter =  $this->blockCommandIfNoTicketLeft($request, $session->get('command')->getVisitDay()->date);
                if ($tickets_counter) {
                    $this->get('app.session')->addTicketToSession($session, $ticket);

                    return $this->redirectToRoute('command');
                }
                else {
                    $session->getFlashBag()->add('error', 'Plus de tickets disponibles pour cette date!');

                    return $this->redirectToRoute('command');
                }

            }

            return $this->render('command.html.twig', array("ticket_form" => $form->createView(), "command" => $session->get('command')));
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/ticket/remove/{id}", name="remove_ticket", requirements={"id": "\d*"})
     */
    public function deleteTicketAction (Request $request, int $id)
    {
        $session = $request->getSession();
        $this->get('app.session')->removeTicketFromSession($session, $id);

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }

    /**
     * @Route("/command/cancel", name="cancel_command")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelCommandAction (Request $request)
    {
        $session = $request->getSession();
        $this->get('app.session')->emptySession($session, "command");

        return $this->redirectToRoute("homepage");
    }

    /**
     * @param string $date
     * @return Response
     *
     * @Route("/api/tickets/count/{date}", name="ajax_counter")
     */
    public function countTicketsByDaysAction (string $date) {
        $conn = $this->get('database_connection');
        $query = $conn->prepare("SELECT * FROM command WHERE visit_day = :date");
        $query->execute(array("date" => $date));

        $results = $query->fetchAll();

        $tickets_number = array_sum(array_column($results, 'tickets_bought'));

        return new Response(json_encode($tickets_number));
    }

    /**
     * @param Request $request
     * @param string $date
     * @return bool
     */
    private function blockCommandIfNoTicketLeft (Request $request, string $date)
    {
        $session = $request->getSession();
        $number_tickets_left = $this->countTicketsByDaysAction($date);

        //var_dump($session->get('command')->getTickets());

        if (count($session->get('command')->getTickets()) === (10 - $number_tickets_left->getContent())) {
            return false;
        }

        return true;
    }
}
