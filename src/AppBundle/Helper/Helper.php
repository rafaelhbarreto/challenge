<?php 

namespace AppBundle\Helper; 

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface; 

class Helper {

  /**
     * Recieves a instance of UploadedFile and try upload it.active
     * The function returns an array with status and mesage of upload.
     * 
     * @param UploadedFile $file
     * @param string $rootDir
     * @throws Exception
     * @return string $fileName
     */
    public static function uploadFile(UploadedFile $file, $rootDir) 
    {
      if(($file instanceof UploadedFile) && $file->getError() == 0) 
      {
        if($file->getSize() > 100000) {
          throw new \Exception("The file size is too big");
        }
        
        if($file->guessExtension() !== 'xml') {
          throw new \Exception("Invalid format to upload");
        }
        // Generate a unique name for the file before saving
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        
        // Move the file to the directory where brochures are stored
        $uploadsDir = $rootDir.'/../web/uploads/xml';
        $file->move($uploadsDir, $fileName);
      }
      
      return $rootDir.'/../web/uploads/xml/'.$fileName; 
  }
}