<?php

namespace App\GameBundle\Form\Type;

use App\GameBundle\Entity\Round;
use App\GameBundle\Entity\Word;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoundType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('letter', TextType::class)
            ->add('game', EntityType::class, [
                'class' => 'GameBundle:Game'
            ]);
        
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Round $round */
            $round = $event->getData();

            $game = $round->getGame();
            if (null !== $game) {
                foreach ($game->getSubjects() as $subject) {
                    foreach ($game->getUsers() as $user) {
                        $word = new Word();
                        $word->setUser($user);
                        $word->setSubject($subject);

                        $round->addWord($word);
                    }
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\GameBundle\Entity\Round'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'game_round';
    }
}
