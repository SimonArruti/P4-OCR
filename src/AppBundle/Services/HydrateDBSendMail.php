<?php
/**
 * Created by PhpStorm.
 * User: simonarruti
 * Date: 10/05/2017
 * Time: 00:39
 */

namespace AppBundle\Services;


use AppBundle\Entity\Command;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HydrateDBSendMail
{
    private $em;
    private $container;

    /**
     * HydrateDB constructor.
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function addCommandToDatabase (Command $command, string $customer_email, int $price)
    {
        $command->setCustomerEmail($customer_email);
        $command->setTotalPaid($price);
        $command->setTicketsBought(count($command->getTickets()));
        $command->setReservationCode();

        $this->em->persist($command);

        try {
            $this->em->flush();
        }
        catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public function sendMail (Command $command)
    {
        $date = $command->getVisitDay()->date;
        $period = $command->getVisitPeriod();
        $price = $command->getPriceTotal();
        $tickets = $command->getTickets();

        $reservation_code = $command->getReservationCode();

        $customer = $command->getCustomerEmail();

        $message = \Swift_Message::newInstance()
            ->setSubject('Test mail')
            ->setFrom('louvre@mail.com')
            ->setTo($customer)
            ->setBody(
                $this->container->get('templating')->render('mail/email.html.twig', array(
                    "visitDay" => $date,
                    "visitPeriod" => $period,
                    "price" => $price,
                    "code" => $reservation_code,
                    "tickets" => $tickets
                ))
            )
        ;

        $this->container->get('mailer')->send($message);
    }
}