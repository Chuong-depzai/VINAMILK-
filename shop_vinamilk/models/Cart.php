<?php
require_once __DIR__ . '/../db.php';

class Cart
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }

    public function addItem($productId, $quantity = 1)
    {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        return true;
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        return true;
    }

    public function removeItem($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return true;
        }
        return false;
    }

    public function getItems()
    {
        if (empty($_SESSION['cart'])) {
            return array();
        }

        $productIds = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $sql = "SELECT * FROM products WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($productIds);
        $products = $stmt->fetchAll();

        $cartItems = array();
        foreach ($products as $product) {
            $product['quantity'] = $_SESSION['cart'][$product['id']];
            $product['subtotal'] = $product['price'] * $product['quantity'];
            $cartItems[] = $product;
        }

        return $cartItems;
    }

    public function getTotal()
    {
        $items = $this->getItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    public function getCount()
    {
        return array_sum($_SESSION['cart']);
    }

    public function clear()
    {
        $_SESSION['cart'] = array();
        return true;
    }
}
