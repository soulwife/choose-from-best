<?php

namespace SoulFamily\BestEntityBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


class UploadExternalFile
{
    private $imagesCategoriesDirectory;
    const imageFormats = ["image/png" , "image/jpeg", "image/jpg" ];

    public function __construct($imagesCategoriesDirectory)
    {
        $this->imagesCategoriesDirectory = $imagesCategoriesDirectory;
    }

    public function copyExternalFile($url, $name)
    {
        $testFile = $this->imagesCategoriesDirectory . '/test.png';
        copy($url, $testFile);
        $imageInfo = getimagesize($testFile);
        if ($imageInfo && in_array($imageInfo['mime'], self::imageFormats)) {
            copy($testFile, $this->imagesCategoriesDirectory . '/top' . ucfirst($name) . '.png');
            unlink($testFile);
        }
    }
}