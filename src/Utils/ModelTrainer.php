<?php

namespace App\Utils;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Estimator;
use App\Model\MLP;
use App\Model\Tree;

class ModelTrainer
{
    private Estimator $estimator;

    public function __construct(string $algorithm = 'tree')
    {
        switch ($algorithm) {
            case 'mlp':
                $this->estimator = new MLP();
                break;
            case 'tree':
                $this->estimator = new Tree();
                break;
            default:
                throw new \InvalidArgumentException("Invalid algorithm: $algorithm");
        }
    }

    public function train(Labeled $trainingDataset): void
    {
        $this->estimator->train($trainingDataset);
    }

    public function saveModel(string $filePath): void
    {
        file_put_contents($filePath, serialize($this->estimator));
    }

    public function getEstimator(): Estimator
    {
        return $this->estimator;
    }
}