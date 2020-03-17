<?php

namespace App\Controller;

use App\Entity\Lab;
use App\Entity\LabMaterial;
use App\Entity\LabResult;
use App\Entity\Materials;
use App\Form\LabResultType;
use App\Form\LabType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LabController extends AbstractController
{
    /**
     * @Route("/labs", name="lab")
     */
    public function index()
    {
        $labs = $this->getDoctrine()->getRepository(Lab::class)->findAll();

        return $this->render('lab/index.html.twig', [
            'controller_name' => 'LabController',
            'labs' => $labs,
        ]);
    }

    /**
     * @Route("/labs/{id}", name="lab_info", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function labInfo(int $id)
    {
        $lab = $this->getDoctrine()->getRepository(Lab::class)->find($id);

        return $this->render('lab/lab_info.html.twig', [
            'controller_name' => 'LabController',
            'lab' => $lab,
        ]);
    }

    /**
     * @Route("/admin/labs/new", name="admin_lab_new")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Exception
     */
    public function newLab(Request $request, EntityManagerInterface $em)
    {
        $lab = new Lab();

        $form = $this->createForm(LabType::class, $lab);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $files = $lab->getLabMaterials();

            $materials = array();

            foreach ($files as $labFile) {
                /** @var UploadedFile $file */
                $file = new UploadedFile($labFile->getFileName(), 'q');

                $date = new \DateTime('now');
                $date = $date->format('m-d-Y_H-i-m');
                $fileName = $file->getFileName() . '_' . $date . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('files_directory'),
                    $fileName
                );

                $material = new LabMaterial();
                $material->setFileName($fileName);
                $material->setName($labFile->getName());

                $materials[] = $material;

                $lab->removeLabMaterial($labFile);
            }

            for ($i = 0; $i < count($materials); $i++) {
                $lab->addLabMaterial($materials[$i]);
            }

            $videoLink = str_replace('https://www.youtube.com/watch?v=', '', $lab->getVideoLink());

            $lab->setVideoLink($videoLink);
            $em->persist($lab);
            $em->flush();

            return $this->redirectToRoute('lab');
        }

        return $this->render('lab/new.html.twig', [
            'controller_name' => 'LabController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/labs/delete/{id}", name="admin_labs_delete", requirements={"id"="\d+"})
     * @param int $id
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteLab(int $id, EntityManagerInterface $em)
    {
        $lab = $this->getDoctrine()->getRepository(Lab::class)->find($id);

        $em->remove($lab);
        $em->flush();

        return $this->redirectToRoute('lab');
    }

    /**
     * @Route("/admin/labs/complete", name="admin_lab_complete", requirements={"id"="\d+"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function completeLab(Request $request, EntityManagerInterface $em)
    {
        $labResult = new LabResult();

        $form = $this->createForm(LabResultType::class, $labResult);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $checkLabRes = $this->getDoctrine()->getRepository(LabResult::class)
                ->findOneBy([
                    'lab' => $labResult->getLab(),
                    'user' => $labResult->getUser()
                ]);

            if ($checkLabRes) {
                throw $this->createAccessDeniedException('Такая лабораторная уже засчитана');
            }

            $labResult->setIsComplete(true);

            $em->persist($labResult);
            $em->flush();

            return $this->redirectToRoute('lab');
        }

        return $this->render('lab/lab_complete.html.twig', [
            'controller_name' => 'LabController',
            'form' => $form->createView(),
        ]);
    }
}
