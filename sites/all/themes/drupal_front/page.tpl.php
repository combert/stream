<?php
// $Id: page.tpl.php,v 1.14.2.6 2009/02/13 16:28:33 johnalbin Exp $

/**
 * @file page.tpl.php
 *
 * Theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the theme is located in, e.g. themes/garland or
 *   themes/garland/minelli.
 * - $is_front: TRUE if the current page is the front page. Used to toggle the mission statement.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE tag.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $body_classes: A set of CSS classes for the BODY tag. This contains flags
 *   indicating the current layout (multiple columns, single column), the current
 *   path, whether the user is logged in, and so on.
 * - $body_classes_array: An array of the body classes. This is easier to
 *   manipulate then the string in $body_classes.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $mission: The text of the site mission, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been disabled.
 * - $primary_links (array): An array containing primary navigation links for the
 *   site, if they have been configured.
 * - $secondary_links (array): An array containing secondary navigation links for
 *   the site, if they have been configured.
 *
 * Page content (in order of occurrance in the default page.tpl.php):
 * - $left: The HTML for the left sidebar.
 *
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $title: The page title, for use in the actual HTML content.
 * - $help: Dynamic help text, mostly for admin pages.
 * - $messages: HTML for status and error messages. Should be displayed prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the view
 *   and edit tabs when displaying a node).
 *
 * - $content: The main content of the current Drupal page.
 *
 * - $right: The HTML for the right sidebar.
 *
 * Footer/closing data:
 * - $feed_icons: A string of all feed icons for the current page.
 * - $footer_message: The footer message as defined in the admin settings.
 * - $footer : The footer region.
 * - $closure: Final closing markup from any modules that have altered the page.
 *   This variable should always be output last, after all other dynamic content.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
	<script>
		function showHideNav()
		{
			if (document.getElementById('navlinks').style.display == 'none')
			{
				document.getElementById('navlinks').style.display = 'block';
				document.getElementById('updown').className = 'up';
				$(function() { 
	    		$.sessionStart(); 
	    		$.session("db_updown", "down");  
			    $.sessionStop(); 
    		}); 				
			}
			else
			{
				document.getElementById('navlinks').style.display = 'none';
				document.getElementById('updown').className = 'down';
				$(function() { 
	    		$.sessionStart(); 
	    		$.session("db_updown", "up");  
			    $.sessionStop(); 
    		}); 				
			}
		}
				$(function() { 
					$.sessionStart(); 
	    			if ($.session("db_updown") == 'up')
	    			{showHideNav();}  
			    $.sessionStop(); 
    		}); 				
	</script>
</head>

<body class="bd <?php print $body_classes; ?>">
<?php
	// if the user is a super admin, show bottom form
	global $user;
	if (!in_array('x_super admin', $user->roles))
	{
		echo '<style>.vertical-tabs-list, .vertical-tabs-panes {display: none;} div.vertical-tabs {border: none;}</style>';	
	}
?>

<div id="navtop">

<table width="100%" cellpadding="0" cellspacing="0" style="margin: 0px;"><tr>
	<td align="left" width="45%">
		<div class="topnavnlinks">
			<div class="navtitle sitename">&nbsp;</div>	
			<div class="fl"><a href="/"><?php print $site_name; ?></a></div>
		</div>		
	</td>
	<td align="center" width="10%">
		<div class="topnavnlinks">
			<div id="updown" class="up">&nbsp;</div>
		</div>		
	</td>
	<td align="right" width="45%">
		<div class="topnavnlinks">
			<div class="fr"><?php print $admin_links[1]; ?></div>
			<div class="navtitler logout">&nbsp;</div>	
			<div class="fr">&nbsp;|&nbsp;</div>
			<div class="fr"><?php print $admin_links[0]; ?></div>
			<div class="navtitler user">&nbsp;</div>		
		</div>		
	</td>
</tr></table>

	
	
	
</div>


       
<div id="navmiddle">
	<div id="header-blocks" class="navmiddle region region-header">
<div id="navtitles">


</div>
<div id="navlinks">
	<?php print $top; ?>
</div>
<div id="navmargin">&nbsp;</div>
      </div>
    </div>
<div id="navbottom"></div>
<div id="pagetop">

  <div id="page"><div id="page-inner">

    <a name="top" id="navigation-top"></a>

		
		<div id="header-inner" class="clear-block"></div>

    <div id="main"><div id="main-inner" class="clear-block">

      <div id="content"><div id="content-inner">






        
        	<table cellpadding="0" cellspacing="0" style="padding: 0; margin: 0; border: none; width: 100%; background: #fff;"><tr>
        		<td class="c1">&nbsp;</td>
        		<td>&nbsp;</td>
        		<td class="c2">&nbsp;</td>
        	</tr><tr>
        		<td>&nbsp;</td>
        		<td>
        			
           <?php if ($breadcrumb || $title || $tabs || $help || $messages): ?>
          <div id="content-header">
            <?php if ($title): ?>
            	<?php if ($node->type): ?>
              	<h1 class="title" style="float:right; color: #cc0000;">(<?php print $node->type; ?>)</h1>
              <?php endif; ?>
              <h1 class="title"><?php print $title; ?></h1>
            <?php endif; ?>
            
            <?php print $messages; ?>
            
            <?php if ($tabs): ?>
              <div class="tabs"><?php print $tabs; ?></div>
            <?php endif; ?>
            <?php print $help; ?>
          </div> <!-- /#content-header -->
        <?php endif; ?>     			
        			<div id="content-area">
        				<?php print $content; ?> </div>
        			</td>
        		<td>&nbsp;</td>
        	</tr><tr>
        		<td class="c3">&nbsp;</td>
        		<td>&nbsp;</td>
        		<td class="c4">&nbsp;</td>
        	</tr></table>
        	         
       

        <?php if ($feed_icons): ?>
          <div class="feed-icons"><?php print $feed_icons; ?></div>
        <?php endif; ?>

        <?php if ($content_bottom): ?>
          <div id="content-bottom" class="region region-content_bottom">
            <?php print $content_bottom; ?>
          </div> <!-- /#content-bottom -->
        <?php endif; ?>

      </div></div> <!-- /#content-inner, /#content -->



      <?php if ($left): ?>
        <div id="sidebar-left"><div id="sidebar-left-inner" class="region region-left">
          <?php print $left; ?>
        </div></div> <!-- /#sidebar-left-inner, /#sidebar-left -->
      <?php endif; ?>

      <?php if ($right): ?>
        <div id="sidebar-right"><div id="sidebar-right-inner" class="region region-right">
          <?php print $right; ?>
        </div></div> <!-- /#sidebar-right-inner, /#sidebar-right -->
      <?php endif; ?>

    </div></div> <!-- /#main-inner, /#main -->

    <?php if ($footer || $footer_message): ?>
      <div id="footer"><div id="footer-inner" class="region region-footer">

        <?php if ($footer_message): ?>
          <div id="footer-message"><?php print $footer_message; ?></div>
        <?php endif; ?>

        <?php print $footer; ?>

      </div></div> <!-- /#footer-inner, /#footer -->
    <?php endif; ?>

  </div></div> <!-- /#page-inner, /#page -->

  <?php if ($closure_region): ?>
    <div id="closure-blocks" class="region region-closure"><?php print $closure_region; ?></div>
  <?php endif; ?>

  <?php print $closure; ?>

</body>
</html>
