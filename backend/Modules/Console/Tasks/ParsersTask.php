<?php


namespace Backend\Modules\Console\Tasks;

use Backend\Library\Parsers\Teplo3000\Adapter\ProductAdapter;
use Backend\Library\Parsers\Teplo3000\Parser\Parser;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Product;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Backend\Models\MySQL\DAO\ProductCategory;
use Backend\Modules\Console\Task;
use Backend\Library\Parsers\SDK\Manager as SaveManager;

class ParsersTask extends Task
{
    public function Teplo3000Action()
    {
        $userId = 2;
        /**@var TcpLogger $logger */
        $logger = $this->di->get('loggerFactory');
        $logger->setLoggerName('teplo3000');
        $parser = new Parser($logger);
        //todo: debug
        //if (!file_exists('tmp.txt')) {
            if (!$parser->parse()) {
                $logger->error('Парсер не сумел разобрать данные');
                return;
            }
            $products = $parser->getProducts();
            //todo: debug
//            file_put_contents('tmp.txt', serialize([
//                'products' => $products,
//            ]));
       // }
        //todo: debug
        //$tmp = unserialize(file_get_contents('tmp.txt'));
        //$products = $tmp['products'];
        $props = [];
        foreach ($products as $product){
            /**@var Product $product*/
            $property = $product->getProperty();
            foreach ($property as $attribute){
                $props[$attribute->getKey()][$attribute->getValue()] =  $attribute->getValue();
            }
        }

        $productAdapter = (new ProductAdapter($products, $userId, ProductCategory::TYPE_ALLOW_ALL))
            ->setLogger($logger);
        $saveManager = new SaveManager($logger);
        $saveManager->setProducts($productAdapter->getProducts());
        if (!$saveManager->save()) {
            $logger->error('Парсер не сумел сохранить данные');
            return;
        }

        $parser->tmpClear();
        $this->categoryService->cacheClearBase();
    }

}
