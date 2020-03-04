<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 14.03.19
 * Time: 15:27
 */

namespace Backend\Library\Traits;

use Phalcon\Http\Request;

/**
 * Trait MoveFile
 * @package Backend\Library\Traits
 * @property Request $request
 */
trait MoveFile
{

    protected function moveImage($fieldName, $path)
    {
        $image = null;
        $imageName = '';
        foreach ($this->request->getUploadedFiles() as $file) {
            if ($fieldName === $file->getKey() && $file->isUploadedFile()) {
                $image = $file;
            }
        }
        if(!is_dir($path)){
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        if (!empty($image)) {
            $imageName = md5(uniqid(true)) . '.' . $image->getExtension();
            $path = "{$path}/{$imageName}";

            if ($image->moveTo($path)) {
                chmod($path, 0777);
            } else {
                $imageName = '';
            }
        }
        return $imageName;
    }
}