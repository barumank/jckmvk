<?php


namespace Backend\Modules\API\CRM\v1\Controllers;

use Backend\Library\RequestException;
use Backend\Library\Service\Auth\Exception;
use Backend\Models\MySQL\DAO\User;
use Backend\Modules\API\CRM\v1\Controller;
use Backend\Modules\API\CRM\v1\Validations\UserSettingsValidation;
use Backend\Modules\API\CRM\v1\Validations\UserValidation;

class UserController extends Controller
{
    public function GetCurrentUserAction()
    {
        /**@var User $user */
        $user = $this->auth->getUser();

        if (empty($user)) {
            return $this->jsonResponse->sendError();
        }

        return $this->jsonResponse->sendSuccess([
            'user' => $user->getUserProps()
        ]);
    }

    /**
     * @AclRoles(admin)
     */
    public function GetUsersAction()
    {
        if (!$this->request->isGet()) {
            return $this->jsonResponse->sendError('Не верный запрос');
        }
        $fields = $this->request->getQuery('fields', null, []);
        $filter = $this->request->getQuery('filter', null, []);

        $userColumns = '';
        if (isset($fields['user']) && !empty($fields['user'])) {
            $userColumns = "{$fields['user']},id";
        }
        $statusList = [];
        if (isset($filter['status']) && !empty($filter['status'])) {
            $statusList = explode(',', $filter['status']);
        }
        $builder = User::getBuilder();
        User::bindFields($builder, $userColumns)
            ->orderBy('u.id DESC');

        if (!empty($statusList)) {
            $builder->andWhere('u.status in ({statusList:array})', [
                'statusList' => $statusList
            ]);
        }
        if (isset($filter['search']) && !empty($filter['search'])) {
            $builder->andWhere('(LOWER(u.name) like :search: or LOWER(u.last_name) like :search: or LOWER(u.email) like :search:)', [
                'search' => '%' . html_entity_decode(mb_strtolower($filter['search'])) . '%',
            ]);
        }
        $this->paginationService->execute($builder, $filter['page'] ?? 1, $filter['page_size'] ?? 30);

        return $this->jsonResponse->sendSuccess([
            'users' => $this->paginationService->getPaginate()->items,
            'pagination' => $this->paginationService->getPagination()
        ]);
    }

    /**
     * @AclRoles(admin)
     */
    public function GetUserAction()
    {
        if (!$this->request->isGet()) {
            return $this->jsonResponse->sendError('Не верный запрос');
        }
        $userId = $this->request->getQuery('userId', 'int', 0);
        if (empty($userId)) {
            return $this->jsonResponse->sendError('Не верный запрос');
        }
        /**@var User $user */
        $user = User::findFirst($userId);
        if (empty($user)) {
            return $this->jsonResponse->sendError('Не верный запрос');
        }
        return $this->jsonResponse->sendSuccess([
            'user' => $user->getUserProps()
        ]);
    }

    /**
     * @AclRoles(admin)
     */
    public function SaveAction()
    {
        if (!$this->request->isPost()) {
            return $this->jsonResponse->sendError('Не верный запрос');
        }
        $post = $this->request->getPost();

        $user = new User();
        if (isset($post['id']) && !empty($post['id'])) {
            $user = User::findFirst($post['id']);
        }
        if ($this->request->hasPost('status')) {
            $user->setStatus($this->request->getPost('status', 'int'));
        }

        if (!$user->save()) {
            return $this->jsonResponse->sendError('Ошибка при сохранении');
        }

        return $this->jsonResponse->sendSuccess([
            'user' => $user->getUserProps()
        ]);
    }

    /**
     * @AclRoles(admin)
     */
    public function SaveUserAction()
    {
        try {
            if (!$this->request->isPost()) {
                throw new RequestException('Не верный запрос');
            }
            $id = $this->request->getPost('id', 'int');
            $post = $this->request->getPost();

            $validation = new UserValidation();
            $messages = $validation->validate($post);
            if ($messages->count() > 0) {
                throw (new RequestException('Не верный запрос'))
                    ->setContext($messages);
            }
            /**@var User $user */
            $user = new User();
            if (!empty($id)) {
                $user = User::findFirst($id);
            }
            if (empty($user)) {
                throw new RequestException('Ошибка при сохранении');
            }
            $user->setEmail($validation->getValue('email'))
                ->setRole($validation->getValue('role'))
                ->setName($validation->getValue('name'))
                ->setLastName($validation->getValue('last_name'));

            if ($this->request->hasPost('password')) {
                $user->setPassword($this->security->hash($validation->getValue('password')));
            }
            if (!$user->save()) {
                throw new RequestException('Ошибка при сохранении');
            }
            if ($this->request->hasFiles('image')) {
                /**@var \Phalcon\Http\Request\File $imageFile */
                $imageFile = current($this->request->getUploadedFiles());
                $this->fileService->setUserId($id);
                $fileUserService = $this->fileService->getUserService();
                $imageName = $fileUserService->saveLogoByRequestFile($imageFile);
                if (empty($imageName)) {
                    throw new RequestException('Ошибка загрузки изображения');
                }
                $oldImage = $user->getImage();
                $fileUserService->deleteLogo($oldImage);
                $user->setImage($imageName)->update();
            }
        } catch (RequestException $exception) {
            $messages = $exception->getContext();
            return $this->jsonResponse->sendError($exception->getMessage(), $messages);
        } catch (\Exception $exception) {
            return $this->jsonResponse->sendError($exception->getMessage());
        }
        return $this->jsonResponse->sendSuccess([
            'user' => $user->getUserProps()
        ]);
    }

    /**
     * @AclRoles(admin,user)
     */
    public function SettingsAction()
    {
        try {
            $validation = new UserSettingsValidation();
            if ($validation->validate($this->request->getPost())->count() > 0) {
                throw (new RequestException($validation->getMessage(['id'])))
                    ->setContext($validation->getMessages(['id']));
            }
            $id = $validation->getValue('id');
            /**@var User $user */
            $user = User::findFirst($id);
            if (empty($user)) {
                throw new RequestException('Ошибка при сохранении');
            }
            $user->setName($validation->getValue('name'))
                ->setEmail($validation->getValue('email'))
                ->setLastName($validation->getValue('last_name'));
            if ($this->request->hasPost('password')) {
                $user->setPassword($this->security->hash($validation->getValue('password')));
            }
            $oldImage = '';
            $imageName = '';
            $fileUserService = $this->fileService->setUserId($id)->getUserService();
            if ($this->request->hasFiles('image')) {
                /**@var \Phalcon\Http\Request\File $imageFile */
                $imageFile = current($this->request->getUploadedFiles());
                $imageName = $fileUserService->saveLogoByRequestFile($imageFile);
                if (empty($imageName)) {
                    throw new RequestException('Ошибка загрузки изображения');
                }
                $oldImage = $user->getImage();
                $user->setImage($imageName);
            }
            $deleteImage = $oldImage;
            $isSave = $user->save();
            if (!$isSave) {
                $deleteImage = $imageName;
            }
            $fileUserService->deleteLogo($deleteImage);
            if (!$isSave) {
                throw new RequestException('Ошибка сохранения');
            }
        } catch (RequestException $exception) {
            $messages = $exception->getContext();
            return $this->jsonResponse->sendError($exception->getMessage(), $messages);
        } catch (\Exception $exception) {
            return $this->jsonResponse->sendError($exception->getMessage());
        }
        return $this->jsonResponse->sendSuccess([
            'user' => $user->getUserProps()
        ]);
    }

}
