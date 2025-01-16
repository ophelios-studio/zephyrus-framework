<?php namespace Models\Inventory\Brokers;

use Models\Inventory\Entities\Product;
use stdClass;
use Zephyrus\Database\DatabaseBroker;

class ProductBroker extends DatabaseBroker
{
    public function findAll(): array
    {
        return $this->select("SELECT * FROM product");
    }

    public function findById(int $productId): ?stdClass
    {
        return $this->selectSingle("SELECT * FROM product WHERE id = ?", [$productId]);
    }

    public function insert(Product $product): int
    {
        return $this->selectSingle("INSERT INTO product(provider, brand, name, price) 
                                               VALUES (?, ?, ?, ?) RETURNING id", [
            $product->provider,
            $product->brand,
            $product->name,
            $product->price
        ])->id;
    }

    public function update(Product $old, Product $new): int
    {
        $this->query("UPDATE product 
                               SET provider = ?, brand = ?, name = ?, price = ?
                             WHERE id = ?", [
            $new->provider,
            $new->brand,
            $new->name,
            $new->price,
            $old->id
        ]);
        return $this->getLastAffectedCount();
    }

    public function delete(Product $old): int
    {
        $this->query("DELETE FROM product WHERE id = ?", [$old->id]);
        return $this->getLastAffectedCount();
    }
}
