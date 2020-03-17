<?php

namespace App\Controller;

use App\Entity\Lection;
use App\Entity\Materials;
use App\Form\LectionType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class LectionController extends AbstractController
{
//    /**
//     * @Route("/admin/lection", name="admin_lection")
//     */
//    public function index()
//    {
//        $lections = $this->getDoctrine()->getRepository(Lection::class)->findAll();
//
//        return $this->render('lection/index.html.twig', [
//            'controller_name' => 'LectionController',
//            'lections' => $lections,
//        ]);
//    }

//    /**
//     * @Route("/admin/lection/{id}", name="lection_info", requirements={"id"="\d+"})
//     * @param int $id
//     * @return Response
//     */
//    public function lectionInfo(int $id)
//    {
//        $lection = $this->getDoctrine()->getRepository(Lection::class)->find($id);
//
//        return $this->render('lection/info.html.twig', [
//            'controller_name' => 'LectionController',
//            'lection' => $lection,
//        ]);
//    }

    /**
     * @Route("/admin/lection/new", name="admin_lection_new")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @throws Exception
     */
    public function newLection(Request $request, EntityManagerInterface $em)
    {
        $lection = new Lection();

        $form = $this->createForm(LectionType::class, $lection);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $files = $lection->getMaterials();

            $materials = array();

            foreach ($files as $lectionFile) {
                /** @var UploadedFile $file */
                $file = new UploadedFile($lectionFile->getFileName(), 'q');

                $date = new DateTime('now');
                $date = $date->format('m-d-Y_H-i-m');
                $fileName = $file->getFileName() . '_' . $date . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('files_directory'),
                    $fileName
                );

                $material = new Materials();
                $material->setFileName($fileName);
                $material->setName($lectionFile->getName());

                $materials[] = $material;

                $lection->removeMaterial($lectionFile);
            }

            for ($i = 0; $i < count($materials); $i++) {
                $lection->addMaterial($materials[$i]);
            }

            $videoLink = str_replace('https://www.youtube.com/watch?v=', '', $lection->getVideoLink());

            $lection->setVideoLink($videoLink);
            $lection->setIsComplete(false);

            $em->persist($lection);
            $em->flush();

            return $this->redirect($this->generateUrl('razdel'));
        }

        return $this->render('lection/new.html.twig', [
            'controller_name' => 'LectionController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/lection/delete/{id}", name="admin_lection_delete", requirements={"id"="\d+"})
     * @param int $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteLection(int $id, EntityManagerInterface $em)
    {
        $lection = $this->getDoctrine()->getRepository(Lection::class)->find($id);

        $em->remove($lection);
        $em->flush();

        return $this->redirectToRoute('razdel');
    }

    /**
     * @Route("/admin/lection/change/{id}", name="admin_lection_change", requirements={"id"="\d+"})
     * @param int $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function changeLectionStatus(int $id, EntityManagerInterface $em)
    {
        $lection = $this->getDoctrine()->getRepository(Lection::class)->find($id);

        $lection->setIsComplete(!($lection->getIsComplete()));
        $em->persist($lection);
        $em->flush();

        return $this->redirectToRoute('razdel_lections', ['id'=> $lection->getRazdel()->getId()]);
    }
}
