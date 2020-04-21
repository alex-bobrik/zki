<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\User;
use App\Form\GroupsType;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class GroupsController extends AbstractController
{
    /**
     * @Route("/teacher/groups", name="admin_groups")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, RouterInterface $router)
    {
        $q = $request->get('q');

        if ($q) {
            $groupsQuery = $this->getDoctrine()->getRepository(Groups::class)
                ->createQueryBuilder('l')
                ->select('l')
                ->where('l.name like :name')
                ->setParameter('name', '%'.$q.'%')
                ->getQuery();
        } else {
            $groupsQuery = $this->getDoctrine()->getRepository(Groups::class)
                ->createQueryBuilder('l');
        }

        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $query = $formSearch->get('query')->getData();

            return new RedirectResponse($router->generate('admin_groups', ['q' => $query]));
        }

        $groups = $paginator->paginate(
            $groupsQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('groups/index.html.twig', [
            'controller_name' => 'GroupsController',
            'groups' => $groups,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     * @Route("/teacher/groups/new", name="admin_groups_new")
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
     * @Route("/teacher/groups/edit/{id}", name="admin_groups_edit", requirements={"id"="\d+"})
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
