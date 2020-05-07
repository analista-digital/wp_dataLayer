<?php
// function needed to obtain primary category
// published on https://www.lab21.gr/blog/wordpress-get-primary-category-post/
function get_post_primary_category($post_id, $term='category', $return_all_categories=false){
    $return = array();

    if (class_exists('WPSEO_Primary_Term')){
        // Show Primary category by Yoast if it is enabled & set
        $wpseo_primary_term = new WPSEO_Primary_Term( $term, $post_id );
        $primary_term = get_term($wpseo_primary_term->get_primary_term());

        if (!is_wp_error($primary_term)){
            $return['primary_category'] = $primary_term;
        }
    }

    if (empty($return['primary_category']) || $return_all_categories){
        $categories_list = get_the_terms($post_id, $term);

        if (empty($return['primary_category']) && !empty($categories_list)){
            $return['primary_category'] = $categories_list[0];  //get the first category
        }
        if ($return_all_categories){
            $return['all_categories'] = array();

            if (!empty($categories_list)){
                foreach($categories_list as &$category){
                    $return['all_categories'][] = $category->term_id;
                }
            }
        }
    }

    return $return;
}

// function needed to obtain user role
// published on https://kellenmace.com/get-current-users-role-in-wordpress/
function km_get_user_role( $user = null ) {
 $user = $user ? new WP_User( $user ) : wp_get_current_user();

 return $user->roles ? $user->roles[0] : 'guest';
}

// Fill initial digitalData with information from WordPress
function populate_datalayer() {
	// get post information, f.i. post id
	global $post;
	$author_id = $post->post_author;
    $postid = get_queried_object_id();
	$post_status = get_post_status($postid);
	$post_title = str_replace(' ', '_', get_the_title($postid));
	// make an unique pageInstance
	$pageInstanceId = $postid . '-' . $post_title . '-' . $post_status;
	// populate pageinfo 
	$is_mobile = wp_is_mobile();
	$sys_env = ($is_mobile == true ? 'mobile' : 'desktop');
	$post_version = get_the_modified_date('dmy'). '_' . get_the_modified_time('gi');
	$post_author = get_the_author_meta( 'nickname', $author_id );
	$published_date = get_the_date('Y-m-d');
	$modified_date = get_the_modified_date('Y-m-d');
	$language = get_locale();
	// populate category
	$categories_ids = wp_get_post_categories($postid);
	$cats = array();
	foreach($categories_ids as $c){
		$cat = get_category( $c );
		$cats[] = array( 'id' => $c, 'name' => $cat->name, 'slug' => $cat->slug );
	}
	$primary_cats =  get_the_category();
    $primary_cat = array();
	$primary_cat[] =  array( 'id' => $primary_cats[0]->term_id, 'name' => $primary_cats[0]->name, 'slug' => $primary_cats[0]->slug );
	$post_type = get_post_type($postid);
	$tags = wp_get_post_tags($postid);
	$post_tags = [];
	foreach($tags as $t){
		array_push($post_tags, $t->name);
	}
	// populate user
	global $blog_id;
	$user = get_current_user_id();
	$auth_status = ($user > 0 ? 'logged-in' : 'logged-out');
	$role = km_get_user_role( $user = $user );
	$blog_name = get_bloginfo( 'name' );
	$hashed_id = hash('sha256', $user . '_' . $blog_name);
	
	?>
	<script>
		digitalData = {
			'pageInstanceID' : '<?php echo $pageInstanceId; ?>',
			'page':{
 				'pageInfo': {
 					'pageID': '<?php echo $postid; ?>',
					'pageName': '<?php echo get_the_title($postid); ?>',
					'destinationURL': '<? echo get_permalink( $postid ) ?: 'unknown/refresh'; ?>',
					'referringURL': '<? echo wp_get_referer(); ?>',
					'sysEnv' : '<?php echo $sys_env; ?>',
					'variant' : '',
					'version' : '<?php echo $post_version; ?>',
					'author' : '<?php echo $post_author; ?>',
					'creationDate' : '<?php echo $published_date; ?>',
					'modificationnDate' : '<?php echo $modified_date; ?>',
					'language' : '<?php echo $language; ?>'
					},
				'category': {
 					'categories' : '<?php echo json_encode($cats); ?>',
					'primaryCategory' : '<?php echo json_encode($primary_cat); ?>',
					'pageType' : '<?php echo $post_type; ?>'
					},
				'tag': {
 					'tags' : '<?php echo json_encode($post_tags); ?>'
					}
			},
			'user': {
 				'auth' : '<?php echo $auth_status; ?>',
				'role' : '<?php echo $role; ?>',
				'hash_id' : '<?php echo $hashed_id; ?>'
				},
			'version': '1.0'
			}
    </script>
<?php 
}
// add_action(where, our populate function, index or priority)
// you can play with the number to place the dataLayer above the library calling your Tag Management System 
add_action( 'wp_head', 'populate_datalayer', 19 );
?>