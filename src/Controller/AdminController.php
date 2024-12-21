<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MlpModelService;
use App\Service\TreeModelService;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home')]
    public function index(): Response
    {
        return $this->render('admin.html.twig');
    }

    #[Route('/admin/train/{algorithm}', name: 'admin_train')]
    public function train(string $algorithm): Response
    {
        $datasetPath = $this->getParameter('kernel.project_dir') . '/image/training';
        $modelPath = $this->getParameter('kernel.project_dir') . '/public/' . $algorithm . '.rbx';

        if ($algorithm === 'mlp') {
            $service = new MlpModelService();
        } else {
            $service = new TreeModelService();
        }

        $service->train($datasetPath, $modelPath);

        return new Response('Model trained and saved successfully.');
    }

    #[Route('/admin/test/{algorithm}', name: 'admin_test')]
    public function test(string $algorithm): Response
    {
        $datasetPath = $this->getParameter('kernel.project_dir') . '/image/testing';
        $modelPath = $this->getParameter('kernel.project_dir') . '/public/' . $algorithm . '.rbx';

        if ($algorithm === 'mlp') {
            $service = new MlpModelService();
        } else {
            $service = new TreeModelService();
        }

        $results = $service->test($datasetPath, $modelPath);

        return $this->json($results);
    }
}