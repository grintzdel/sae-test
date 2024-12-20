<?php

namespace App\Controller;

use App\Service\TreeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TreeController extends AbstractController
{
    #[Route('/tree', name: 'Tree')]
    public function test(Request $request, TreeService $modelTestService): Response
    {
        if ($request->isMethod('POST') && $request->files->get('image')) {
            $uploadedFile = $request->files->get('image');

            if ($uploadedFile instanceof UploadedFile) {
                if (!$uploadedFile->isValid() || !in_array($uploadedFile->getMimeType(), ['image/png', 'image/jpeg'])) {
                    $this->addFlash('error', 'Veuillez uploader une image valide.');
                    return $this->redirectToRoute('Tree');
                }

                $imagePath = $uploadedFile->getPathname();

                try {
                    $prediction = $modelTestService->testImage($imagePath);
                    $this->addFlash('success', "Le modèle a prédit le chiffre : $prediction");
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Le fichier téléchargé n\est pas valide. ');
                return $this->redirectToRoute('Tree');
            }
        }

        return $this->render('tree.html.twig');
    }
}
