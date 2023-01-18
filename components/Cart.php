<?php

require_once "api_resolve.php";
require_once "pdo_read.php";

class Cart
{
    private PDO $PDO;
    private string $sql_video_long;
    private string $sql_type;
    private PDOStatement|false $p_type;
    private PDOStatement|false $p_video_long;

    public function __construct()
    {
        ensure_session();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [
                'ids' => [],
                'prices' => [],
                'count' => 0,
                'total' => 0
            ];
        }

        $this->PDO = new_pdo_read();
        $this->sql_type = 'SELECT type FROM items WHERE (id = :id)';
        $this->p_type = $this->PDO->prepare($this->sql_type);

        $this->sql_video_long = 'SELECT i.tag,
                                        u.name,
                                        v.name,
                                        price,
                                        description,
                                        subject,
                                        upload_date,
                                        views
                                  FROM items as i
                                  INNER JOIN videos v on i.tag = v.tag
                                  INNER JOIN users u on v.uploader = u.id
                                  WHERE i.id = :id';
        $this->p_video_long =$this->PDO->prepare($this->sql_video_long);
    }

    private function get_type($id): string
    {
        $this->p_type->execute(['id' => $id]);
        return $this->p_type->fetch(PDO::FETCH_ASSOC)['type'];
    }

    /**
     * Add an item to the session cart
     * @param int $id db.items.id to add to cart
     * @param int|null $price Price of item
     * @param bool $price_in_cents whether prices is in cents
     * @return bool success of addition
     */
    public function add_item(int $id, int $price = null, bool $price_in_cents = false): bool
    {
        if (!in_array($id, $_SESSION['cart']['ids'])) {
            if ($price !== null) {
                $_SESSION['cart']['ids'][] = $id;
                $_SESSION['cart']['count'] += 1;

                if ($price_in_cents) {
                    $_SESSION['cart']['total'] += $price;
                    $_SESSION['cart']['prices'][$id] = $price;
                } else {
                    $_SESSION['cart']['total'] += $price * 100;
                    $_SESSION['cart']['prices'][$id] = $price * 100;
                }

            }
            return true;
        }
        return false;
    }

    /**
     * Remove an item from the session cart
     * @param int $id db.items.id to remove from cart
     * @return bool success of removal
     */
    public function remove_item(int $id): bool
    {
        if (in_array($id, $_SESSION['cart']['ids'])) {
            unset($_SESSION['cart']['ids'][array_search($id, $_SESSION['cart']['ids'])]);
            $_SESSION['cart']['count'] -= 1;
            $_SESSION['cart']['total'] -= $_SESSION['cart']['prices'][$id];
            unset($_SESSION['cart']['prices'][$id]);
            return true;
        }
        return false;

    }

    /**
     * Get the db.items.id values of the items in the session cart
     * @return array of db.items.id values
     */
    public function ids(): array
    {
        return $_SESSION['cart']['ids'];
    }

    /**
     * Get the db.items.(id, price) values of the items in the session cart
     * @return array of 'id' and 'price' values for cart items
     */
    public function items_short(): array
    {
        $items = [];
        foreach ($_SESSION['cart']['ids'] as $id) {
            $items[] = ['id' => $id, 'price' => $_SESSION['cart']['prices'][$id]];
        }

        return $items;
    }

    public function item_short(int $id) : array
    {
        return ['id' => $id, 'price' => $_SESSION['cart']['prices'][$id]];
    }

    /**
     * Get the amount of items in the cart
     * @return int amount of items in cart
     */
    public function count(): int
    {
        return $_SESSION['cart']['count'];
    }

    /**
     * Get the total price of the cart
     * @param bool $in_cents whether price should be in euro's or cents
     * @return int|float total price of cart
     */
    public function total(bool $in_cents = false): int|float
    {
        if ($in_cents) {
            return $_SESSION['cart']['total'];
        }
        return $_SESSION['cart']['total'] / 100;
    }

    public function video_long($id)
    {
        $this->p_video_long->execute(['id' => $id]);
        return $this->p_video_long->fetch(PDO::FETCH_ASSOC);
    }

    public function items_long(): array
    {
        $items = [];
        foreach ($_SESSION['cart']['ids'] as $id) {
            if ($this->get_type($id) === 'video') {
                $items[] = $this->video_long($id);
            } else {
                throw new InvalidArgumentException("Courses aren't implemented yet");
                # TODO
            }
        }

        return $items;
    }

}
