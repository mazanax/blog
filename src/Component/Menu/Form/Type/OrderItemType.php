<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\Type;

use App\Entity\Menu\Item;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Item::TYPES),
            ])
            ->add('parent', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'id'
            ])
            ->add('order', NumberType::class);
    }
}
