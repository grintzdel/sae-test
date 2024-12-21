<?php

namespace App\Model;

use Rubix\ML\Classifiers\MultilayerPerceptron;
use Rubix\ML\NeuralNet\ActivationFunctions\ReLU;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Optimizers\Adam;

class MLP extends MultilayerPerceptron
{
    public function createModelMLP(): MultilayerPerceptron
    {
        parent::__construct();
        return new MultilayerPerceptron([
            new Dense(200),
            new Activation(new ReLU()),
            new Dense(50),
            new Activation(new ReLU()),
        ], 128, new Adam(0.001), 0, 10);
    }
}
