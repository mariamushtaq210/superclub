<?php
namespace SSC;
class SSC_Venue extends \SSC\SSC_PostType
{
    private $filters = false;

    /**
     * Sets current post type, args.
     */
    protected function setPostTypeArgs()
    {
        $labels = array(
            'name' => 'Venues',
            'singular_name' => 'Venue',
            'menu_name' => 'Venue',
            'name_admin_bar' => 'Venue',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Venue',
            'new_item' => 'New Venue',
            'edit_item' => 'Edit Venue',
            'view_item' => 'View Venue',
            'all_items' => 'All Venues',
            'search_items' => 'Search Venues',
            'parent_item_colon' => 'Parent Venues:',
            'not_found' => 'No Venues found.',
            'not_found_in_trash' => 'No Venues found in Trash.',
        );
        $this->args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 30,
            'hierarchical' => true,
            'supports' => array('title', 'excerpt', 'thumbnail', 'editor'),
        );
    }

    public function format_date($format = 'F Y')
    {
        return \DateTime::createFromFormat('d/m/Y', $this->get_field('date'))
            ->format($format);
    }
}
