<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Form\GroupsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GroupsController extends AbstractController
{
    /**
     * @Route("/admin/groups", name="admin_groups")
     */
    public function index(EntityManagerInterface $em)
    {
        $groups = $this->getDoctrine()->getRepository(Groups::class)->findAll();

        return $this->render('groups/index.html.twig', [
            'controller_name' => 'GroupsController',
            'groups' => $groups,
        ]);
    }

    /**
     * @Route("/admin/groups/new", name="admin_groups_new")
     */
    public function newGroup(Request $request, EntityManagerInterface $em)
    {
        $group = new Groups();

        $form = $this->createForm(GroupsType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('admin_groups');
        }

        return $this->render('groups/new.html.twig', [
            'controller_name' => 'GroupsController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/groups/edit/{id}", name="admin_groups_edit", requirements={"id"="\d+"})
     */
    public function editGroup(Request $request, EntityManagerInterface $em, int $id)
    {
        $group = $this->getDoctrine()->getRepository(Groups::class)->find($id);

        $form = $this->createForm(GroupsType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('admin_groups');
        }

        return $this->render('groups/edit.html.twig', [
            'controller_name' => 'GroupsController',
            'form' => $form->createView(),
        ]);
    }
}
