<?php


namespace Backend\Modules\API\CRM\v1\Controllers;

use Backend\Library\RequestException;
use Backend\Models\MySQL\DAO\Organization;
use Backend\Models\MySQL\DAO\UserToOrganization;
use Backend\Modules\API\CRM\v1\Controller;
use Backend\Modules\API\CRM\v1\Validations\OrganizationValidation;

class OrganizationsController extends Controller
{
    /**
     * @AclRoles(admin,user)
     */
    public function IndexAction()
    {



    }

    /**
     * @AclRoles(admin,user)
     */
    public function SaveAction()
    {
        try {
            $validation = new OrganizationValidation();
            if ($validation->validate($this->request->getPost())->count() > 0) {
                throw (new RequestException($validation->getMessage(['id'])))
                    ->setContext($validation->getMessages(['id']));
            }
            $id = $validation->getValue('id');
            /**@var Organization $organization */
            $organization = Organization::findFirst($id);
            if (empty($organization)) {
                throw new RequestException('Ошибка при сохранении');
            }
            $userId = $this->auth->getIdentity('user_d');
            $organization
                ->setUserId($userId)
                ->setName($validation->getValue('name'))
                ->setInn($validation->getValue('inn'))
                ->setLegalAddress($validation->getValue('legal_address'))
                ->setPostalAddress($validation->getValue('postal_address'))
                ->setCorrespondentAccount($validation->getValue('correspondent_account'))
                ->setPaymentAccount($validation->getValue('payment_account'))
                ->setNameDirector($validation->getValue('name_director'))
                ->setPositionDirector($validation->getValue('position_director'))
                ->setByVirtue($validation->getValue('by_virtue'))
                ->setEmail($validation->getValue('email'))
                ->setPhone($validation->getValue('by_virtue'));

            $oldImage = '';
            $imageName = '';
            $fileOrganizationService = $this->fileService->setUserId($id)->getOrganizationService();
            if ($this->request->hasFiles('image')) {
                /**@var \Phalcon\Http\Request\File $imageFile */
                $imageFile = current($this->request->getUploadedFiles());
                $imageName = $fileOrganizationService->saveImageByRequestFile($imageFile);
                if (empty($imageName)) {
                    throw new RequestException('Ошибка загрузки изображения');
                }
                $oldImage = $organization->getImage();
                $organization->setImage($imageName);
            }
            $deleteImage = $oldImage;
            $rootId = Organization::findRootIdByUserId($userId);
            if(!empty($rootId)){
                $organization->setRootId($rootId);
            }
            $this->db->begin();
            $isSave = $organization->save();
            if (!$isSave) {
                $deleteImage = $imageName;
            }
            if(empty($rootId)){
                $rootId = $organization->getId();
            }
            if(Organization::hasExistsUser($rootId,$userId)){
                $userToOrganization = (new UserToOrganization())
                ->setUserId($userId)
                ->setOrganizationId($rootId);
                if(!$userToOrganization->save()){
                    $this->db->rollback();
                    throw new RequestException('Ошибка сохранения');
                }
            }
            $fileOrganizationService->deleteImage($deleteImage);
            if (!$isSave) {
                $this->db->rollback();
                throw new RequestException('Ошибка сохранения');
            }
            $this->db->commit();
        } catch (RequestException $exception) {
            $messages = $exception->getContext();
            return $this->jsonResponse->sendError($exception->getMessage(), $messages);
        } catch (\Exception $exception) {
            return $this->jsonResponse->sendError($exception->getMessage());
        }
        return $this->jsonResponse->sendSuccess([
            'organization' => $organization
        ]);
    }
}