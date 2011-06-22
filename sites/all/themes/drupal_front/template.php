<?php
// $Id: template.php,v 1.17.2.1 2009/02/13 06:47:44 johnalbin Exp $

/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can add new regions for block content, modify
 *   or override Drupal's theme functions, intercept or make additional
 *   variables available to your theme, and create custom PHP logic. For more
 *   information, please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to drupal_front_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: drupal_front_breadcrumb()
 *
 *   where drupal_front is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override any of the theme functions used in Zen core,
 *   you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_item_link()   in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */


/*
 * Add any conditional stylesheets you will need for this sub-theme.
 *
 * To add stylesheets that ALWAYS need to be included, you should add them to
 * your .info file instead. Only use this section if you are including
 * stylesheets based on certain conditions.
 */
/* -- Delete this line if you want to use and modify this code
// Example: optionally add a fixed width CSS file.
if (theme_get_setting('drupal_front_fixed')) {
  drupal_add_css(path_to_theme() . '/layout-fixed.css', 'theme', 'all');
}
// */

function drupal_front_admin_links() {
  global $user;
  if ($user->uid) {
    $links[0] = '<a href="'. url('user') .'" class="user-name">'. $user->name .'</a>';
    $links[1] = '<a href="'. url('logout') .'">'. t('Logout') .'</a>';
    //$links = implode(' | ', $links);
    return $links;
  }
}


/**
 * Implementation of HOOK_theme().
 */
function drupal_front_theme(&$existing, $type, $theme, $path) {
  $hooks = zen_theme($existing, $type, $theme, $path);
  // Add your theme hooks like this:
  /*
  $hooks['hook_name_here'] = array( // Details go here );
  */
  // @TODO: Needs detailed comments. Patches welcome!
  return $hooks;
}

/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
/* -- Delete this line if you want to use this function
function drupal_front_preprocess(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */

function drupal_front_preprocess_page(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
  
  $vars['admin_links'] = drupal_front_admin_links();
  
 
  if (arg(0) == 'admin' || ((arg(0) == 'node' AND is_numeric(arg(1)) AND arg(2) == 'edit') || (arg(0) == 'node' AND arg(1) == 'add'))) {
    $vars['go_home'] = '<a style="color: #fff; text-decoration: none;" href="'. url() .'">'. t('Go Back to Homepage') .'</a>';
  }
/*
$vars['create'] = _getBlock('navigation','navigation:11','Create');
$vars['manage'] = _getBlock('navigation','navigation:10','Manage');
$vars['build'] = _getBlock('navigation','navigation:17','Build');
$vars['configure'] = _getBlock('navigation','navigation:18','Configure');
$vars['user'] = _getBlock('navigation','navigation:20','Users');
$vars['reports'] = _getBlock('navigation','navigation:16','Reports');
*/
}


function _getBlock($mname,$mlevel,$mtitle) {
  $data = array();

  // Get the block configuration options.
  list($menu_name, $parent_mlid) = split(':', variable_get($mname, $mlevel));
  //list($menu_name, $parent_mlid) = split(':', $mname, $mlevel);
  $level = variable_get('1', 1);
  $follow = variable_get('0', 0);
  $depth = variable_get('1', 1);
  $expanded = variable_get('0', 0);
  $sort = variable_get('0', 0);

  // Block name
  $data['subject'] = $mtitle;

  // Optionally load the specified parent menu item.
  $parent_item = $parent_mlid ? menu_link_load($parent_mlid) : NULL;

  if ($expanded || $parent_mlid) {
    // Get the full, un-pruned tree.
    $tree = menu_tree_all_data($menu_name);
    // And add the active trail data back to the full tree.
    db_menu_tree_add_active_path($tree);
  }
  else {
    // Get the tree pruned for just the active trail.
    $tree = menu_tree_page_data($menu_name);
  }

  // Prune the tree along the active trail to the specified level.
  if ($level > 1 || $parent_mlid) {
    $data['subject'] = db_menu_tree_prune_tree($tree, $level, $parent_item);
  }

  // Prune the tree to the active menu item.
  if ($follow && $new_title = menu_tree_prune_active_tree($tree, $follow)) {
    $data['subject'] = $new_title;
  }

  // If the menu-item-based tree is not "expanded", trim the tree to the active path.
  if ($parent_mlid && !$expanded) {
    db_menu_tree_trim_active_path($tree);
  }

  // Trim the branches that extend beyond the specified depth.
  if ($depth > 0) {
    db_menu_tree_depth_trim($tree, $depth);
  }

  // Sort the active path to the top of the tree.
  if ($sort) {
    menu_tree_sort_active_path($tree);
  }

  // Localize the tree.
  if (module_exists('i18nmenu')) {
    i18nmenu_localize_tree($tree);
  }

  // Render the tree.
  $data['content'] = db_menu_block_tree_output($tree);

  if ($data['content']) {
    $settings = array(
      'menu_name' => $menu_name,
      'parent_mlid' => $parent_mlid,
      'level' => $level,
      'follow' => $follow,
      'depth' => $depth,
      'expanded' => $expanded,
      'sort' => $sort,
    );
    //$data['content'] = theme('menu_block_wrapper', $data['content'], $settings);
    //dsm($data[content]);
    //$data['content'] = '<div class="navblock navblock-'.$parent_mlid.'"><div class="ntw"><div class="navtitle navtitle-'.$parent_mlid.'">&nbsp;</div>'.$mtitle.'</div>'.$data['content'].'</div>';

  }

  return $data;
}

function db_menu_tree_add_active_path(&$tree) {
  // Grab any menu item to find the menu_name for this tree.
  $menu_item = current($tree);
  $tree_with_trail = menu_tree_page_data($menu_item['link']['menu_name']);

  // To traverse the original tree down the active trail, we use a pointer.
  $subtree_pointer =& $tree;

  // Find each key in the active trail.
  while ($tree_with_trail) {
    foreach (array_keys($tree_with_trail) AS $key) {
      if ($tree_with_trail[$key]['link']['in_active_trail']) {
        // Set the active trail info in the original tree.
        $subtree_pointer[$key]['link']['in_active_trail'] = TRUE;
        // Continue in the subtree, if it exists.
        $tree_with_trail =& $tree_with_trail[$key]['below'];
        $subtree_pointer =& $subtree_pointer[$key]['below'];
        break;
      }
      else {
        unset($tree_with_trail[$key]);
      }
    }
  }
}

function menu_block_get_all_menus() {
  static $all_menus;

  if (!$all_menus) {
    // Include book support.
    if (module_exists('book')) {
      module_load_include('inc', 'menu_block', 'menu_block.book');
    }
    // We're generalizing menu's menu_get_menus() by making it into a hook.
    // Retrieve all the menu names provided by hook_get_menus().
    $all_menus = module_invoke_all('get_menus');
    asort($all_menus);
  }
  return $all_menus;
}


function db_menu_tree_prune_tree(&$tree, $level, $parent_item = FALSE) {
  // Get the default menu title.
  if (!empty($parent_item)) {
    // Use the title of the menu item.
    $menu_title = $parent_item['title'];
  }
  elseif ($level == 1) {
    $menu_names = menu_block_get_all_menus();
    // Grab any menu item to find the menu_name for this tree.
    $menu_item = current($tree);
    $menu_title = $menu_names[$menu_item['link']['menu_name']];
  }
  else {
    // We won't know the title until we've pruned the tree.
    $menu_title = '';
  }

  if (!empty($parent_item)) {
    // Prune the tree along the path to the menu item.
    for ($i = 1; $i <= MENU_MAX_DEPTH && $parent_item["p$i"] != '0'; $i++) {
      $plid = $parent_item["p$i"];
      $found_active_trail = FALSE;
      // Examine each element at this level for the ancestor.
      foreach (array_keys($tree) AS $key) {
        if ($tree[$key]['link']['mlid'] == $plid) {
          // Prune the tree to the children of this ancestor.
          $tree = $tree[$key]['below'] ? $tree[$key]['below'] : array();
          $found_active_trail = TRUE;
          break;
        }
      }
      // If we don't find the ancestor, bail out.
      if (!$found_active_trail) {
        $tree = array();
        break;
      }
    }
  }

  // Trim the upper levels down to the one desired.
  for ($i = 1; $i < $level; $i++) {
    $found_active_trail = FALSE;
    // Examine each element at this level for the active trail.
    foreach (array_keys($tree) AS $key) {
      if ($tree[$key]['link']['in_active_trail']) {
        // Get the title for the pruned tree.
        $menu_title = $tree[$key]['link']['title'];
        // Prune the tree to the children of the item in the active trail.
        $tree = $tree[$key]['below'] ? $tree[$key]['below'] : array();
        $found_active_trail = TRUE;
        break;
      }
    }
    // If we don't find the active trail, the active item isn't in the tree we want.
    if (!$found_active_trail) {
      $tree = array();
      break;
    }
  }

  return $menu_title;
}

function db_menu_tree_trim_active_path(&$tree) {
  // To traverse the original tree down the active trail, we use a pointer.
  $current_level =& $tree;

  // Traverse the tree along the active trail.
  do {
    $next_level = FALSE;
    foreach (array_keys($current_level) AS $key) {
      if ($current_level[$key]['link']['in_active_trail'] && $current_level[$key]['below']) {
        // Continue in the subtree, if it exists.
        $next_level = $key;
      }
      else {
        // Trim anything not along the active trail.
        $current_level[$key]['below'] = FALSE;
      }
    }
    if ($next_level) {
      $current_level =& $current_level[$next_level]['below'];
    }
  } while ($next_level);
}

function db_menu_tree_depth_trim(&$tree, $depth_limit) {
  // Prevent invalid input from returning a trimmed tree.
  if ($depth_limit < 1) { return; }

  // Examine each element at this level to find any possible children.
  foreach (array_keys($tree) AS $key) {
    if ($tree[$key]['below']) {
      if ($depth_limit > 1) {
        db_menu_tree_depth_trim($tree[$key]['below'], $depth_limit-1);
      }
      else {
        // Remove the children items.
        $tree[$key]['below'] = FALSE;
      }
    }
    if ($depth_limit == 1 && $tree[$key]['link']['has_children']) {
      // Turn off the menu styling that shows there were children.
      $tree[$key]['link']['has_children'] = FALSE;
      $tree[$key]['link']['leaf_has_children'] = TRUE;
    }
  }
}

function db_menu_block_tree_output(&$tree) {
  $output = '';
  $items = array();

  // Pull out just the menu items we are going to render so that we
  // get an accurate count for the first/last classes.
  foreach (array_keys($tree) as $key) {
    if (!$tree[$key]['link']['hidden']) {
      $items[$key] = array(
        'link' => $tree[$key]['link'],
        // To prevent copying the entire child array, we render it first.
        'below' => !empty($tree[$key]['below']) ? db_menu_block_tree_output($tree[$key]['below']) : '',
      );
    }
  }

  $num_items = count($items);
  $i = 1;
  foreach (array_keys($items) as $key) {
    // Render the link.
    $link_class = array();
    if (!empty($items[$key]['link']['localized_options']['attributes']['class'])) {
      $link_class[] = $items[$key]['link']['localized_options']['attributes']['class'];
    }
    if ($items[$key]['link']['in_active_trail']) {
      $link_class[] = 'active-trail';
    }
    if (!empty($link_class)) {
      $items[$key]['link']['localized_options']['attributes']['class'] = implode(' ', $link_class);
    }
    $link = theme('menu_item_link', $items[$key]['link']);
    // Render the menu item.
    $extra_class = array();
    if ($i == 1) {
      $extra_class[] = 'first';
    }
    if ($i == $num_items) {
      $extra_class[] = 'last';
    }
    $extra_class[] = 'menu-mlid-' . $items[$key]['link']['mlid'];
    if (!empty($items[$key]['link']['leaf_has_children'])) {
      $extra_class[] = 'has-children';
    }
    if ($items[$key]['link']['href'] == $_GET['q'] || ($items[$key]['link']['href'] == '<front>' && drupal_is_front_page())) {
      $extra_class[] = 'active';
    }
    $extra_class = !empty($extra_class) ? implode(' ', $extra_class) : NULL;
    $output .= theme('menu_item', $link, $items[$key]['link']['has_children'], $items[$key]['below'], $items[$key]['link']['in_active_trail'], $extra_class);
    $i++;
  }

  return $output ? theme('menu_tree', $output) : '';
}














/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function drupal_front_preprocess_node(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function drupal_front_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function drupal_front_preprocess_block(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */
