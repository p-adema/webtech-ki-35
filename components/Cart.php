<?php

require_once "api_resolve.php";
require_once "pdo_read.php";

class Cart
{
    private PDO $PDO;

    private string $sql_tag;
    private string $sql_price;
    private string $sql_video_long;
    private string $sql_type;
    private string $sql_course_long;
    private string $sql_course_videos;
    private PDOStatement|false $p_tag;
    private PDOStatement|false $p_price;
    private PDOStatement|false $p_type;
    private PDOStatement|false $p_video_long;
    private PDOStatement|false $p_course_long;
    private PDOStatement|false $p_course_videos;


    /**
     * Ensure a cart exists in session,
     * create a database connection and prepare all queries
     */
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
        try {
            $this->PDO = new_pdo_read(err_fatal: false);
        } catch (PDOException $e) {
            api_fail('Internal cart error', ['submit' => 'Connecting cart failed']);
        }
        if (!isset($this->PDO)) {
            api_fail('Internal cart error', ['submit' => 'Unknown cart error']);
        }

        $this->sql_tag = 'SELECT id FROM db.items WHERE (tag = :tag)';
        $this->p_tag = $this->PDO->prepare($this->sql_tag);

        $this->sql_price = 'SELECT price FROM db.items WHERE (id = :id)';
        $this->p_price = $this->PDO->prepare($this->sql_price);

        $this->sql_type = 'SELECT type FROM items WHERE (id = :id)';
        $this->p_type = $this->PDO->prepare($this->sql_type);

        $this->sql_video_long = 'SELECT i.tag,
                                        u.name AS uploader,
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
        $this->p_video_long = $this->PDO->prepare($this->sql_video_long);

        $this->sql_course_long = 'SELECT i.tag,
                                        u.name AS uploader,
                                        c.name,
                                        price,
                                        description,
                                        subject,
                                        creation_date,
                                        views
                                  FROM items as i
                                  INNER JOIN courses c on i.tag = c.tag
                                  INNER JOIN users u on c.creator = u.id
                                  WHERE i.id = :id';
        $this->p_course_long = $this->PDO->prepare($this->sql_course_long);

        $this->sql_course_videos = 'SELECT v.id 
                                    FROM course_videos AS c
                                    INNER JOIN items i ON i.id = :id
                                    INNER JOIN items v on c.video_tag = v.tag
                                    WHERE c.course_tag = i.tag';
        $this->p_course_videos = $this->PDO->prepare($this->sql_course_videos);

        if ($this->p_price === false or $this->p_type === false or
            $this->p_tag === false or $this->p_video_long === false) {
            api_fail('Internal cart error', ['submit' => 'Loading cart failed']);
        }
    }

    public function get_id(string $tag): int|false
    {
        $this->p_tag->execute(['tag' => $tag]);
        return $this->p_tag->fetch(PDO::FETCH_ASSOC)['id'];
    }

    public function course_videos(int $id): array
    {
        $this->p_course_videos->execute(['id' => $id]);
        $videos = $this->p_course_videos->fetchAll(PDO::FETCH_ASSOC);
        return $videos ?: [];
    }

    /**
     * Add an item to the session cart
     * @param int $id db.items.id to add to cart
     * @return bool success of addition
     */
    public function add_item(int $id): bool
    {
        if (!in_array($id, $_SESSION['cart']['ids'])) {
            $price = $this->get_price($id);

            if ($price) {
                $_SESSION['cart']['ids'][] = $id;
                $_SESSION['cart']['count'] += 1;

                $_SESSION['cart']['total'] += $price;
                $_SESSION['cart']['prices'][$id] = $price;

                foreach ($this->course_videos($id) as $video_id) {
                    $this->remove_item($video_id['id']);
                }
                return true;
            }
        }
        return false;
    }

    private function get_price($id): float|false
    {
        $this->p_price->execute(['id' => $id]);
        return $this->p_price->fetch(PDO::FETCH_ASSOC)['price'];
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

    public function item_short(int $id): array
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
     * @return float total price of cart
     */
    public function total(): float
    {
        return $_SESSION['cart']['total'];
    }

    public function item_long(int $id)
    {
        if ($this->get_type($id) === 'video') {
            $video = $this->video_long($id);
            $video['type'] = 'video';
            return $video;
        } else {
            $course = $this->course_long($id);
            $course['type'] = 'course';
            return $course;
        }
    }

    public function items_long(): array
    {
        $items = [];
        foreach ($_SESSION['cart']['ids'] as $id) {
            $items[] = $this->item_long($id);
        }

        return $items;
    }

    private function get_type($id): string
    {
        $this->p_type->execute(['id' => $id]);
        return $this->p_type->fetch(PDO::FETCH_ASSOC)['type'];
    }

    public function video_long($id)
    {
        $this->p_video_long->execute(['id' => $id]);
        return $this->p_video_long->fetch(PDO::FETCH_ASSOC);
    }

    public function course_long($id)
    {
        $this->p_course_long->execute(['id' => $id]);
        return $this->p_course_long->fetch(PDO::FETCH_ASSOC);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [
            'ids' => [],
            'prices' => [],
            'count' => 0,
            'total' => 0
        ];
    }


}
