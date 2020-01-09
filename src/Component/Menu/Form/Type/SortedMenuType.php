<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\Type;

use App\Component\Menu\Form\DTO\SortedMenu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortedMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('items', CollectionType::class, [
            'entry_type' => SortedMenuItemType::class,
            'allow_add' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', SortedMenu::class);
        $resolver->setDefault('csrf_protection', false);
    }
}
