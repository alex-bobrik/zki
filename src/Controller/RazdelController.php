<?php

namespace App\Controller;

use App\Entity\Lection;
use App\Entity\Razdel;
use App\Form\EditNameFormType;
use App\Form\LectionType;
use App\Form\RazdelType;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class RazdelController extends AbstractController
{
    /**
     * @Route("/razdeli", name="razdel")
     */
    public function index(Request $request, RouterInterface $router)
    {
        $razdels = $this->getDoctrine()->getRepository(Razdel::class)->findAll();

        $q = $request->get('q');
        //dump($q); die;

//        $foundLections = null;
        if ($q) {
            $razdels = $this->getDoctrine()->getRepository(Razdel::class)
                ->createQueryBuilder('l')
                ->select('l')
                ->where('l.name like :name')
                ->setParameter('name', '%'.$q.'%')
                ->getQuery()
                ->getResult();
        }

        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $query = $formSearch->get('query')->getData();

            return new RedirectResponse($router->generate('razdel', ['q' => $query]));
        }

        return $this->render('razdel/index.html.twig', [
            'controller_name' => 'RazdelController',
            'razdels' => $razdels,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     * @Route("/razdeli/{id}", name="razdel_lections", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function lectionsOfRazdel(int $id, PaginatorInterface $paginator, Request $request, RouterInterface $router)
    {
        $razdel = $this->getDoctrine()->getRepository(Razdel::class)->find($id);

        $q = $request->get('q');

        if ($q) {
            $lectionsQuery = $this->getDoctrine()->getRepository(Lection::class)
                ->createQueryBuilder('l')
                ->select('l')
                ->join('l.razdel', 'razdel')
                ->where('l.name like :name')
                ->andWhere('razdel = :razdel')
                ->setParameter('razdel', $razdel)
                ->setParameter('name', '%'.$q.'%')
                ->getQuery();
        } else {
            $lectionsQuery = $this->getDoctrine()->getRepository(Lection::class)
                ->createQueryBuilder('l')
                ->select('l')
                ->join('l.razdel', 'razdel')
                ->where('razdel = :razdel')
                ->setParameter('razdel', $razdel)
                ->getQuery();
        }

        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $query = $formSearch->get('query')->getData();

            return new RedirectResponse($router->generate('razdel_lections', ['q' => $query, 'id' => $id]));
        }

        $lections = $paginator->paginate(
            $lectionsQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('razdel/lections.html.twig', [
            'controller_name' => 'RazdelController',
            'razdel' => $razdel,
            'lections' => $lections,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     * @Route("/razdeli/{id}/lection/{lectId}", name="razdel_lection_info", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function showLection(int $id, int $lectId)
    {
        $lection = $this->getDoctrine()->getRepository(Lection::class)->find($lectId);

        return $this->render('razdel/lection_info.html.twig', [
            'controller_name' => 'RazdelController',
            'lection' => $lection,
        ]);
    }

    /**
     * @Route("/teacher/razdel/new", name="admin_razdel_new")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function newRazdel(Request $request, EntityManagerInterface $em)
    {
        $razdel = new Razdel();

        $form = $this->createForm(RazdelType::class, $razdel);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($razdel);
            $em->flush();

            return $this->redirectToRoute('razdel');
        }

        return $this->render('razdel/new.html.twig', [
            'controller_name' => 'RazdelController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/teacher/razdel/edit/{id}", name="admin_razdel_edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $id
     * @return Response
     */
    public function editRazdel(Request $request, EntityManagerInterface $em, int $id)
    {
        $razdel = $this->getDoctrine()->getRepository(Razdel::class)->find($id);

        $form = $this->createForm(RazdelType::class, $razdel);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($razdel);
            $em->flush();

            return $this->redirectToRoute('razdel_lections', ['id' => $razdel->getId()]);
        }

        return $this->render('razdel/new.html.twig', [
            'controller_name' => 'RazdelController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/teacher/razdel/delete/{id}", name="admin_razdel_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $id
     * @return Response
     */
    public function deleteRazdel(Request $request, EntityManagerInterface $em, int $id)
    {
        $razdel = $this->getDoctrine()->getRepository(Razdel::class)->find($id);

        $em->remove($razdel);
        $em->flush();

        return $this->redirectToRoute('razdel');
    }

    /**
     * @Route("/teacher/lection/edit/{id}", name="admin_lection_edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $id
     * @return Response
     */
    public function editLection(Request $request, EntityManagerInterface $em, int $id)
    {
        /** @var Lection $lection */
        $lection = $this->getDoctrine()->getRepository(Lection::class)->find($id);

        $form = $this->createForm(EditNameFormType::class, ['name' => $lection->getName()]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $name = $form->get('name')->getData();

            $lection->setName($name);
            $em->flush();

            return $this->redirectToRoute('razdel_lection_info', ['lectId' => $id, 'id' => $lection->getRazdel()->getId()]);
        }

        return $this->render('razdel/edit_lection_name.html.twig', [
           'form' => $form->createView(),
        ]);
    }
}
