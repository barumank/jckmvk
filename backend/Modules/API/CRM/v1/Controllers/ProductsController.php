<?php


namespace Backend\Modules\API\CRM\v1\Controllers;


use Backend\Library\RequestException;
use Backend\Models\MySQL\DAO\AttributeGroup;
use Backend\Models\MySQL\DAO\AttributeProduct;
use Backend\Models\MySQL\DAO\Product;
use Backend\Models\MySQL\DAO\ProductCategory;
use Backend\Models\MySQL\DAO\ProductToAttribute;
use Backend\Models\MySQL\DAO\User;
use Backend\Models\MySQL\Models\ProductSimilarGroup;
use Backend\Modules\API\CRM\v1\Controller;
use Backend\Modules\API\CRM\v1\Validations\ProductSimilarGroupValidation;
use Backend\Modules\API\CRM\v1\Validations\ProductValidation;
use Exception;

class ProductsController extends Controller
{
    /**
     * @AclRoles(admin,user)
     */
    public function IndexAction()
    {
        $fields = $this->request->getQuery('fields', null, []);
        $filter = $this->request->getQuery('filter', null, []);
        $include = $this->request->getQuery('include', null, []);
        if (!empty($include)) {
            $include = explode(',', $include);
        }
        $type = 0;
        if (isset($filter['type'])) {
            $type = (int)$this->filter->sanitize($filter['type'], 'int', 0);
        }
        $page = 1;
        if (isset($filter['page'])) {
            $page = (int)$this->filter->sanitize($filter['page'], 'int', 1);
        }
        $search ='';
        if (isset($filter['search'])) {
            $search = (string)$filter['search'];
        }
        $category = null;
        /**@var ProductCategory $category */
        $userId = $this->auth->getIdentity('user_id');
        if (isset($filter['category_id'])
            && !empty($categoryId = (int)$this->filter->sanitize($filter['category_id'], 'int'))) {
            $category = ProductCategory::findFirst($categoryId);
            if (!empty($category)
                && !$category->typeIsAllowAll()
                && !$category->hasUserId($userId)) {
                $category = null;
            }
        }
        $productColumns = '';
        if (isset($fields['product'])) {
            $productColumns = $fields['product'] ;
        }
        $productAttributeColumns = '';
        if (isset($fields['productAttribute'])) {
            $productAttributeColumns = $fields['productAttribute'];
        }
        $this->productService
            ->setType($type)
            ->setPage($page)
            ->setUserId($this->auth->getIdentity('user_id'))
            ->setSearch($search)
            ->setProductCategory($category)
            ->setProductColumns($productColumns)
            ->setProductAttributeColumns($productAttributeColumns);

        $response = [];
        if (in_array('productAttributes', $include)) {
            $response['productAttributes'] = $this->productService->getProductAttributes();
        }
        if (in_array('attributeNames', $include)) {
            $response['attributeNames'] = $this->productService->getAttributeNames();
        }
        $response['pagination'] = $this->productService->getPagination();
        $response['products'] = $this->productService->getProducts();
        $this->jsonResponse->sendSuccess($response);
    }


    public function GetProductSimilarAction() {
        $fields = $this->request->getQuery('fields', null, []);
        $filter = $this->request->getQuery('filter', null, []);
        $include = $this->request->getQuery('include', null, []);
        if (!empty($include)) {
            $include = explode(',', $include);
        }
        $type = 0;
        if (isset($filter['type'])) {
            $type = (int)$this->filter->sanitize($filter['type'], 'int', 0);
        }
        $productId = null;
        if (isset($filter['product_id'])) {
            $productId = (int)$this->filter->sanitize($filter['product_id'], 'int');
        }

        $groupId = null;
        if (isset($filter['group_id'])) {
            $groupId = (int)$this->filter->sanitize($filter['group_id'], 'int');
        }

        $productColumns = '';
        if (isset($fields['product'])) {
            $productColumns = $fields['product'] ;
        }
        $productAttributeColumns = '';
        if (isset($fields['productAttribute'])) {
            $productAttributeColumns = $fields['productAttribute'];
        }

        $similarProducts = ProductSimilarGroup::find([
            'product_id =:product_id: AND group_id = :group_id:',
            'bind' => [
                'product_id' => $productId,
                'group_id' => $groupId
            ]
        ]);
        $products = $productIdList = [];
        foreach ($similarProducts as $similarProduct) {
            $analog = $similarProduct->getProductAnalog();
            $productIdList[] = $analog->getId();
            $products[] = [
                'id' => $analog->getId(),
                'name' => $analog->getName()
            ];
        }
        $this->productService
            ->setProductIdList($productIdList)
            ->setType($type)
            ->setUserId($this->auth->getIdentity('user_id'))
            ->setProductColumns($productColumns)
            ->setProductAttributeColumns($productAttributeColumns);

        $response = [];
        if (in_array('productAttributes', $include)) {
            $response['productAttributes'] = $this->productService->getProductAttributes();
        }
        if (in_array('attributeNames', $include)) {
            $response['attributeNames'] = $this->productService->getAttributeNames();
        }
        $response['products'] = $products;
        $this->jsonResponse->sendSuccess($response);

    }

    public function GetProductAttributeGroupsAction()
    {
        $response = $groups = [];
        $attributeGroups = AttributeGroup::findByIdList([2, 17]);
        foreach ($attributeGroups as $attributeGroup) {
            $groups[] = [
                'value' => $attributeGroup->getName(),
                'id' => $attributeGroup->getId()
            ];

        }
        $response['productAttributeGroups'] = $groups;
        return $this->jsonResponse->sendSuccess($response);
    }

    /**
     * @AclRoles(admin,user)
     */
    public function SaveAction()
    {
        try {
            if (!$this->request->isPost()) {
                return $this->jsonResponse->sendError('Не верный запрос');
            }
            $post = $this->request->getPost();
            $userId = $this->auth->getIdentity('user_id');
            $productType = Product::TYPE_BASE;
            if ($this->auth->getIdentity('role') === User::ROLE_USER) {
                $productType = Product::TYPE_CUSTOM;
            }
            $validation = new ProductValidation();
            $messages = $validation->validate($post);
            if ($messages->count() > 0) {
                throw (new RequestException('Ошибка'))->setContext($messages);
            }
            $product = new Product();
            $product->setUserId($userId);
            $product->setName($validation->getValue('name'));
            $product->setAmount($validation->getValue('amount'));
            $product->setVendorCode($validation->getValue('vendor_code'));
            $product->setRrc($validation->getValue('rrc'));
            $product->setDiscount($validation->getValue('discount'));
            $product->setType($productType);
            $product->setHash(' ');
            if (!$product->save()) {
                throw new RequestException('Ошибка сохранения');
            }
        } catch (RequestException $e) {
            $messages = $e->getContext();
            return $this->jsonResponse->sendError($e->getMessage(), $messages);
        } catch (Exception $e) {
            return $this->jsonResponse->sendError($e->getMessage());
        }
        return $this->jsonResponse->sendSuccess();
    }

    /**
     * @AclRoles(admin,user)
     */
    public function SaveAnalogGroupAction()
    {
        try {
            if (!$this->request->isPost()) {
                return $this->jsonResponse->sendError('Не верный запрос');
            }
            $post = $this->request->getPost();
            $validation = new ProductSimilarGroupValidation();
            $messages = $validation->validate($post);
            if ($messages->count() > 0) {
                throw (new RequestException('Ошибка'))->setContext($messages);
            }
            $productAnalog = new ProductSimilarGroup();
            $productAnalog->setProductId($validation->getValue('product_id'));
            $productAnalog->setAnalogId($validation->getValue('analog_id'));
            $productAnalog->setGroupId($validation->getValue('group_id'));
            if (!$productAnalog->save()) {
                throw new RequestException('Ошибка сохранения');
            }
        } catch (RequestException $e) {
            $messages = $e->getContext();
            return $this->jsonResponse->sendError($e->getMessage(), $messages);
        } catch (Exception $e) {
            return $this->jsonResponse->sendError($e->getMessage());
        }
        return $this->jsonResponse->sendSuccess();
    }

    public function GetProductAction()
    {
        $response = $result = [];
        $productId = $this->request->get('product_id');
        if ($productId) {
            $product = Product::findFirst($productId);
            if ($product) {
                $result = $product->toArray();
            }
        }
        $response['product'] = $result;
        return $this->jsonResponse->sendSuccess($response);
    }
}