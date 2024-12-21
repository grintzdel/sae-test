<?php

namespace App\Service;

use App\Model\Tree;
use App\Utils\DatasetLoader;
use App\Utils\ModelTrainer;
use App\Utils\ModelTester;

class TreeModelService
{
    private ModelTrainer $trainer;
    private ModelTester $tester;

    public function __construct()
    {
        $this->trainer = new ModelTrainer('tree');
        $this->tester = new ModelTester('tree');
    }

    public function train(string $datasetPath, string $modelPath): void
    {
        $loader = new DatasetLoader();
        $dataset = $loader->loadDataset($datasetPath);
        $this->trainer->train($dataset);
        $this->trainer->saveModel($modelPath);
    }

    public function test(string $datasetPath, string $modelPath): array
    {
        $loader = new DatasetLoader();
        $dataset = $loader->loadDataset($datasetPath);
        $this->tester->loadModel($modelPath);
        return $this->tester->test($dataset);
    }
}