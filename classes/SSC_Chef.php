<?php
namespace SSC;
class SSC_Chef extends \SSC\SSC_PostType
{
    private $filters = false;

    /**
     * Sets current post type, args.
     */
    protected function setPostTypeArgs()
    {
        $labels = array(
            'name' => 'Chefs',
            'singular_name' => 'Chef',
            'menu_name' => 'Chef',
            'name_admin_bar' => 'Chef',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Chef',
            'new_item' => 'New Chef',
            'edit_item' => 'Edit Chef',
            'view_item' => 'View Chef',
            'all_items' => 'All Chefs',
            'search_items' => 'Search Chefs',
            'parent_item_colon' => 'Parent Chefs:',
            'not_found' => 'No chefs found.',
            'not_found_in_trash' => 'No chefs found in Trash.',
        );
        $this->args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 30,
            'hierarchical' => true,
            'supports' => array('title', 'excerpt', 'thumbnail'),
        );
    }

    public function format_date($format = 'F Y')
    {
        return \DateTime::createFromFormat('d/m/Y', $this->get_field('date'))
            ->format($format);
    }
}
