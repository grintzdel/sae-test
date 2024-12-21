<?php

namespace App\Service;

use Rubix\ML\Datasets\Unlabeled;

class TreeDisplayService
{
    private mixed $model;
    const PATH_MODEL_TREE = __DIR__ . '/../../public/tree.rbx';

    public function __construct()
    {
        $modelPath = self::PATH_MODEL_TREE;

        try {
            if (!file_exists($modelPath)) {
                throw new \RuntimeException("Le fichier du modèle n'existe pas à l'emplacement spécifié : $modelPath");
            }

            $modelContent = file_get_contents($modelPath);
            if ($modelContent === false) {
                throw new \RuntimeException("Erreur lors de la lecture du fichier du modèle.");
            }

            if (strlen($modelContent) === 0) {
                throw new \RuntimeException("Le contenu du fichier du modèle est vide.");
            }

            $this->model = unserialize($modelContent);

            if (!$this->model) {
                throw new \RuntimeException("Erreur lors de la désérialisation du modèle.");
            }
        } catch (\Exception $e) {
            throw new \RuntimeException("Erreur lors du chargement du modèle : " . $e->getMessage());
        }
    }

    public function testImage(string $imagePath): ?int
    {
        if (!file_exists($imagePath)) {
            throw new \RuntimeException("L'image spécifiée n'existe pas : $imagePath");
        }

        $sample = $this->prepareImage($imagePath);
        $dataset = new Unlabeled([$sample]);

        try {
            $prediction = $this->model->predict($dataset);
            return $prediction[0] ?? null;
        } catch (\Exception $e) {
            throw new \RuntimeException("Erreur lors de la prédiction : " . $e->getMessage());
        }
    }

    private function prepareImage(string $imagePath): array
    {
        $imageInfo = getimagesize($imagePath);
        if ($imageInfo === false) {
            throw new \RuntimeException("Le fichier spécifié n'est pas une image valide : $imagePath");
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        if ($width !== 28 || $height !== 28) {
            throw new \RuntimeException("L'image doit être de taille 28x28 pixels.");
        }

        $image = @imagecreatefromstring(file_get_contents($imagePath));
        if (!$image) {
            throw new \RuntimeException("Impossible de charger l'image : $imagePath");
        }

        $pixels = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($image, $x, $y);
                $red = ($rgb >> 16) & 0xFF;
                $green = ($rgb >> 8) & 0xFF;
                $blue = $rgb & 0xFF;
                $gray = ($red + $green + $blue) / 3;
                $pixels[] = $gray / 255;
            }
        }

        imagedestroy($image);

        return $pixels;
    }
}