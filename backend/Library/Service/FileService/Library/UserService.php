<?php


namespace Backend\Library\Service\FileService\Library;

use \Phalcon\Http\Request\File;
use \Phalcon\Config;

/**
 * Class UserFiles
 * @package Backend\Library\Service\FileService\Library
 */
class UserService extends BaseServiceEntity
{

    /**
     * @param File $image
     * @return string|null
     * @throws \Exception
     */
    public function saveLogoByRequestFile(File $image): ?string
    {
        $imageName = "{$this->generateHash()}.{$image->getExtension()}";

        if(empty($this->userId)){
            throw new \Exception('Нужно установить пользователя');
        }
        $dir = "{$this->config->path('dirs.users')}/{$this->userId}";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        $imagePath = "{$dir}/$imageName";
        if (!$image->moveTo($imagePath)) {
            return null;
        }
        $image = new \Phalcon\Image\Adapter\Imagick($imagePath);
        $image->resize(
            400,
            200,
            \Phalcon\Image::AUTO
        )->save();

        return $imageName;
    }

    public function deleteLogo(?string $imageName): bool
    {
        if (empty($imageName)) {
            return true;
        }
        $dir = "{$this->config->path('dirs.users')}/{$this->userId}";
        $filePath = "{$dir}/{$imageName}";
        if (!file_exists($filePath)) {
            return true;
        }
        return unlink($filePath);
    }
}