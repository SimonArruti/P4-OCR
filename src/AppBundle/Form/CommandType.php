<?php

namespace AppBundle\Form;

use AppBundle\Entity\Command;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitDay', DateType::class, array(
                'input' => "datetime",
                'widget' => 'single_text',
                'label' => 'Date de visite',
                'format' => 'dd/MM/yyyy',
                "constraints" => array(
                    new NotBlank(),
                    new DateTime(array("message" => "Valeur invalide."))
                )
            ))
            ->add('visitPeriod', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => Command::TYPE_DAY,
                    'Demi-journée' => Command::TYPE_HALF_DAY
                ),
                "choice_attr" => array(
                    "Journée" => array("class" => "select-period-full")
                ),
                "label" => "Type de billet"
                )
            )
            ->add('submit', SubmitType::class, array(
                "label" => "Valider"
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Command'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_command';
    }


}
