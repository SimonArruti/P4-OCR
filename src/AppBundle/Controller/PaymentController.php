<?php
/**
 * Created by PhpStorm.
 * User: simonarruti
 * Date: 08/05/2017
 * Time: 02:11
 */

namespace AppBundle\Controller;

use AppBundle\Form\PaymentType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    /**
     * @Route("/payment", name="payment")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function payment (Request $request) {

        $session = $request->getSession();

        if ($session->get('command') == null) {

            return $this->redirectToRoute('homepage');
        }
        elseif (count($session->get('command')->getTickets()) === 0) {

            return $this->redirectToRoute('command');
        }
        else {
            $command = $session->get('command');

            $tickets = $this->get('app.price')->getTicketTypeAndPrice($command->getTickets());

            $prices = array_column($tickets->toArray(), 'price');
            $total = array_sum($prices);

            $command->setPriceTotal($total);

            $form = $this->createForm(PaymentType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $secret_key = $this->getParameter('stripe_secret');

                Stripe::setApiKey($secret_key);

                $token = $request->get('stripeToken');
                $price = $command->getPriceTotal();
                $customer_email = $form->getData()['customer_email'];

                try {
                    $charge = Charge::create(array(
                        "amount" => $price . "00",
                        "currency" => "eur",
                        "description" => "Louvre tickets bought by " . $customer_email,
                        "source" => $token
                    ));

                    $this->get('app.hydrate')->addCommandToDatabase($command, $customer_email, $price);

                    $this->get('app.hydrate')->sendMail($command);

                    $session->getFlashBag()->add('command_success', 'La commande a bien été enregistrée. Vous allez très vite recevoir le récapitulatif de celle-ci par mail.');

                    $this->get('app.session')->emptySession($session, $command);

                    return $this->redirectToRoute("homepage");
                }
                catch (Exception $exception) {
                    $session->getFlashBag()->add('command_fail', 'La commande a échouée. Veuillez réessayer.');

                    return $this->redirect($request->headers->get('referer'));
                }
            }

            return $this->render('payment.html.twig', array("command" => $session->get('command'), "payment_form" => $form->createView()));
        }
    }
}