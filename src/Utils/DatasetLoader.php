<?php

namespace App\Utils;

use Rubix\ML\Datasets\Labeled;

class DatasetLoader
{
    public function loadDataset(string $directory): Labeled
    {
        $samples = [];
        $labels = [];

        foreach (range(0, 9) as $label) {
            $labelDir = $directory . '/' . $label;
            $images = glob($labelDir . '/*.png');

            foreach ($images as $imagePath) {
                $image = imagecreatefrompng($imagePath);
                imagefilter($image, IMG_FILTER_GRAYSCALE);

                $width = imagesx($image);
                $height = imagesy($image);

                $pixels = [];
                for ($y = 0; $y < $height; $y++) {
                    for ($x = 0; $x < $width; $x++) {
                        $colorIndex = imagecolorat($image, $x, $y);
                        $color = imagecolorsforindex($image, $colorIndex);
                        $pixels[] = $color['red'] / 255;
                    }
                }

                $samples[] = $pixels;
                $labels[] = (string)$label;

                imagedestroy($image);
            }
        }

        return Labeled::build($samples, $labels);
    }
}