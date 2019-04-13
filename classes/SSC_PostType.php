<?php
namespace SSC;
abstract class SSC_PostType
{
    protected $ID, $args;

    /**
     * EAL_PostType constructor.
     * @param int $id
     */
    public function __construct($id = 0)
    {
        $ptype = self::getPostType();
        if (!post_type_exists($ptype)) {
            $this->setPostTypeArgs();
            $this->setup_post_type();
        } else if ($id) {
            $this->ID = $id;
            $this->init();
        } else {
            global $post;
            $this->ID = $post->ID;
            $this->init();
        }
    }

    /**
     * @param $var
     * @return mixed
     */
    public function __get($var)
    {
        $post = $this->getPostObject();
        return $post->$var;
    }

    /**
     * Automatically get post type from class name.
     * Used for static methods as post type var isn't set up.
     * @return string
     */
    protected static function getPostType()
    {
        $class_name = get_called_class();
        $class_name_parts = explode('_', $class_name);
        unset($class_name_parts[0]);
        $post_type = implode('_', $class_name_parts);
        return strtolower($post_type);
    }

    /**
     * @throws Exception
     */
    protected function init()
    {
        if (!$this->ID) {
            return;
        }
        $post_type = self::getPostType();
        if (get_post_type($this->ID) !== $post_type) {
            throw new Exception("Warning: $post_type ID: " . esc_attr($this->ID) . " does not exist!");
        }
    }

    /**
     * @return array|null|WP_Post
     */
    protected function getPostObject()
    {
        $post = get_post($this->ID);
        return $post;
    }

    /**
     * @param gets acf field $field
     * @return mixed|null|void
     * @throws \Exception
     */
    public function get_field($field)
    {
        if (function_exists('get_field')) {
            return get_field($field, $this->ID);
        } else {
            throw new \Exception("ACF must be installed to use PostType::get_field()");
        }
    }

    /**
     * Sets up post type hook
     */
    function setup_post_type()
    {
        add_action('init', array($this, 'register_post_type'));
    }

    /**
     *  Registers post type
     */
    function register_post_type()
    {
        $post_type = self::getPostType();
        register_post_type($post_type, $this->args);
    }

    /**
     * @param $args
     * @return array
     */
    public static function query($args)
    {
        $args['post_type'] = self::getPostType();
        $posts_array = get_posts($args);
        $objects = array();
        foreach ($posts_array as $p) {
            $class = get_called_class();
            $objects[] = new $class($p->ID);
        }
        return $objects;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($this->ID), 'single-post-thumbnail');
        return $image[0];
    }

    protected abstract function setPostTypeArgs();

}