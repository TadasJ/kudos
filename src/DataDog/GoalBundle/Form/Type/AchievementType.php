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
class AchievementType extends AbstractType
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
        $builder->add('goal', 'entity', [
            'class' => 'GoalBundle:Goal',
            'choices' => $this->fetchGoalChoices(),
            'empty_value' => 'Please Select',
            'required' => false,
            'multiple' => true,
        ]);

        $builder->add('users', 'entity', [
            'class' => 'UserBundle:User',
            'choices' => $this->fetchEmployeeChoices(),
            'empty_value' => 'Please Select',
            'required' => false,
            'multiple' => true,
        ]);
        $builder->add('confirm', 'submit');

    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'achievement';
    }

    private function fetchGoalChoices()
    {
        $goals = $this->em->getRepository('GoalBundle:Goal')->findAll();
        return $goals;
    }

    private function fetchEmployeeChoices()
    {
        $users = $this->em->getRepository('UserBundle:User')->findAllActiveEmployees();
        return $users;
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