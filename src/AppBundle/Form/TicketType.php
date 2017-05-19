<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array(
                "label" => "Prénom",
                "constraints" => array(
                    new NotBlank(),
                    new Length(array('min' => 2, 'minMessage' => "Le champ prénom doit contenir au moins 2 caractères."))
                )
            ))
            ->add('lastname', TextType::class, array(
                "label" => "Nom",
                "constraints" => array(
                    new NotBlank(),
                    new Length(array('min' => 2, 'minMessage' => "Le champ nom doit contenir au moins 2 caractères."))
                )
            ))
            ->add('birthDate', BirthdayType::class, array(
                "label" => "Date de naissance",
                "format" => "dd MM yyyy",
                "constraints" => array(
                    new Date(array("message" => "Valeur invalide."))
                )
            ))
            ->add('country', CountryType::class, array(
                "label" => "Choix d'un pays",
                "constraints" => array(
                    new Country(array("message" => "Valeur invalide."))
                )
            ))
            ->add('reduce', CheckboxType::class, array(
                "label" => "Tarif réduit ?",
                "required" => false
            ))
            ->add('submit', SubmitType::class, array(
                "label" => "Ajouter un billet"
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ticket';
    }


}
