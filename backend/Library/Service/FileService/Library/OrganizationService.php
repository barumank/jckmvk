<?php


namespace Backend\Library\Service\FileService\Library;


use Backend\Models\MySQL\DAO\Organization;
use Phalcon\Http\Request\File;

class OrganizationService extends BaseServiceEntity
{
    const DIRECTORY_NAME = 'organization_images';

    /**
     * @param File $image
     * @return string|null
     * @throws \Exception
     */
    public function saveImageByRequestFile(File $image): ?string
    {
        $imageName = "{$this->generateHash()}.{$image->getExtension()}";

        if (empty($this->userId)) {
            throw new \Exception('Нужно установить пользователя');
        }
        $dir = "{$this->config->path('dirs.users')}/{$this->userId}/" . self::DIRECTORY_NAME;
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
            Organization::MIN_IMAGE_WIDTH,
            Organization::MIN_IMAGE_HEIGHT,
            \Phalcon\Image::AUTO
        )->save();

        return $imageName;
    }

    /**
     * @param string|null $imageName
     * @return bool
     * @throws \Exception
     */
    public function deleteImage(?string $imageName): bool
    {
        if (empty($imageName)) {
            return true;
        }
        if (empty($this->userId)) {
            throw new \Exception('Нужно установить пользователя');
        }
        $dir = "{$this->config->path('dirs.users')}/{$this->userId}/" . self::DIRECTORY_NAME;
        $filePath = "{$dir}/{$imageName}";
        if (!file_exists($filePath)) {
            return true;
        }
        return unlink($filePath);
    }

}