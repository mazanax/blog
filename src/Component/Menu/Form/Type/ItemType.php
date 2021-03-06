<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\Type;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Folder;
use App\Entity\Menu\Item;
use App\Entity\Page;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('parent', EntityType::class, [
                'placeholder' => 'Top-Level element',
                'choice_label' => 'title',
                'class' => Folder::class,
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Item::TYPES),
                'expanded' => true
            ])
            ->add('page', EntityType::class, [
                'placeholder' => 'Choose a page',
                'class' => Page::class,
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('tag', EntityType::class, [
                'placeholder' => 'Choose a tag',
                'class' => Tag::class,
                'required' => false,
            ])
            ->add('href', TextType::class, [
                'required' => false,
            ])
            ->add('inNewWindow', CheckboxType::class, [
                'label' => 'Open in new window/tab',
                'required' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ItemDTO::class);
        $resolver->setDefault('validation_groups', static function (FormInterface $form) {
            /** @var ItemDTO $data */
            $data = $form->getData();

            $groups = ['Default', Item::NAMES[$data->type]];

            if (null !== $data->parent) {
                $groups[] = 'Child';
            }

            return $groups;
        });
    }
}
