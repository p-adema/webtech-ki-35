<?php

require_once "api_resolve.php";
require_once "pdo_read.php";

class Cart
{
    private string $sql_tag;
    private string $sql_price;
    private string $sql_video_long;
    private string $sql_type;
    private string $sql_course_long;
    private string $sql_owned_video_prices;
    private string $sql_course_videos;
    private string $sql_course_total;
    private PDOStatement|false $p_tag;
    private PDOStatement|false $p_course_total;
    private PDOStatement|false $p_price;
    private PDOStatement|false $p_type;
    private PDOStatement|false $p_video_long;
    private PDOStatement|false $p_course_long;
    private PDOStatement|false $p_course_videos;
    private PDOStatement|false $p_owned_item_prices;



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

        $this->sql_course_total = 'SELECT SUM(v.price) as price
FROM course_videos cv
         INNER JOIN items v on v.tag = cv.video_tag
        INNER JOIN items c on cv.course_tag = c.tag
         WHERE c.id = :course_id
';
        $this->p_course_total = prepare_readonly($this->sql_course_total);

        $this->sql_tag = 'SELECT id FROM db.items WHERE (tag = :tag)';
        $this->p_tag = prepare_readonly($this->sql_tag);

        $this->sql_price = 'SELECT price, type FROM db.items WHERE (id = :id)';
        $this->p_price = prepare_readonly($this->sql_price);

        $this->sql_type = 'SELECT type FROM items WHERE (id = :id)';
        $this->p_type = prepare_readonly($this->sql_type);

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
        $this->p_video_long = prepare_readonly($this->sql_video_long);

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
        $this->p_course_long = prepare_readonly($this->sql_course_long);

        $this->sql_course_videos = 'SELECT v.id 
                                    FROM course_videos AS c
                                    INNER JOIN items i ON i.id = :id
                                    INNER JOIN items v on c.video_tag = v.tag
                                    WHERE c.course_tag = i.tag';
        $this->p_course_videos = prepare_readonly($this->sql_course_videos);

        $this->sql_owned_video_prices = '                SELECT SUM(v.price) as price
                 FROM course_videos cv 
               INNER JOIN items v on v.tag = cv.video_tag
               INNER JOIN ownership o on o.item_tag = v.tag
                 INNER JOIN items c on c.tag = cv.course_tag
                WHERE o.user_id = :uid and c.id = :course_id';
        $this->p_owned_item_prices = prepare_readonly($this->sql_owned_video_prices);

    }


    /**
     * @param string $tag items.tag
     * @return int|false items.id
     */
    public function item_id_from_tag(string $tag): int|false
    {
        $this->p_tag->execute(['tag' => $tag]);
        return $this->p_tag->fetch(PDO::FETCH_ASSOC)['id'];
    }

    /**
     * Fetches all course videos from a given course ID
     * @param int $course_id courses.id
     * @return array videos.id array
     */
    public function course_videos(int $course_id): array
    {
        $this->p_course_videos->execute(['id' => $course_id]);
        $videos = $this->p_course_videos->fetchAll(PDO::FETCH_ASSOC);
        return $videos ?: [];
    }

    /**
     * Add an item to the session cart
     * @param int $item_id items.id
     * @return bool success of addition
     */
    public function add_item(int $item_id): bool
    {
        if (!in_array($item_id, $_SESSION['cart']['ids'])) {
            $price = $this->item_price_from_id($item_id);

            if ($price !== false) {
                $_SESSION['cart']['ids'][] = $item_id;
                $_SESSION['cart']['count'] += 1;

                $_SESSION['cart']['total'] += $price;
                $_SESSION['cart']['prices'][$item_id] = $price;

                foreach ($this->course_videos($item_id) as $video_id) {
                    $this->remove_item($video_id['id']);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $item_id items.id
     * @return float|false Video price, or (discounted) course price
     */
    private function item_price_from_id(int $item_id): float|false
    {
        $this->p_price->execute(['id' => $item_id]);
        $item = $this->p_price->fetch(PDO::FETCH_ASSOC);
        if ($item['type'] === 'course'){
            if ($_SESSION['auth']) {
                $user_id = $_SESSION['uid'];
                $this->p_owned_item_prices->execute(['uid' => $user_id, 'course_id' => $item_id]);
                $owned_total = $this->p_owned_item_prices->fetch()['price'];
                $this->p_course_total->execute(['course_id' => $item_id]);
                $total_price = $this->p_course_total->fetch()['price'];
                $course['price'] = $item['price'] * (1 - ($owned_total / $total_price));
                $total = round($course['price'], 2);
            } else {
                $total = $item['price'];
            }
            return $total;
        } else {
            return $item['price'];
        }
    }

    /**
     * @param string $tag items.tag
     * @return float|false Video price, or (discounted) course price
     */
    public function item_price_from_tag(string $tag): float|false
    {
        return $this->item_price_from_id($this->item_id_from_tag($tag));
 }
    /**
     * Remove an item from the session cart
     * @param int $item_id items.id
     * @return bool success of removal
     */
    public function remove_item(int $item_id): bool
    {
        if (in_array($item_id, $_SESSION['cart']['ids'])) {
            unset($_SESSION['cart']['ids'][array_search($item_id, $_SESSION['cart']['ids'])]);
            $_SESSION['cart']['count'] -= 1;
            $_SESSION['cart']['total'] -= $_SESSION['cart']['prices'][$item_id];
            unset($_SESSION['cart']['prices'][$item_id]);
            return true;
        }
        return false;

    }

    /**
     * Get the items.id values of the items in the session cart
     * @return array of items.id values
     */
    public function ids(): array
    {
        return $_SESSION['cart']['ids'];
    }

    /**
     * Get the total price of the cart
     * @return float total price of cart
     */
    public function total(): float
    {
        return $_SESSION['cart']['total'];
    }

    /**
     * Get the full information array of an item
     * @param int $item_id items.id
     * @return array Information about a video or course
     */
    public function item_long(int $item_id): array
    {
        if ($this->item_type_from_id($item_id) === 'video') {
            $video = $this->video_long($item_id);
            $video['type'] = 'video';
            return $video;
        } else {
            $course = $this->course_long($item_id);
            $course['type'] = 'course';
            return $course;
        }
    }

    /**
     * @return array Full information arrays for every item in the cart
     */
    public function items_long(): array
    {
        $items = [];
        foreach ($_SESSION['cart']['ids'] as $id) {
            $items[] = $this->item_long($id);
        }

        return $items;
    }

    /**
     * @param int $item_id items.id
     * @return string 'video' or 'course'
     */
    private function item_type_from_id(int $item_id): string
    {
        $this->p_type->execute(['id' => $item_id]);
        return $this->p_type->fetch(PDO::FETCH_ASSOC)['type'];
    }

    /**
     * Gets the full information array of a video
     * @param int $item_id items.id
     * @return array   i.tag,
                       u.name AS uploader,
                       v.name,
                       price,
                       description,
                       subject,
                       upload_date,
                       views
     */
    public function video_long(int $item_id): array
    {
        $this->p_video_long->execute(['id' => $item_id]);
        return $this->p_video_long->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Gets the full information array of a course
     * @param int $item_id items.id
     * @return array  i.tag,
                      u.name AS uploader,
                      c.name,
                      price,
                      description,
                      subject,
                      creation_date,
                      views
     */
    public function course_long(int $item_id): array
    {
        $this->p_course_long->execute(['id' => $item_id]);

        $course = $this->p_course_long->fetch(PDO::FETCH_ASSOC);
        $course['price'] = $this->item_price_from_id($item_id);
        return $course;
    }

    /**
     * Clear the cart
     */
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
