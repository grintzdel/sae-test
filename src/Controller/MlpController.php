<?php

namespace App\Controller;

use App\Service\MlpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MlpController extends AbstractController
{
    #[Route('/mlp', name: 'Mlp')]
    public function test(Request $request, MlpService $modelTestService): Response
    {
        if ($request->isMethod('POST') && $request->files->get('image')) {
            $uploadedFile = $request->files->get('image');

            if ($uploadedFile instanceof UploadedFile) {
                if (!$uploadedFile->isValid() || !in_array($uploadedFile->getMimeType(), ['image/png', 'image/jpeg'])) {
                    $this->addFlash('error', 'Veuillez téléverser une image valide au format PNG ou JPEG.');
                    return $this->redirectToRoute('Mlp');
                }

                $imagePath = $uploadedFile->getPathname();

                try {
                    $prediction = $modelTestService->testImage($imagePath);
                    $this->addFlash('success', "Prédiction du modèle MultiLayerPerceptron : $prediction");
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Le fichier téléversé n\'est pas valide.');
                return $this->redirectToRoute('Mlp');
            }
        }

        return $this->render('mlp.html.twig');
    }
}