<?php namespace Models\Inventory\Services;

use Models\Inventory\Brokers\ProductBroker;
use Models\Inventory\Entities\Product;
use Models\Inventory\Validators\ProductValidator;
use Zephyrus\Application\Form;

class ProductService
{
    public static function readAll(): array
    {
        return Product::buildArray(new ProductBroker()->findAll());
    }

    public static function read(int $productId): ?Product
    {
        return Product::build(new ProductBroker()->findById($productId));
    }

    public static function insert(Form $form): Product
    {
        ProductValidator::assert($form);
        $broker = new ProductBroker();
        $productId = $broker->insert(Product::build($form->buildObject()));
        return self::read($productId);
    }

    public static function update(Product $product, Form $form): Product
    {
        ProductValidator::assert($form);
        $broker = new ProductBroker();
        $broker->update($product, Product::build($form->buildObject()));
        return self::read($product->id);
    }

    public static function remove(Product $old): int
    {
        return new ProductBroker()->delete($old);
    }
}
