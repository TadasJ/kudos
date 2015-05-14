<?php

namespace DataDog\GoalBundle\Controller;

use DataDog\GoalBundle\Entity\Achievement;
use DataDog\GoalBundle\Entity\Goal;
use DataDog\GoalBundle\Form\Type\AchievementType;
use DataDog\GoalBundle\Form\Type\GoalType;
use DataDog\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DateTime;

/**
 * Achievement controller.
 *
 * @Route("/achievement")
 */
class AchievementController extends Controller
{
    /**
     * Responsible for creating new Achievement entries
     *
     * @Route("/create", name = "achievement_create")
     * @param Request $request
     * @Template("GoalBundle:Achievement:create.html.twig")
     */
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $achievement = new Achievement();
        $form = $this->createForm(new AchievementType($em), $achievement);
        $form->handleRequest($request);

        if($form->isValid()){
            $achievement = $form->getData();
            //$user->setUsername($form->getData()->getUsername());
            //$user->setPassword($form->getData()->getPassword());
            //$user->setRole($form->getData()->getRole());
            //$user->setFirstName($form->getData()->getFirstName());
            //$user->setLastName($form->getData()->getLastName());
            //$user->setTotalPoints($form->getData()->getTotalPoints());
            //$user->setIsActive($form->getData()->getIsActive());

            $achievement->setManager($achievement->getGoal()->getManager());
            $achievement->setTitle($achievement->getGoal()->getTitle());
            $achievement->setPoints($achievement->getGoal()->getPointsReward());

            $em->persist($achievement);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Achievement successfully created.');
        }

        return $this->render('GoalBundle:Achievement:create.html.twig', [
            'form' => $form->createView(),
            'achievement' => $achievement,
        ]);
    }
}
