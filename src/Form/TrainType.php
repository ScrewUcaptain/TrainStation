<?php

namespace App\Form;

use App\Entity\Train;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
		->add('arrivalTime', DateTimeType::class, [
				'date_label' => 'train arrives on',
				'data' => new \DateTime(),
				'input' => 'datetime',
				'years' => range(date('Y'), date('Y')+1),
				'months' => range(date('m'), 12),
				'days' => range(date('d'), 31),
				'hours' => range(0, 23),
				'minutes' => range(0, 59),
			])
			->add('departureTime', DateTimeType::class, [
				'date_label' => 'train leaves on',
				//datetime plus 10 minutes
				'data' => new \DateTime(),
				'input_format' => 'H:i d/m/Y',
				'years' => range(date('Y'), date('Y')+1),
				'months' => range(date('m'), 12),
				'days' => range(date('d'), 31),
				'hours' => range(0, 23),
				'minutes' => range(0, 59),
			])
            ->add('destination', EntityType::class, [
				'class' => 'App\Entity\Destination',
				'choice_label' => 'name',
				'label' => 'destination',
			])
			->add('save', SubmitType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Train::class,
        ]);
    }
}
