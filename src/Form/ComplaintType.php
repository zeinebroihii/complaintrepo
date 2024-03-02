<?php

// src/Form/ComplaintType.php

namespace App\Form;

use App\Entity\ComplaintGestion\Complaint;
use App\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ComplaintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Technical' => 'technical',
                    'Conflict' => 'conflict',
                ],
                'placeholder' => 'Choose a type',
            ])
            ->add('description', FroalaEditorType::class) // Use FroalaEditorType for the description field
            ->add('priority')
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
        ]);
    }
}
