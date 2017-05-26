<?php
/**
 * Created by PhpStorm.
 * User: simonarruti
 * Date: 08/05/2017
 * Time: 02:12
 */

namespace AppBundle\Services;


use AppBundle\Entity\Ticket;

class Price
{
    /**
     * @param Ticket $ticket
     * @return string
     */
    public function getTicketType (Ticket $ticket)
    {
        $date = $ticket->getBirthDate();

        $now = new \DateTime();
        $interval = $now->diff($date);

        $age = $interval->y;

        if ($ticket->isReduce() && ($age >= 4 && $age <= 12)) {

            return "enfant";
        }
        elseif ($ticket->isReduce() && $age < 4) {

            return "free";
        }
        elseif ($ticket->isReduce()) {

            return "reduce";
        }
        elseif ($age >= 4 && $age <= 12) {

            return "enfant";
        }
        elseif ($age > 12 && $age < 60) {

            return "normal";
        }
        elseif ($age > 60) {

            return "senior";
        }
        else {

            return "free";
        }
    }

    /**
     * @param string $ticketType
     * @return int
     */
    public function getPrice (string $ticketType)
    {
        switch ($ticketType) {
            case "normal" :
                return 16;

                break;
            case "enfant" :
                return 8;

                break;
            case "senior" :
                return 12;

                break;
            case "reduce" :
                return 10;

                break;
            default :
                return 0;
        }
    }

    public function getTicketTypeAndPrice ($tickets)
    {
        foreach ($tickets as $key => $ticket) {
            $ticket_type = $this->getTicketType($ticket);

            $price = $this->getPrice($ticket_type);

            $ticket->setTicketType($ticket_type);
            $ticket->setPrice($price);
        }

        return $tickets;
    }
}