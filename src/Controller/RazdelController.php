<?php

namespace App\Controller;

use App\Entity\Lection;
use App\Entity\Razdel;
use App\Form\RazdelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RazdelController extends AbstractController
{
    /**
     * @Route("/razdeli", name="razdel")
     */
    public function index()
    {
        $razdels = $this->getDoctrine()->getRepository(Razdel::class)->findAll();

        return $this->render('razdel/index.html.twig', [
            'controller_name' => 'RazdelController',
            'razdels' => $razdels,
        ]);
    }

    /**
     * @Route("/razdeli/{id}", name="razdel_lections", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function lectionsOfRazdel(int $id)
    {
        $razdel = $this->getDoctrine()->getRepository(Razdel::class)->find($id);

        return $this->render('razdel/lections.html.twig', [
            'controller_name' => 'RazdelController',
            'razdel' => $razdel,
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
     * @Route("/admin/razdel/new", name="admin_razdel_new")
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
     * @Route("/admin/razdel/edit/{id}", name="admin_razdel_edit", requirements={"id"="\d+"})
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
     * @Route("/admin/razdel/delete/{id}", name="admin_razdel_delete", requirements={"id"="\d+"})
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
}
