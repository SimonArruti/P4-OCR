<?php

use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use AppBundle\Services\Price;

class PriceTest extends TestCase
{
    public function testGetTicketType ()
    {
        $ticket = new Ticket();
        $ticket->setFirstname('Carl');

        $date = new DateTime('1955-05-07');
        var_dump($date);
        $ticket->setBirthDate($date);

        $price = new Price();

        $this->assertEquals('senior', $price->getTicketType($ticket));
    }

    public function testGetPrice ()
    {
        $price = new Price();

        $this->assertEquals(16, $price->getPrice('normal'));
        $this->assertEquals(8, $price->getPrice('enfant'));
        $this->assertEquals(12, $price->getPrice('senior'));
        $this->assertEquals(10, $price->getPrice('reduce'));
        $this->assertEquals(0, $price->getPrice('something else'));
    }
}