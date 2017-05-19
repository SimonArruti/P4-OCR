<?php

use AppBundle\Services\SessionService;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Command;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SessionTest extends TestCase
{
    public function testAddTicketToSession ()
    {
        $session = new Session(new MockArraySessionStorage());
        $command = new Command();
        $ticket = new Ticket();

        $session->set('command', $command);

        $session_service = new SessionService();
        $session_service->addTicketToSession($session, $ticket);

        $this->assertCount(1, $session->get('command')->getTickets());
    }

    public function testRemoveTicketFromSession ()
    {
        $session = new Session(new MockArraySessionStorage());
        $command = new Command();
        $ticket = new Ticket();

        $session->set('command', $command);

        $session_service = new SessionService();
        $session_service->addTicketToSession($session, $ticket);

        $session_service->removeTicketFromSession($session, 0);

        $this->assertCount(0, $session->get('command')->getTickets());

    }

    public function testEmptySession ()
    {
        $session = new Session(new MockArraySessionStorage());
        $command = new Command();

        $session->set('command', $command);

        $session_service = new SessionService();
        $session_service->emptySession($session, "command");

        $this->assertEquals(null, $session->get('command'));
    }
}