<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandRepository")
 */
class Command
{
    const TYPE_DAY = "day";

    const TYPE_HALF_DAY = "half-day";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="command", cascade={"persist"})
     */
    private $tickets;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_email", type="string", length=255)
     */
    private $customerEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit_day", type="date")
     */
    private $visitDay;

    /**
     * @var string
     *
     * @ORM\Column(name="visit_period", type="string", length=255)
     */
    private $visitPeriod;

    /**
     * @var int
     */
    private $priceTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="total_paid", type="integer")
     */
    private $totalPaid;

    /**
     * @var int
     *
     * @ORM\Column(name="tickets_bought", type="integer")
     */
    private $ticketsBought;

    /**
     * @var string
     *
     * @ORM\Column(name="reservation_code", type="string")
     */
    private $reservationCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchased_at", type="datetime")
     */
    private $purchasedAt;

    /**
     * Command constructor.
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->purchasedAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customerEmail
     *
     * @param string $customerEmail
     *
     * @return Command
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Set visitDay
     *
     * @param \DateTime $visitDay
     *
     * @return Command
     */
    public function setVisitDay($visitDay)
    {
        $this->visitDay = $visitDay;

        return $this;
    }

    /**
     * Get visitDay
     *
     * @return \DateTime
     */
    public function getVisitDay()
    {
        return $this->visitDay;
    }

    /**
     * Set visitPeriod
     *
     * @param string $visitPeriod
     *
     * @return Command
     */
    public function setVisitPeriod($visitPeriod)
    {
        $this->visitPeriod = $visitPeriod;

        return $this;
    }

    /**
     * Get visitPeriod
     *
     * @return string
     */
    public function getVisitPeriod()
    {
        return $this->visitPeriod;
    }

    /**
     * @return int
     */
    public function getPriceTotal()
    {
        return $this->priceTotal;
    }

    /**
     * @param int $priceTotal
     */
    public function setPriceTotal(int $priceTotal)
    {
        $this->priceTotal = $priceTotal;
    }

    /**
     * Set totalPaid
     *
     * @param integer $totalPaid
     *
     * @return Command
     */
    public function setTotalPaid($totalPaid)
    {
        $this->totalPaid = $totalPaid;

        return $this;
    }

    /**
     * Get totalPaid
     *
     * @return int
     */
    public function getTotalPaid()
    {
        return $this->totalPaid;
    }

    /**
     * Set ticketsBought
     *
     * @param integer $ticketsBought
     *
     * @return Command
     */
    public function setTicketsBought($ticketsBought)
    {
        $this->ticketsBought = $ticketsBought;

        return $this;
    }

    /**
     * Get ticketsBought
     *
     * @return int
     */
    public function getTicketsBought()
    {
        return $this->ticketsBought;
    }

    /**
     * Set reservationCode
     *
     * @param string $reservationCode
     *
     * @return Command
     */
    public function setReservationCode()
    {
        $alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $letter = substr(str_shuffle($alpha), 0, 10);
        $bytes = random_bytes(2);
        $code = $letter . bin2hex($bytes);

        $this->reservationCode = $code;

        return $this;
    }

    /**
     * Get reservationCode
     *
     * @return string
     */
    public function getReservationCode()
    {
        return $this->reservationCode;
    }

    /**
     * Set purchasedAt
     *
     * @param \DateTime $purchasedAt
     *
     * @return Command
     */
    public function setPurchasedAt($purchasedAt)
    {
        $this->purchasedAt = $purchasedAt;

        return $this;
    }

    /**
     * Get purchasedAt
     *
     * @return \DateTime
     */
    public function getPurchasedAt()
    {
        return $this->purchasedAt;
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Command
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setCommand($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

}
