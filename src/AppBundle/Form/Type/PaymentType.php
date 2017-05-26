<?php
/**
 * Created by PhpStorm.
 * User: simonarruti
 * Date: 09/05/2017
 * Time: 00:37
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer_email', TextType::class, array(
                "label" => "Email",
                "constraints" => array(
                    new NotBlank(),
                    new Email(array("message" => "L'email '{{ value }}' n'est pas une adresse valide."))
                )
            ))
            ->add('submit', SubmitType::class, array(
                "label" => "Valider et payer"
            ));
    }
}