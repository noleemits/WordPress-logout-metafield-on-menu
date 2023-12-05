add_action('admin_head-nav-menus.php', 'ibarra_add_custommenu_metabox');

function ibarra_add_custommenu_metabox() {
    add_meta_box('add-ibarra_custommenu', __('Log Out Link', 'ibarracontadores'), 'ibarra_custommenu_metabox', 'nav-menus', 'side', 'default');
}

function ibarra_custommenu_metabox($object) {
    $menukeywords = array(
        '#ibarra_logout#' => __('Log Out', 'ibarracontadores')
    );

    class IbarraCustomMenuItems {
        public $db_id = 0;
        public $object = 'ibarra_custommenu';
        public $object_id;
        public $menu_item_parent = 0;
        public $type = 'custom';
        public $title;
        public $url;
        public $target = '';
        public $attr_title = '';
        public $classes = array();
        public $xfn = '';
    }

    $menukeywords_obj = array();
    foreach ($menukeywords as $value => $title) {
        $menukeywords_obj[$title] = new IbarraCustomMenuItems();
        $menukeywords_obj[$title]->object_id = esc_attr($value);
        $menukeywords_obj[$title]->title = esc_attr($title);
        $menukeywords_obj[$title]->url = esc_attr($value);
    }

    $walker = new Walker_Nav_Menu_Checklist(array());
    ?>
    <div id="ibarra-custommenu" class="loginlinksdiv">
        <div id="tabs-panel-ibarra-custommenu-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
            <ul id="ibarra-custommenuchecklist" class="list:ibarra-custommenu categorychecklist form-no-clear">
                <?php echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $menukeywords_obj), 0, (object) array('walker' => $walker)); ?>
            </ul>
        </div>
        <p class="button-controls">
            <span class="list-controls">
                <a href="<?php echo admin_url('nav-menus.php?ibarra_custommenu-tab=all&selectall=1#ibarra-custommenu'); ?>" class="select-all aria-button-if-js" role="button"><?php esc_html_e('Select All', 'ibarracontadores'); ?></a>
            </span>
            <span class="add-to-menu">
                <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu', 'ibarracontadores'); ?>" name="add-ibarra-custommenu-menu-item" id="submit-ibarra-custommenu" />
                <span class="spinner"></span>
            </span>
        </p>
    </div>
    <?php
}

add_filter('wp_setup_nav_menu_item', 'ibarra_setup_nav_menu_item');

function ibarra_setup_nav_menu_item($menu_item) {
    $menukeywords = array('#ibarra_logout#');

    if (isset($menu_item->object, $menu_item->url) && !is_admin() && 'custom' == $menu_item->object && in_array($menu_item->url, $menukeywords)) {
        $item_redirect = $_SERVER['REQUEST_URI'];

        if ($menu_item->url === '#ibarra_logout#') {
            $menu_item->url = wp_logout_url($item_redirect);
        }
        
        $menu_item->url = esc_url($menu_item->url);
    }

    return $menu_item;
}
