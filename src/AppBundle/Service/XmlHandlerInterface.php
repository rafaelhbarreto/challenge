<?php 

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface XmlHandlerInterface
 * @package AppBundle\Service
 */
interface XmlHandlerInterface
{
    /**
     * Receive the file and handles the upload
     * 
     * @param UploadedFile $file
     * @return void 
     */
    public function storage($filename);
}