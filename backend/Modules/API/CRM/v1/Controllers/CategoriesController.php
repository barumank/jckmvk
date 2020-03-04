<?php


namespace Backend\Modules\API\CRM\v1\Controllers;

use Backend\Library\RequestException;
use Backend\Models\MySQL\DAO\Product;
use Backend\Models\MySQL\DAO\ProductCategory;
use Backend\Models\MySQL\DAO\ProductToCategory;
use Backend\Models\MySQL\DAO\User;
use Backend\Modules\API\CRM\v1\Controller;
use Backend\Modules\API\CRM\v1\Validations\ProductCategoryDeleteValidation;
use Backend\Modules\API\CRM\v1\Validations\ProductCategoryValidation;
use function GuzzleHttp\Psr7\str;

class CategoriesController extends Controller
{
    /**
     * @AclRoles(admin,user)
     */
    public function IndexAction()
    {
        $fields = $this->request->getQuery('fields', null, []);
        $filter = $this->request->getQuery('filter', null, []);

        $categoryId = null;
        if (isset($filter['parentCategoryId'])) {
            $categoryId = (int)$this->filter->sanitize($filter['category_id'], 'int');
        }
        $parentCategoryId = null;
        if (isset($filter['parent_id'])) {
            $parentCategoryId = (int)$this->filter->sanitize($filter['parent_id'], 'int');
        }
        $type = ProductCategory::TYPE_ALLOW_ALL;
        if (isset($filter['type'])) {
            $type = (int)$this->filter->sanitize($filter['type'], 'int');
        }
        $search = '';
        if (isset($filter['search'])) {
            $search = (string)$filter['search'];
        }
        $columns = '';
        if (isset($fields['category'])) {
            $columns = "{$fields['category']},id";
        }
        $builder = ProductCategory::getBuilder();
        ProductCategory::bindFields($builder, $columns);
        $userId = $this->auth->getIdentity('user_id');
        $builder->andWhere('(pc.user_id = :userId: or pc.type =:type:)', [
            'userId' => $userId,
            'type' => ProductCategory::TYPE_ALLOW_ALL
        ])->andWhere('pc.type = :customType:', [
            'customType' => $type
        ]);

        if ($categoryId !== null) {
            $builder->andWhere('pc.id = :parentCategoryId:', [
                'parentCategoryId' => $categoryId
            ]);
        }
        if (!empty($parentCategoryId)) {
            $builder->andWhere('pc.parent_id = :parentCategoryId:', [
                'parentCategoryId' => $parentCategoryId
            ]);
        } elseif ($parentCategoryId === 0) {
            $builder->andWhere('pc.parent_id is null');
        }
        if (!empty($search)) {
            ProductToCategory::categoryJoinProductToCategoryAndProduct($builder);
            $builder->andWhere('LOWER(p.name) like :search:', [
                'search' => '%' . html_entity_decode(mb_strtolower($search)) . '%'
            ])->groupBy('pc.id');
        }

        $out = [];
        $result = $builder->getQuery()->execute();
        $this->categoryService->setUserId($userId)
            ->setCategoryType($type);
        foreach ($result as $item) {
            $item = (array)$item;
            $item['hasChildren'] = $this->categoryService
                ->setCategoryId($item['id'])
                ->hasChildren();
            $out[] = $item;
        }

        return $this->jsonResponse->sendSuccess([
            'categories' => $out,
        ]);
    }

    /**
     * @AclRoles(admin,user)
     */
    public function GetParentAction()
    {
        $fields = $this->request->getQuery('fields', null, []);
        $filter = $this->request->getQuery('filter', null, []);
        $categoryId = (int)$this->filter->sanitize($filter['category_id'], 'int');
        if (empty($categoryId)) {
            return $this->jsonResponse->sendSuccess([
                'parents' => []
            ]);
        }
        /**@var ProductCategory $category */
        $category = ProductCategory::findFirst($categoryId);
        if (empty($category)) {
            return $this->jsonResponse->sendSuccess([
                'parents' => []
            ]);
        }
        $this->categoryService->setProductCategory($category);
        $parentIdList = $this->categoryService->getParentIdList();
        if (empty($parentIdList)) {
            return $this->jsonResponse->sendSuccess([
                'parents' => []
            ]);
        }
        $columns = '';
        if (isset($fields['category'])) {
            $columns = $fields['category'];
        }
        $builder = ProductCategory::getBuilder();
        $userId = $this->auth->getIdentity('user_id');
        ProductCategory::bindFields($builder, $columns);
        $builder->andWhere('pc.id in ({idList:array})', [
            'idList' => $parentIdList
        ]);
        $builder->andWhere('(pc.user_id = :userId: or pc.type =:type:)', [
            'userId' => $userId,
            'type' => ProductCategory::TYPE_ALLOW_ALL
        ]);
        $result = $builder->getQuery()->execute();
        $result = $result->toArray();
        $resultMap = [];
        foreach ($result as $item) {
            $resultMap[$item['id']] = $item;
        }
        $out = [];
        foreach ($parentIdList as $item) {
            if (isset($resultMap[$item])) {
                $out[] = $resultMap[$item];
            }
        }
        return $this->jsonResponse->sendSuccess([
            'parents' => $out
        ]);
    }

    /**
     * @AclRoles(admin,user)
     */
    public function getByIdAction()
    {
        $categoryId = $this->request->getQuery('category_id', 'int');
        if (empty($categoryId)) {
            return $this->jsonResponse->sendError('Ошибка запроса');
        }
        $category = ProductCategory::findFirst($categoryId);
        if (empty($category)) {
            return $this->jsonResponse->sendError('Категория не найдена');
        }
        $userId = (int)$this->auth->getIdentity('user_id');
        if ($this->auth->getIdentity('role') === User::ROLE_USER
            && (int)$category->getUserId() !== $userId) {
            return $this->jsonResponse->sendError('Категория не найдена');
        }
        return $this->jsonResponse->sendSuccess([
            'category' => $category
        ]);
    }

    /**
     * @AclRoles(admin,user)
     */
    public function GetByTypeAction()
    {
        $type = (int)$this->request->getQuery('type', 'int');

        $builder = ProductCategory::getBuilder()
            ->columns('pc.id, pc.user_id,pc.parent_id,pc.name,pc.image,pc.type')
            ->andWhere('pc.type = :type:', [
                'type' => $type
            ]);

        return $this->jsonResponse->sendSuccess([
            'list' => $builder->getQuery()->execute()
        ]);
    }

    /**
     * @AclRoles(admin,user)
     */
    public function SaveAction()
    {
        try {
            if (!$this->request->isPost()) {
                throw new RequestException('Не верный запрос');
            }
            $post = $this->request->getPost();
            $userId = $this->auth->getIdentity('user_id');

            $validation = new ProductCategoryValidation();
            $messages = $validation->validate($post);
            if ($messages->count() > 0) {
                throw (new RequestException('Не верный запрос'))
                    ->setContext($messages);
            }
            $category = new ProductCategory();
            if ($this->request->hasPost('id')) {
                $id = $this->request->getPost('id', 'int');
                $category = ProductCategory::findFirst($id);
            }
            $parentId = $validation->getValue('parent_id');
            $category->setUserId($userId)
                ->setParentId(empty($parentId) ? null : $parentId)
                ->setType($validation->getValue('type'))
                ->setName($validation->getValue('name'));
            $oldImage = '';
            $imageName = '';
            $fileService = $this->fileService->setUserId($userId)->getProductCategoryService();
            if ($this->request->hasFiles('image')) {
                /**@var \Phalcon\Http\Request\File $imageFile */
                $imageFile = current($this->request->getUploadedFiles());
                $imageName = $fileService->saveImageByRequestFile($imageFile);
                if (empty($imageName)) {
                    throw new RequestException('Ошибка загрузки изображения');
                }
                $oldImage = $category->getImage();
                $category->setImage($imageName);
            }
            $deleteImage = $oldImage;
            $isSave = $category->save();
            if (!$isSave) {
                $deleteImage = $imageName;
            }
            $fileService->deleteImage($deleteImage);
            if(!$isSave){
                throw new RequestException('Ошибка сохранения');
            }
        } catch (RequestException $exception) {
            $messages = $exception->getContext();
            return $this->jsonResponse->sendError($exception->getMessage(), $messages);
        } catch (\Exception $exception) {
            return $this->jsonResponse->sendError($exception->getMessage());
        }
        return $this->jsonResponse->sendSuccess();
    }

    /**
     * @AclRoles(admin,user)
     */
    public function DeleteAction()
    {
        try {
            if (!$this->request->isDelete()) {
                throw new RequestException('Не верный запрос');
            }
            $validation = new ProductCategoryDeleteValidation();
            $messages = $validation->validate($this->request->getQuery());
            if($messages->count()>0){
                throw (new RequestException($messages->current()->getMessage()));
            }
            $id = $validation->getValue('category_id');
            /**@var ProductCategory $category */
            $category = ProductCategory::findFirst($id);
            if (empty($category)){
                throw new RequestException('Сущность не найдена');
            }
            if(!$category->delete()){
                throw new RequestException('Ошибка при удалении');
            }
            $category->clearCache();

        } catch (RequestException $exception) {
            $messages = $exception->getContext();
            return $this->jsonResponse->sendError($exception->getMessage(), $messages);
        } catch (\Exception $exception) {
            return $this->jsonResponse->sendError($exception->getMessage());
        }
        return $this->jsonResponse->sendSuccess();
    }
}