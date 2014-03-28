<?php

	/**
	 * Plugin Name:  Flexible Blogtitle
	 * Plugin URI:   http://www.koljanolte.com/wordpress/plugins/flexible-blogtitle/
	 * Description:  Flexible Blogtitle is a slim WordPress plugin that allows you to define a custom site title for different posts, pages and areas.
	 * Version:      0.1
	 * Author:       Kolja Nolte
	 * Author URI:   http://www.koljanolte.com
	 * License:      GPLv2 or later
	 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
	 */

	/**
	 * Stop script when the file is called directly.
	 */
	if(!function_exists("add_action")) {
		return false;
	}

	/**
	 * Returns basic plugin variables.
	 *
	 * @param string $info
	 *
	 * @return bool
	 */
	function get_flexible_blogtitle_info($info) {
		$infos  = array(
			"name"       => "Flexible Blogtitle",
			"slug"       => "flexible-blogtitle",
			"id"         => "flexible_blogtitle",
			"textdomain" => "flexible_blogtitle"
		);
		$output = false;
		if(isset($infos[$info])) {
			$output = $infos[$info];
		}
		return $output;
	}

	/**
	 * Sets the default settings when plugin is activated.
	 */
	function flexible_blogtitle_init_default_settings() {
		/** Set up default settings and values */
		$default_settings = array(
			"flexible_blogtitle_auto_title"    => "on",
			"flexible_blogtitle_auto_blogname" => "off",
			"flexible_blogtitle_rules"         => array()
		);
		/** Use update_option() to create the default options  */
		foreach($default_settings as $setting => $value) {
			add_option($setting, $value);
		}
	}

	register_activation_hook(__FILE__, "flexible_blogtitle_init_default_settings");

	/**
	 * Loads the text domain for localization.
	 */
	function flexible_blogtitle_init_languages() {
		load_plugin_textdomain(get_flexible_blogtitle_info("textdomain"), false, dirname(plugin_basename(__FILE__)) . "/languages/");
	}

	add_action("init", "flexible_blogtitle_init_languages");

	/**
	 * Returns the flexible blogtitle rules.
	 *
	 * @return mixed|void
	 */
	function get_flexible_blogtitle_rules() {
		$rules = get_option("flexible_blogtitle_rules");
		if(empty($rules)) {
			$output = array();
		}
		else {
			$output = $rules;
		}
		return $output;
	}

	/**
	 * Returns the blogtitle for the current site.
	 *
	 * @return string
	 */
	function get_flexible_blogtitle() {
		$rules  = get_flexible_blogtitle_rules();
		$output = "";
		foreach($rules as $rule) {
			/** Object: Post ID */
			if($rule["object"] == "post_id") {
				$post_id = get_the_ID();
				if($rule["operator"] == "is") {
					if($post_id == $rule["value"]) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "is_not") {
					if($post_id != $rule["value"]) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "contains") {
					if(strstr($post_id, $rule["value"])) {
						$output = $rule["new_blogtitle"];
					}
				}
			}
			/** Object: Page ID */
			elseif($rule["object"] == "page_id") {
				$page_id = get_the_ID();
				if($rule["operator"] == "is") {
					if($page_id == $rule["value"]) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "is_not") {
					if($page_id != $rule["value"]) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "contains") {
					if(strstr($page_id, $rule["value"])) {
						$output = $rule["new_blogtitle"];
					}
				}
			}
			/** Object: Title */
			elseif($rule["object"] == "post_page_title") {
				$title = get_the_title();
				if($rule["operator"] == "is") {
					if($title == $rule["value"]) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "is_not") {
					if($title != $rule["value"]) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "contains") {
					if(strstr($title, $rule["value"])) {
						$output = $rule["new_blogtitle"];
					}
				}
			}
			/** Object: Category name */
			elseif($rule["object"] == "category" || $rule["object"] == "category_id") {
				if($rule["operator"] == "is") {
					if(in_category($rule["value"])) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "is_not") {
					if(!in_category($rule["value"])) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "contains") {
					$show_new_blogtitle = false;
					$categories         = get_the_category(get_the_ID());
					foreach($categories as $category) {
						if($rule["object"] == "category") {
							if(strstr($category->name, $rule["value"])) {
								$show_new_blogtitle = true;
							}
						}
						elseif($rule["object"] == "category_id") {
							if(strstr($category->term_id, $rule["value"])) {
								$show_new_blogtitle = true;
							}
						}
					}
					if($show_new_blogtitle) {
						$output = $rule["new_blogtitle"];
					}
				}
			}
			/** Object: Taxonomy */
			elseif($rule["object"] == "taxonomy") {
				$sanitized_taxonomies = array();
				foreach(get_the_taxonomies() as $taxonomy => $content) {
					array_push($sanitized_taxonomies, $taxonomy);
				}
				if($rule["operator"] == "is") {
					if(in_array($rule["value"], $sanitized_taxonomies)) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "is_not") {
					if(!in_array($rule["value"], $sanitized_taxonomies)) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "contains") {
					$show_new_blogtitle = false;
					foreach($sanitized_taxonomies as $taxonomy) {
						if(strstr($taxonomy, $rule["value"])) {
							$show_new_blogtitle = true;
						}
					}
					if($show_new_blogtitle) {
						$output = $rule["new_blogtitle"];
					}
				}
			}
			/** Object: Term */
			elseif($rule["object"] == "term") {
				$sanitized_terms = array();
				foreach(get_the_taxonomies() as $name => $taxonomy) {
					foreach(get_the_terms(get_the_ID(), $name) as $term) {
						array_push($sanitized_terms, $term->name);
					}
				}
				if($rule["operator"] == "is") {
					if(in_array($rule["value"], $sanitized_terms)) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "is_not") {
					if(!in_array($rule["value"], $sanitized_terms)) {
						$output = $rule["new_blogtitle"];
					}
				}
				elseif($rule["operator"] == "contains") {
					$show_new_blogtitle = false;
					foreach($sanitized_terms as $term) {
						if(strstr($term, $rule["value"])) {
							$show_new_blogtitle = true;
						}
					}
					if($show_new_blogtitle) {
						$output = $rule["new_blogtitle"];
					}
				}
			}
		}
		if(empty($output)) {
			$output = get_bloginfo("title");
		}
		return $output;
	}

	/**
	 *
	 * Displays the blogtitle for the current site.
	 */
	function the_flexible_blogtitle() {
		echo get_flexible_blogtitle();
	}

	/**
	 * Returns the current state of the "Change title automatically" function.
	 *
	 * @return string|void
	 */
	function get_flexible_blogtitle_auto_title() {
		return get_option("flexible_blogtitle_auto_title");
	}

	/**
	 * Returns the current state of the "show title as blogname" function.
	 *
	 * @return string
	 */
	function get_flexible_blogtitle_auto_blogname() {
		return get_option("flexible_blogtitle_auto_blogname");
	}

	/**
	 * @param $title
	 *
	 * @return string
	 */
	function build_flexible_blogtitle_auto_title($title) {
		if(!is_admin() && get_flexible_blogtitle_auto_title() == "on" && get_flexible_blogtitle() != get_bloginfo("name")) {
			$title = get_flexible_blogtitle();
		}
		return $title;
	}

	add_filter("wp_title", "build_flexible_blogtitle_auto_title", 15, 3);

	/**
	 * @param $output
	 * @param $show
	 *
	 * @return string
	 */
	function build_flexible_blogtitle_auto_blogname($output, $show) {
		if(get_flexible_blogtitle_auto_blogname() == "on" && $show == "name") {
			$output = get_flexible_blogtitle();
		}
		return $output;
	}

	add_filter("bloginfo", "build_flexible_blogtitle_auto_blogname", 10, 2);

	/**
	 * Adds "checked" attribute if both parameters are equal.
	 *
	 * @param $checkbox_value
	 * @param $check_value
	 */
	function the_checkbox_state($checkbox_value, $check_value) {
		if($checkbox_value == $check_value) {
			echo ' checked="checked"';
		}
	}

	/**
	 * Returns the success message.
	 *
	 * @param $message
	 *
	 * @return string|void
	 */
	function get_success_message($message) {
		return __($message, get_flexible_blogtitle_info("textdomain"));
	}

	/**
	 * Displays the success message.
	 *
	 * @param $message
	 */
	function the_success_message($message) {
		echo '<div id="message" class="updated"><p><strong>' . get_success_message($message) . '</strong></p></div>';
	}

	/**
	 * Returns the error message.
	 *
	 * @param $message
	 *
	 * @return string|void
	 */
	function get_error_message($message) {
		return __($message, get_flexible_blogtitle_info("textdomain"));
	}

	/**
	 * Displays the error message.
	 *
	 * @param $message
	 */
	function the_error_message($message) {
		echo '<div id="message" class="error"><p><strong>' . get_error_message($message) . '</strong></p></div>';
	}

	/**
	 * Initializes plugin's setting page.
	 */
	function flexible_blogtitle_init_admin_settings_page() {
		/** Creates a new page on the admin interface */
		add_options_page("Flexible Blogtitle " . __("settings", get_flexible_blogtitle_info("textdomain")), get_flexible_blogtitle_info("name"), "manage_options", get_flexible_blogtitle_info("slug"), "flexible_blogtitle_build_admin_settings_page");
	}

	add_action("admin_menu", "flexible_blogtitle_init_admin_settings_page");

	/**
	 * Builds the plugin's settings page.
	 */
	function flexible_blogtitle_build_admin_settings_page() {
		?>
		<style type="text/css">
			.flexible-blogtitle input[type="text"],
			.flexible-blogtitle input[type="button"] {
				height:      28px;
				padding-top: 1px;
			}

			.flexible-blogtitle .add-rule {
				margin-bottom: 10px;
			}

			.flexible-blogtitle .add-rule input[type="number"] {
				width: 40px;
			}

			.flexible-blogtitle .bulk-edit {
				margin-top: 10px;
			}
		</style>
		<div class="wrap flexible-blogtitle">
		<h2><?php echo __("Settings", "default") . " › " . __(get_flexible_blogtitle_info("name"), get_flexible_blogtitle_info("textdomain")); ?></h2>
		<?php
			//update_option("flexible_blogtitle_rules", "");
			if(isset($_POST["save_settings"])) {
				$fields = array(
					"auto_title",
					"auto_blogname"
				);
				foreach($fields as $option) {
					if(isset($_POST[$option])) {
						$value = $_POST[$option];
					}
					else {
						$value = "off";
					}
					update_option("flexible_blogtitle_" . $option, $value);
				}
				the_success_message("The settings have been saved.");
			}
			if(isset($_POST["add_rule"])) {
				$duplicate_found = false;
				$new_rule        =
					array(
						"id"            => $_POST["id"],
						"object"        => $_POST["object"],
						"operator"      => $_POST["operator"],
						"value"         => $_POST["value"],
						"new_blogtitle" => $_POST["new_blogtitle"]
					);
				$old_rules       = get_option("flexible_blogtitle_rules");
				if(empty($old_rules)) {
					$merged_rules = array();
				}
				else {
					$merged_rules = $old_rules;
					foreach($old_rules as $rule) {
						if($rule["object"] == $_POST["object"] && $rule["operator"] == $_POST["operator"] && $rule["value"] == $_POST["value"]) {
							$duplicate_found = true;
						}
					}
				}
				if(!$duplicate_found && !empty($new_rule["object"]) && !empty($new_rule["operator"]) && !empty($new_rule["value"]) && !empty($new_rule["new_blogtitle"])) {
					array_push($merged_rules, $new_rule);
					if(update_option("flexible_blogtitle_rules", $merged_rules)) {
						the_success_message("The rule has been added.");
					}
					else {
						the_error_message("The rule could not be added. Please try again.");
					}
				}
			}
			/** Delete clicked rule */
			if(isset($_GET["delete_rule_id"])) {
				$new_rules = array();
				$old_rules = get_option("flexible_blogtitle_rules");
				foreach($old_rules as $old_rule) {
					if($_GET["delete_rule_id"] != $old_rule["id"]) {
						array_push($new_rules, $old_rule);
					}
				}
				if(update_option("flexible_blogtitle_rules", $new_rules)) {
					the_success_message("The selected rule has been deleted.");
				}
				else {
					the_error_message("The selected rule could not be deleted. Please try again.");
				}
			}
			/** Delete selected rule(s) */
			if(isset($_POST["table_entries"])) {
				$updated = false;
				foreach($_POST["table_entries"] as $id => $value) {
					$new_rules = array();
					$old_rules = get_option("flexible_blogtitle_rules");
					foreach($old_rules as $old_rule) {
						/** Only keep the rules that are NOT among the deleted one(s) */
						if($id != $old_rule["id"]) {
							array_push($new_rules, $old_rule);
						}
					}
					if(update_option("flexible_blogtitle_rules", $new_rules)) {
						$updated = true;
					}
					else {
						$updated = false;
					}
				}
				if($updated) {
					the_success_message("The selected rules have been deleted.");
				}
				else {
					the_error_message("The selected rules could not be deleted. Please try again.");
				}
			}
		?>
		<form method="post" action="">
			<table class="form-table settings">
				<tr>
					<th><label for=""><?php _e("Settings", get_flexible_blogtitle_info("textdomain")); ?></label>
					</th>
					<td>
						<p>
							<input type="checkbox" name="auto_title" id="auto_title" value="on"<?php the_checkbox_state("on", get_option("flexible_blogtitle_auto_title")); ?> />
							<label for="auto_title"><?php _e("Automatically show in page title", get_flexible_blogtitle_info("textdomain")); ?></label>

						<p class="description"><?php echo sprintf(__("If this function is not set, you have to insert <code>&lt;?php the_flexible_blogtitle(); ?&gt;</code> manually in the <code>header.php</code> of your theme.<br />See <a href=\"%s\" title=\"Open readme.txt\">readme.txt</a> or the <a href=\"http://www.koljanolte.com/wordpress/plugins/flexible-blogtitle/\" title=\"See online documentary\">online documentary</a> for more information.", get_flexible_blogtitle_info("textdomain")), plugin_dir_url(__FILE__) . "readme.txt"); ?></p>
						<br />

						<p>
							<input type="checkbox" name="auto_blogname" id="auto_blogname" value="on"<?php the_checkbox_state("on", get_option("flexible_blogtitle_auto_blogname")); ?> />
							<label for="auto_blogname"><?php _e("Replace blogname with the current title", get_flexible_blogtitle_info("textdomain")); ?></label>

						<p class="description"><?php _e('Replaces the name of the blog set in <a href="options-general.php" title="">Settings › General</a> with the title for the current site.', get_flexible_blogtitle_info("textdomain")); ?></p>

						<p>
							<br /><input type="submit" name="save_settings" class="button-primary" value="<?php _e("Save changes", get_flexible_blogtitle_info("textdomain")); ?>" />
						</p>
					</td>
				</tr>
			</table>
		</form>
		<form method="post" action="">
			<table class="form-table add-rule">
				<tr>
					<th><label for="object"><?php _e("New rule", get_flexible_blogtitle_info("textdomain")); ?></label>
					</th>
					<td>
						<label for="object"><?php _e("If", get_flexible_blogtitle_info("textdomain")); ?> </label>
						<select name="object" id="object">
							<option value="">- <?php _e("Object", get_flexible_blogtitle_info("textdomain")); ?> -</option>
							<option value="post_id"><?php _e("Post ID", get_flexible_blogtitle_info("textdomain")); ?></option>
							<option value="page_id"><?php _e("Page ID", get_flexible_blogtitle_info("textdomain")); ?></option>
							<option value="post_page_title"><?php _e("Post/page title", get_flexible_blogtitle_info("textdomain")); ?></option>
							<option value="category"><?php _e("Category", get_flexible_blogtitle_info("textdomain")); ?></option>
							<option value="taxonomy"><?php _e("Taxonomy", get_flexible_blogtitle_info("textdomain")); ?></option>
							<option value="term"><?php _e("Term", get_flexible_blogtitle_info("textdomain")); ?></option>
						</select>
						<label for="operator"></label>
						<select name="operator" id="operator">
							<option value="">- <?php _e("Operator", get_flexible_blogtitle_info("textdomain")); ?> -</option>
							<option value="is"><?php _e("is", get_flexible_blogtitle_info("textdomain")); ?></option>
							<option value="is_not"><?php _e("is not", get_flexible_blogtitle_info("textdomain")); ?></option>
							<option value="contains"><?php _e("contains", get_flexible_blogtitle_info("textdomain")); ?></option>
						</select>
						<label for="value-category"></label>
						<select id="value-category" class="dynamic" name="value_category" hidden="hidden">
							<option value="">- <?php _e("Category", get_flexible_blogtitle_info("textdomain")); ?> -</option>
							<?php
								foreach(get_categories(array("hide_empty" => false)) as $category) {
									?>
									<option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
								<?php
								}
							?>
						</select>
						<label for="value-taxonomy"></label>
						<select id="value-taxonomy" name="value_taxonomy" class="dynamic" hidden="hidden">
							<option value="">- <?php _e("Taxonomy", get_flexible_blogtitle_info("textdomain")); ?> -</option>
							<?php
								$args = array(
									"public" => true
								);
								$taxonomies_for_terms = array();
								foreach(get_taxonomies($args, "object") as $taxonomy) {
									array_push($taxonomies_for_terms, $taxonomy->name);
									?>
									<option value="<?php echo $taxonomy->name; ?>"><?php echo $taxonomy->labels->name; ?></option>
								<?php
								}
							?>
						</select>
						<label for="value-term"></label>
						<select id="value-term" class="dynamic" name="value_term" hidden="hidden">
							<option value="">- <?php _e("Term", get_flexible_blogtitle_info("textdomain")); ?> -</option>
							<?php
								$terms = get_terms($taxonomies_for_terms, array(
										"hide_empty" => false,
										"orderby"    => "term_group"
									)
								);
								$taxonomy_name = "";
								foreach($terms as $term) {
									$taxonomy           = get_taxonomy($term->taxonomy);
									$this_taxonomy_name = $taxonomy->labels->name;
									if($this_taxonomy_name != $taxonomy_name) {
										$taxonomy_name = $this_taxonomy_name;
										echo '<option value="">- ' . __($taxonomy_name, get_flexible_blogtitle_info("textdomain")) . ' -</option>';
									}
									?>
									<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
								<?php
								}
							?>
						</select>
						<label for="value"></label>
						<input type="text" class="dynamic" name="value_text" id="value" size="10" placeholder="<?php _e("Enter value...", get_flexible_blogtitle_info("textdomain")); ?>" />
						<label for="new_blogtitle"><?php _e("change blogtitle to:", get_flexible_blogtitle_info("textdomain")); ?> </label>
						<input type="text" name="new_blogtitle" id="new_blogtitle" size="20" />
						<?php
							$rule_id = rand(1, 100);
							$rules = get_flexible_blogtitle_rules();
							while(in_array($rule_id, $rules)) {
								$rule_id++;
							}
						?>
						<input type="hidden" name="id" value="<?php echo $rule_id; ?>" />
						<input type="submit" name="add_rule" class="button-primary" value="<?php _e("Add rule", get_flexible_blogtitle_info("textdomain")); ?>" />
				</tr>
			</table>
		</form>
		<form method="post">
			<table class="wp-list-table widefat active-rules">
				<thead>
					<tr>
						<th class="select-all check-column">
							<label for="select-all"></label><input type="checkbox" id="select-all" name="table_entries" title="<?php _e("Select all rules", get_flexible_blogtitle_info("textdomain")); ?>" />
						</th>
						<th><?php _e("Object", get_flexible_blogtitle_info("textdomain")); ?></th>
						<th><?php _e("Operator", get_flexible_blogtitle_info("textdomain")); ?></th>
						<th><?php _e("Value", get_flexible_blogtitle_info("textdomain")); ?></th>
						<th><?php _e("New blogtitle", get_flexible_blogtitle_info("textdomain")); ?></th>
						<th><?php _e("Delete rule", get_flexible_blogtitle_info("textdomain")); ?></th>
					</tr>
				</thead>
				<?php
					$counter = 1;
					$rules = get_flexible_blogtitle_rules();
					if(empty($rules)) {
						$rules = array();
					}
					foreach($rules as $rule) {
						$alternate = "";
						$counter++;
						if($counter % 2 == 0) {
							$alternate = ' alternate';
						}
						?>
						<tr class="entry<?php echo $alternate; ?>">
							<td>
								<label for="checkbox-<?php echo $counter; ?>"></label><input id="checkbox-<?php echo $counter; ?>" type="checkbox" name="table_entries[<?php echo $rule['id']; ?>]" />
							</td>
							<td><?php echo $rule["object"]; ?></td>
							<td><?php echo $rule["operator"]; ?></td>
							<td><?php echo $rule["value"]; ?></td>
							<td><?php echo $rule["new_blogtitle"]; ?></td>
							<td>
								<?php
								?>
								<a href="<?php echo $_SERVER["PHP_SELF"] . "?page=flexible-blogtitle"; ?>&delete_rule_id=<?php echo $rule["id"]; ?>"><?php _e("Delete", get_flexible_blogtitle_info("textdomain")); ?></a>
							</td>
							<?php
							?>
						</tr>
					<?php
					}
				?>
			</table>
			<div class="bulk-edit">
				<label for="bulk-edit"></label>
				<select name="bulk-edit" id="bulk-edit">
					<option value=""><?php _e("Delete selected rules", get_flexible_blogtitle_info("textdomain")); ?></option>
				</select>
				<input type="submit" class="button" name="bulk_edit" value="<?php _e("Apply", get_flexible_blogtitle_info("textdomain")); ?>" />
			</div>
			<p>
				<br />
				<small><?php _e('Do you speak more than one language?<br /><a href="https://www.transifex.com/projects/p/plugin-flexible-blogtitle/" title="Translate Flexible Blogtitle on Transifex">Help translating Flexible Blogtitle</a> and make it easier for other users to use.', "flexible_blogtitle"); ?></small>
			</p>
		</form>
		</div>
	<?php
	}

	/**
	 * Register and load the plugin's stylesheets and scripts.
	 */
	function flexible_blogtitle_load_admin_media() {
		wp_enqueue_script(get_flexible_blogtitle_info("slug") . "-scripts", plugins_url("scripts.js", __FILE__), array(), "1.0.0", true);
	}

	add_action("admin_enqueue_scripts", "flexible_blogtitle_load_admin_media");