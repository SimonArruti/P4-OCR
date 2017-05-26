<?php
/**
 * Created by PhpStorm.
 * User: simonarruti
 * Date: 08/05/2017
 * Time: 01:36
 */

namespace AppBundle\Services;


use AppBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{
    /**
     * @param SessionInterface $session
     * @param Ticket $ticket
     * @return bool
     */
    public function addTicketToSession (SessionInterface $session,  Ticket $ticket)
    {
        //$session = $request->getSession();
        $session->get('command')->addTicket($ticket);

        return true;
    }

    /**
     * @param SessionInterface $session
     * @param int $id
     * @return bool
     */
    public function removeTicketFromSession (SessionInterface $session, int $id)
    {
        $tickets = $session->get('command')->getTickets();
        $session->get('command')->removeTicket($tickets[$id]);

        return true;
    }

    /**
     *
     * @param SessionInterface $session
     * @param string $session_name
     * @return bool
     */
    public function emptySession (SessionInterface $session, string $session_name)
    {
        $session->remove($session_name);

        return true;
    }
}