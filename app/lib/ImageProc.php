<?php

namespace App\Lib;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageProc
{
    /**
     * class for create image of pages at home page.
     *
     * @param string $path_to_orig_image
     * @param string $dir_for_new_img
     * @param string $new_ext            - extension for new image, image type will be change too
     *
     * @return string
     */
    public function imgForPage($path_to_orig_image, $dir_for_new_img, $new_ext = 'jpg')
    {
        if (\file_exists($path_to_orig_image)) {
            $filename = \pathinfo($path_to_orig_image, PATHINFO_FILENAME);
            $new_image = $dir_for_new_img.DIRECTORY_SEPARATOR.$filename.'.'.$new_ext;

            $manager = ImageManager::withDriver(Driver::class);
            $image = $manager->read($path_to_orig_image);
            $image->cover(1024, 640, 'center')->save($new_image);

            return 'Success! Image "'.$new_image.'" has been cropped, resized and saved.';
        } else {
            return 'ERROR! File "'.$path_to_orig_image.'" not exist.';
        }
    }
}
