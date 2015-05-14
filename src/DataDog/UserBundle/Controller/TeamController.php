<?php

namespace DataDog\UserBundle\Controller;

use DataDog\UserBundle\Entity\Team;
use DataDog\UserBundle\Entity\User;
use DataDog\UserBundle\Form\Type\TeamType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DateTime;

/**
 * Team controller.
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * Responsible for creating new Team entries
     *
     * @Route("/create", name = "team_create")
     * @param Request $request
     * @Template("UserBundle:Team:create.html.twig")
     */
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $team = new Team();
        $form = $this->createForm(new TeamType($em), $team);
        $form->handleRequest($request);

        if($form->isValid()){
            $team = $form->getData();
            //$user->setUsername($form->getData()->getUsername());
            //$user->setPassword($form->getData()->getPassword());
            //$user->setRole($form->getData()->getRole());
            //$user->setFirstName($form->getData()->getFirstName());
            //$user->setLastName($form->getData()->getLastName());
            //$user->setTotalPoints($form->getData()->getTotalPoints());
            //$user->setIsActive($form->getData()->getIsActive());

            $em->persist($team);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Team '.$team->getName().' successfully created.');
        }

        return $this->render('UserBundle:Team:create.html.twig', [
            'form' => $form->createView(),
            'team' => $team,
        ]);
    }

    /**
     * Responsible for editing Tean entries
     *
     * @Route("/edit/{id}", name = "team_edit")
     * @param Request $request
     * @Template("UserBundle:Team:edit.html.twig")
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('UserBundle:Team')->find($id);

        if(!$team){
            throw $this->createNotFoundException('User entity with ID: '.$id.' does not exist.');
        }

        $form = $this->createForm(new TeamType($em), $team);

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $team = $form->getData();
                //$user->setUsername($form->getData()->getUsername());
                //$user->setPassword($form->getData()->getPassword());
                //$user->setRole($form->getData()->getRole());
                //$user->setFirstName($form->getData()->getFirstName());
                //$user->setLastName($form->getData()->getLastName());
                //$user->setTotalPoints($form->getData()->getTotalPoints());
                //$user->setIsActive($form->getData()->getIsActive());
                $date = new DateTime();
                $team->setUpdateAt($date->setTimestamp(time()));
                $em->persist($team);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Team ' . $team->getName() . ' successfully updated.');
            }
        }

        return $this->render('UserBundle:Team:edit.html.twig', [
            'form' => $form->createView(),
            'user' => $team,
        ]);
    }
}
