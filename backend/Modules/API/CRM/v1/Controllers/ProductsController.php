<?php


namespace Backend\Modules\API\CRM\v1\Controllers;


use Backend\Library\RequestException;
use Backend\Models\MySQL\DAO\Product;
use Backend\Models\MySQL\DAO\ProductCategory;
use Backend\Modules\API\CRM\v1\Controller;
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
            $product->setHash('fd');
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
}