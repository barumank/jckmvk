<?php


namespace Backend\Library\Parsers\Teplo3000\Parser;


use Backend\Library\Parsers\Teplo3000\Parser\TDO\Category;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Product;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\ResponseUrl;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Phalcon\Logger\Adapter as LoggerAdapter;

class Parser
{
    /** @var TcpLogger */
    private $logger;
    /** @var Product[] */
    private $products = [];
    /** @var FileProxy */
    private $fileProxy;

    public function __construct(LoggerAdapter $logger)
    {
        $this->logger = $logger;
        $this->fileProxy = new FileProxy();
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function parse(): bool
    {
        $this->logger->notice('Старт парсер');
        $loader = new Loader($this->logger);
        $this->fileProxy->clear();

        $response = $loader->getUrl('/');
        if (empty($response)) {
            $message = 'Ошибка начальной загрузки данных';
            $this->logger->error($message);
            return false;
        }
        /**@var Category[] $mapChildHashByRootCategory */
        $mapChildHashByRootCategory = [];
        /**@var \Generator[] $productUrlsGenerators */
        $productUrlsGenerators = [];
        $rootCategories = $this->getRootCategories($response);
        //загрузка категорий
        foreach ($rootCategories as $category) {
            $response = $loader->getUrl($category->getUrl());
            if (empty($response)) {
                $message = 'Ошибка загрузки главной категории: ' . json_encode($category, JSON_UNESCAPED_UNICODE);
                $this->logger->warning($message);
                continue;
            }
            $params = $this->getFilterValues($response);
            //дочерние категории
            if (empty($params)) {
                $childCategories = $this->getChildCategory($response);
                foreach ($childCategories as $childCategory) {
                    //загрузка дочерних категорий
                    $response = $loader->getUrl($childCategory->getUrl());
                    $params = $this->getFilterValues($response);
                    $response = $loader->postUrl('/result/filtr', $params);
                    $productUrlsGenerators[] = $this->getProductsLinkGenerator($response);
                    $mapChildHashByRootCategory[$childCategory->getHash()] = $category;
                }
                continue;
            }
            //страница всех товатов
            $response = $loader->postUrl('/result/filtr', $params);
            if (empty($response)) {
                $message = 'Ошибка поиска товаров категория: ' . json_encode($category, JSON_UNESCAPED_UNICODE);
                $this->logger->warning($message);
                continue;
            }
            $productUrlsGenerators[] = $this->getProductsLinkGenerator($response);
        }
        //загружаем товары
        $products = [];
        $productParser = new ProductParser();
        $productUrlGenerator = $this->getProductUrlGenerator($productUrlsGenerators);
        $productPageGenerator = $loader->getDataFromUrlGenerator($productUrlGenerator);
        /**@var CategoryComposite */
        $categoryComposite = new CategoryComposite($mapChildHashByRootCategory);
        /** @var Product[][] $imageUrlProductGroupMap*/
        $imageUrlProductGroupMap = [];
        foreach ($productPageGenerator as $responseUrl) {
            /**@var ResponseUrl $responseUrl */
            $response = $responseUrl->getText();
            $product = $productParser
                ->parse($response)
                ->setFileProxy($this->fileProxy);
            $categoryComposite->addProduct($product);
            $imageUrlProductGroupMap[$product->getUrlImage()][] = $product;
            $products[] = $product;
        }

        $imageProductUrlGenerator = $this->getImageProductUrlGenerator($imageUrlProductGroupMap);
        $imageGenerator = $loader->getDataFromUrlGenerator($imageProductUrlGenerator);
        foreach ($imageGenerator as $responseUrl) {
            /**@var ResponseUrl $responseUrl */
            $image = $responseUrl->getText();
            $url = $responseUrl->getUrl();
            if(isset($imageUrlProductGroupMap[$url])){
                foreach ($imageUrlProductGroupMap[$url] as $product){
                    $product->setImage($image);
                }
            }
        }

        //Добиваемся ссылочной целостности категорий внутри продукта
        $products = $categoryComposite->filterCategory($products);
        $this->products = $products;
        return true;
    }


    /**
     * @param string $text
     * @return Category[]
     */
    private function getRootCategories(string $text): array
    {
        $pattern = '#<li>\s+<a\s+href="([^"]+)">([^<]+)</a>\s+<div[^>]+>#usi';
        preg_match_all($pattern, $text, $matches);
        if (empty($matches)) {
            return [];
        }
        $out = [];
        $length = count($matches[0]);
        for ($i = 0; $i < $length; $i++) {
            $out[] = (new Category(trim($matches[2][$i]), trim($matches[1][$i])));
        }
        return $out;
    }

    private function getFilterValues(string $text): array
    {
        $out = [];
        $pattern = '`<form\s+id="form_filtr"[^>]+>(.*?)</form>`usi';
        if (empty(preg_match($pattern, $text, $matches))) {
            return [];
        }
        $formText = $matches[1];
        $pattern = '#<input[^>]+name="([^"]+)"[^>]+value="([^"]+)"[^>]+>#usi';
        preg_match_all($pattern, $formText, $matches);

        $pattern = '#<input[^>]+>#usi';
        preg_match_all($pattern, $formText, $matches);
        $inputs = $matches[0];
        foreach ($inputs as $input) {
            $pattern = '#type="([^"]+)"#usi';
            preg_match($pattern, $input, $matches);
            $type = $matches[1];
            if ($type !== 'text'
                && $type !== 'hidden') {
                continue;
            }
            $pattern = '#name="([^"]+)"#usi';
            preg_match($pattern, $input, $matches);
            $name = $matches[1];
            $pattern = '#value="([^"]+)"#usi';
            preg_match($pattern, $input, $matches);
            $value = $matches[1] ?? '';
            $out[$name] = $value;
        }
        return $out;
    }

    /**
     * @param string $text
     * @return Category[]
     */
    private function getChildCategory(string $text): array
    {
        $out = [];
        $pattern = '#<div\s+class="text"><a href="([^"]+)"[^>]+>(.*?)</a></div>#usi';
        preg_match_all($pattern, $text, $matches);
        if (empty($matches[0])) {
            $pattern = '#<div class="box[^>]+>\s+<a\s+href="([^"]+)"\s+title="([^"]+)">#usi';
            preg_match_all($pattern, $text, $matches);
            $tmp = $matches[1];
            $matches[1] = $matches[2];
            $matches[2] = $tmp;
        }
        $length = count($matches[0]);
        for ($i = 0; $i < $length; $i++) {
            $url = rtrim(trim($matches[1][$i]), '/');
            $out[] = (new Category(trim($matches[1][$i]), $url));
        }
        return $out;
    }

    /**
     * находим все урлы на товары
     * @param string $text
     * @return \Generator|[]string
     */
    private function getProductsLinkGenerator(string $text): \Generator
    {
        $pattern = '#<div\s+class="text">(?:\s+)?<a href="([^"]+)"[^>]+>.*?</a>(?:\s+)</div>#usi';
        preg_match_all($pattern, $text, $matches);
        $length = count($matches[0]);
        for ($i = 0; $i < $length; $i++) {
            yield $matches[1][$i];
        }
    }

    private function getProductUrlGenerator(array $generators)
    {
        foreach ($generators as $generator) {
            yield from $generator;
        }
    }

    public function getImageProductUrlGenerator($imageUrlProductMap)
    {
        foreach ($imageUrlProductMap as $url => $image) {
            yield $url;
        }
    }

    public function tmpClear()
    {
        $this->fileProxy->clear();
    }


}
