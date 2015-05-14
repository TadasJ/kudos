<?php
namespace DataDog\GoalBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
/**
 * Class GoalType
 * @package DataDog\GoalBundle\Form\Type
 */
class GoalType extends AbstractType
{

    protected $em;
    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'textarea', [
            'label' => 'Description',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter goal description for employees'],
            'required' => false,
        ]);
        $builder->add('points_reward', 'text', [
            'label' => 'Points',
            'attr' => ['class' => 'form-control', 'style' => 'width:100%;', 'placeholder' => 'Enter point reward for completing goal'],
            'required' => false,
        ]);

        $builder->add('is_active', 'checkbox', [
            'label' => 'Is Active',
            'required' => false,
            'constraints' => [
                new Assert\Type('boolean'),
                new Assert\Choice([true, false])
            ]
        ]);

        $builder->add('confirm', 'submit', [
            'attr' => ['class' => 'btn btn-primary']
        ]);

    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'goal';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'update' => false,
        ]);
    }
}