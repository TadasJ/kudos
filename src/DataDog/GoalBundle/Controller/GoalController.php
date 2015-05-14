<?php

namespace DataDog\GoalBundle\Controller;

use DataDog\GoalBundle\Entity\Goal;
use DataDog\GoalBundle\Form\Type\GoalType;
use DataDog\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DateTime;

/**
 * Goal controller.
 *
 * @Route("/")
 */
class GoalController extends Controller
{
    /**
     * Displays list of all goals
     * @Route("/goal", name = "goal_index")
     */
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        if($this->getUser()->getRole()->getRole() === 'ROLE_EMPLOYEE'){
            $goals = $em->getRepository('GoalBundle:Goal')->findAllActive();
        }else{
            $goals = $em->getRepository('GoalBundle:Goal')->findAll();
        }

        return $this->render('GoalBundle:Goal:table.html.twig', [
            'goals' => $goals,
        ]);
    }

    /**
     * Responsible for creating new Goal entries
     *
     * @Route("/goal/create", name = "goal_create")
     * @param Request $request
     * @Template("GoalBundle:Goal:create.html.twig")
     */
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $goal = new Goal();
        $form = $this->createForm(new GoalType($em), $goal);
        $form->handleRequest($request);

        if($form->isValid()){
            $goal = $form->getData();
            //$user->setUsername($form->getData()->getUsername());
            //$user->setPassword($form->getData()->getPassword());
            //$user->setRole($form->getData()->getRole());
            //$user->setFirstName($form->getData()->getFirstName());
            //$user->setLastName($form->getData()->getLastName());
            //$user->setTotalPoints($form->getData()->getTotalPoints());
            //$user->setIsActive($form->getData()->getIsActive());

            $em->persist($goal);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Goal '.$goal->getTitle().' successfully created.');
            return $this->redirectToRoute('goal_index');
        }

        return $this->render('GoalBundle:Goal:create.html.twig', [
            'form' => $form->createView(),
            'goal' => $goal,
        ]);
    }

    /**
     * Responsible for editing Goal entries
     *
     * @Route("/goal/edit/{id}", name = "goal_edit")
     * @param Request $request
     * @Template("GoalBundle:Goal:edit.html.twig")
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $goal = $em->getRepository('GoalBundle:Goal')->find($id);

        if(!$goal){
            throw $this->createNotFoundException('Goal entity with ID: '.$id.' does not exist.');
        }

        $form = $this->createForm(new GoalType($em), $goal);

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $goal = $form->getData();
                //$user->setUsername($form->getData()->getUsername());
                //$user->setPassword($form->getData()->getPassword());
                //$user->setRole($form->getData()->getRole());
                //$user->setFirstName($form->getData()->getFirstName());
                //$user->setLastName($form->getData()->getLastName());
                //$user->setTotalPoints($form->getData()->getTotalPoints());
                //$user->setIsActive($form->getData()->getIsActive());
                $date = new DateTime();
                $goal->setUpdateAt($date->setTimestamp(time()));
                $em->persist($goal);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Goal ' . $goal->getTitle() . ' successfully updated.');
                return $this->redirectToRoute('goal_index');
            }
        }

        return $this->render('GoalBundle:Goal:edit.html.twig', [
            'form' => $form->createView(),
            'goal' => $goal,
        ]);
    }
}
