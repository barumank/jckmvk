<?php
/**
 * Created by PhpStorm.
 * User: danik
 * Date: 08.09.17
 * Time: 9:38
 */

namespace Backend\Modules\Admin\Controllers;

use Backend\Library\DateFormatter;
use Backend\Library\Service\Helpers\BuilderFilters\Helpers\AdminSortIconFactory;
use Backend\Models\MySQL\DAO\UserProperty;
use Backend\Models\MySQL\TDO\User;
use Backend\Modules\Admin\Controller;
use Backend\Modules\Admin\Forms\LoginForm;
use Backend\Modules\Admin\Forms\UserForm;
use Phalcon\Mvc\View;

class UserController extends Controller
{
    public function LoginAction()
    {
        $this->security->hash('123456');
        $form = new LoginForm();

        if (!$this->request->isPost()
            || !$form->isValid($this->request->getPost(), (object)$this->request->getPost())) {
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW)->setVars([
                'form' => $form,
                'user' => $this->auth->getMessages()
            ]);
            return;
        }
        $this->response->redirect('/admin');
    }


    public function LogoutAction()
    {
        $this->auth->logOut();
        $this->response->redirect('/admin/login');
    }

    public function ListAction()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $search = $this->request->getQuery('search', 'string', '');

        $sortField = $this->request->getQuery('field', 'string', '');
        $sort = $this->request->getQuery('sort', 'string', '');

        $builderFilter = $this->userService->getBuilderFilter();
        $builderFilter->setSearch($search);

        $sortHelper = $builderFilter->getSortHelper();
        $sortHelper->setLinkPrefix('/admin/user/list')
            ->setSortFieldQueryName('field')
            ->setSortQueryName('sort')
            ->setSortField($sortField)
            ->setSort($sort);

        $paginate = $this->userService->getPaginate($page);

        $this->paginationService
            ->setPrefix('/admin/user/list')
            ->setPageName('page')
            ->setPaginate($paginate);

        $sortIconFactory = new AdminSortIconFactory();

        $this->view->setVars([
            'paginate' => $paginate,
            'sortHelper' => $sortHelper,
            'dateFormatter' => (new DateFormatter())->setFormat(DateFormatter::FORMAT_USER),
            'user' => new User(),
            'sortIconFactory' => $sortIconFactory
        ]);
    }

    public function EditAction()
    {
        $this->assets
            ->addCss('/admin/vendors/switchery/dist/switchery.min.css')
            ->addJs('/admin/vendors/switchery/dist/switchery.min.js')
            ->addJs('/admin/js/user/form.js');

        $id = $this->dispatcher->getParam(0, 'int');
        $user = $this->userService->getById($id);

        if (empty($user)) {
            $this->response->redirect($this->request->getHTTPReferer());
            return;
        }

        $form = new UserForm($user);
        $form->setAction('/admin/user/edit/' . $id);
        $this->view->setVars([
            'form' => $form,
            'user' => $user,
            'cancelUrl' => '/admin/user/list',
        ]);

        if (!$this->request->isPost()
            || !$form->isValid($this->request->getPost(), $user)
            || !$form->save()) {
            return;
        }
        $this->response->redirect('/admin/user/list');
    }

    public function CreateAction()
    {
        $this->assets
            ->addCss('/admin/vendors/switchery/dist/switchery.min.css')
            ->addJs('/admin/vendors/switchery/dist/switchery.min.js')
            ->addJs('/admin/js/user/form.js');


        $user = new User(new \Backend\Models\MySQL\DAO\User(), new UserProperty());

        $form = new UserForm($user);
        $form->setAction('/admin/user/create');
        $this->view->setVars([
            'form' => $form,
            'user' => $user,
            'cancelUrl' => '/admin/user/list',
        ]);

        if (!$this->request->isPost()
            || !$form->isValid($this->request->getPost(), $user)
            || !$form->save()) {
            return;
        }

        $this->response->redirect('/admin/user/list');
    }

    public function DeleteAction()
    {
        $id = $this->dispatcher->getParam(0, 'int');

        if (empty($this->userService->deleteById($id))) {
            $this->response->redirect($this->request->getHTTPReferer());
            return;
        }

        $this->response->redirect('/admin/user/list');
    }

    public function UnloadingAction()
    {
        $this->userService->unloadingExcel();
    }
}