<?php
/**
 * Plugin name: Social Links
 * Author: JH-Devs
 * Description:  A way for admin to manage Social Links
 */
set_value([
    'admin_route' =>'admin',
    'plugin_route' =>'social-links',
    'tables' =>[
        'social_table' => 'social_links',
    ],
]);

 /** check if al tables exist */
 $db = new \Core\Database;

 $tables = get_value()['tables'];
 
if (!$db->table_exists($tables)) {
   dd("Missing database tables in ".plugin_id()." plugin: " . implode(",", $db->missing_tables) ); die;
}


/** set footer page permissions for this plugin */
add_filter('permissions', function($permissions) {

    $permissions[] = 'view_social_link';
    $permissions[] = 'add_social_link';
    $permissions[] = 'edit_social_link';
    $permissions[] = 'delete_social_link'; 
    return $permissions;
});

/** add to footer links */
add_action('header-footer_main_social', function($data) {

    $image = new \Core\Image;
    $links = $data['links'];

    // Pokud $links obsahuje data, provede se renderování
    if (!empty($links)) {
        require plugin_path('views/frontend/social-links.php');
    } 
});

/** add to admin links */
add_filter('basic-admin_before_admin_links', function($links) {

    if(user_can('view_social_links')) {
        $vars = get_value();

        $obj = (object)[];
        $obj->title = 'Social Links ';
        $obj->id = 'social-links';
        $obj->link = ROOT . '/' .$vars['admin_route'].'/'.$vars['plugin_route'];
        $obj->icon = 'fa-solid fa-star';
        $obj->parent = '0';
        $obj->list_order = 99; // Nastavení pozice (čím vyšší hodnota, tím nižší pozice v seznamu)

        $links[] = $obj;
    }

    // Seřadíme odkazy podle 'list_order' pokud existuje
    usort($links, function($a, $b) {
        return ($a->list_order ?? 0) <=> ($b->list_order ?? 0);
    });
    return $links;
});

/** add footer links */
add_filter('header-footer_after_social_links', function($slinks) {

    $vars = get_value();
    $social = new \MainSocial\Social;

    $social->order = 'asc';	
    $social->order_column = 'list_order';	
    $social::$query_id = 'get-social';
    $rows = $social->where(['disabled'=>0]);
    
    $links = empty($links) ? [] : $links;
    $links = array_merge($slinks, $rows);

    return $links;
});
/** run this after a form submit */
add_action('controller', function() {

    $req = new \Core\Request;
    $vars = get_value();

    $admin_route = $vars['admin_route'];
    $plugin_route = $vars['plugin_route'];

    $errors_route = $vars['errors'] ?? [];

    if (URL(1) == $vars['plugin_route'] && $req->posted()) {

        $ses = new \Core\Session;
        $social = new \MainSocial\Social;
        $social_roles_map = new \MainSocial\Social;

        $id = URL(3) ?? null;
        if ($id)
            $row = $social->first(['id' => $id]);

        if (URL(2) == 'add') {
            
            require plugin_path('controllers/add-controller.php');
        } else
        if (URL(2) == 'edit') {
            
            require plugin_path('controllers/edit-controller.php');
        } else
        if (URL(2) == 'delete') {

            require plugin_path('controllers/delete-controller.php');
        } else 
        if (URL(2) == 'delete_all') {

            require plugin_path('controllers/delete-all-controller.php');
        } 
    }       
});

/** displays the view file */
add_action('basic-admin_main_content', function() {

    $ses = new \Core\Session;
    $vars = get_value();

    $admin_route = $vars['admin_route'];
    $plugin_route = $vars['plugin_route'];

    $social = new \MainSocial\Social;
    $all_items = $social->query("select * from social_links");
    
    if (URL(1) == $vars['plugin_route']) {

        $id = URL(3) ?? null;
        if ($id) {

            $social::$query_id = 'get-social';
            $row = $social->first(['id' => $id]);

        }

        if (URL(2) == 'add') {
            
            require plugin_path('views/admin/add.php');
        } else
        if (URL(2) == 'edit') {
            
            require plugin_path('views/admin/edit.php');
        } else
        if (URL(2) == 'delete') {

            require plugin_path('views/admin/delete.php');
        } else
        if (URL(2) == 'view') {

            require plugin_path('views/admin/view.php');
        }  else {
            $limit = 30;
            $pager = new \Core\Pager($limit,1);
            $offset = $pager->offset;

            /** řazení vzestupně */
            $social->order = 'asc';
            $social->order_column = 'list_order';

            $social->limit = $limit;
            $social->offset = $offset; 
            
            $social::$query_id = 'get-social';

            if(!empty($_GET['find']))
			{
				$find = '%' . trim($_GET['find']) . '%';
				$query = "select * from social_links where title like :find limit $limit offset $offset";
				$rows = $social->query($query,['find'=>$find]);
			}else{
				$rows = $social->getAll();
			}
            
            require plugin_path('views/admin/list.php');
        }

    } 
});

/** for manipulating data after a query operation */
add_filter('after_query',function($data){

	
	if(empty($data['result']))
		return $data;

	if(false && $data['query_id'] == 'get-social')
	{
		$role_map = new \MainSocial\Social;
		foreach ($data['result'] as $key => $row) {
			
			$query = "select * from user_roles where disabled = 0 && id in (select role_id from user_roles_map where disabled = 0 && user_id = :user_id)";
            
			$roles = $role_map->query($query,['user_id'=>$row->id]);
			if($roles)
				$data['result'][$key]->roles = array_column($roles, 'role');
			
			/** get user's roles */
			$social_roles_map = new \MainSocial\Social;
				
			$role_ids = $social_roles_map->where(['user_id'=>$row->id,'disabled'=>0]);
			if($role_ids)
				$data['result'][$key]->role_ids = array_column($role_ids, 'role_id');

		}
	} 

	return $data;
});