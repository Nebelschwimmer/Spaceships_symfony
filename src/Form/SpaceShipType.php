<?php

namespace App\Form;
use App\Entity\SpaceShipCategory;
use App\Entity\SpaceShip;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SpaceShipType extends AbstractType
{
    public function __construct(
        private TagTransformer $transformer,
    ) {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name',
                'help' => 'The name of the spaceship', 
            ])
            ->add('color')
            ->add('size')
            ->add('crewCapacity')
            ->add('maxSpeed')
            ->add(child: 'image', type: TextType::class)
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, [
                'class' => SpaceShipCategory::class,
                'choice_label' => 'name',
                'required' => true,
                'empty_data' => null,
                'placeholder' => 'Choose a category',
            ]);
        $builder->add('tags', TextType::class, array(
            'label' => 'Теги',
            'required' => false,
        ));
        $builder->get('tags')->addModelTransformer($this->transformer);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpaceShip::class,
        ]);
    }
}
