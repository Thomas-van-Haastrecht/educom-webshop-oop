<?php

class ModelFactory {
    private $crudFactory;

    public function __construct($crudFactory) {
        $this->crudFactory = $crudFactory;
    }

    public function createModel($type) {
        $model = NULL;
        switch ($type) {
            case 'page':
                require_once('models/PageModel.php');
                $model = new PageModel(NULL);
                break;
            case 'user':
                require_once('models/UserModel.php');
                $crud = $this->crudFactory->createCrud('user');
                $model = new UserModel(NULL, $crud);
                break;
            case 'shop':
                require_once('models/ShopModel.php');
                $crud = $this->crudFactory->createCrud('shop');
                $model = new ShopModel(NULL, $crud);
                break;
            case 'rating':
                require_once('models/RatingModel.php');
                $crud = $this->crudFactory->createCrud('rating');
                $model = new RatingModel($crud);
                break;
        }
        return $model;
    }
}

?>
