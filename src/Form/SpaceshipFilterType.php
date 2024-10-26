<?php
namespace App\Filter;

use App\Filter\SpaceshipFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SpaceshipFilterType extends AbstractType
{
  private ?string $name = null;

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->setMethod('GET')
      ->add('title', TextType::class, [
        'label' => 'Title',
        'required' => false
      ])
    ;
  }
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => SpaceshipFilter::class,
    ]);
  }
}