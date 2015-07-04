<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package phylo
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '-', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<link rel="stylesheet"  type="text/css" media="print" href="<?php echo get_template_directory_uri(); ?>/css/print.css?v5" />
<link rel="stylesheet" media="screen" href="<?php echo get_template_directory_uri(); ?>/css/card.css?v4" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<?php
			if( is_user_logged_in() ) :
				global $current_user;

				?>
				<div class="navigation-main loggedin-menu">
				<em>Hi <?php echo $current_user->display_name; ?></em>
				<?php
				wp_nav_menu( array( 'theme_location' => 'loggedin' ) );
				?>
				</div>
				<?php
			endif;
			?>
		<div class="site-branding clear">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>

		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) { ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-image clear" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
			</a>
		<?php } // if ( ! empty( $header_image ) ) ?>
		</div>
		<nav id="site-navigation" class="navigation-main" role="navigation">
			<h1 class="menu-toggle">&#9776;<?php _e( 'Menu', 'phylo' ); ?></h1>
			<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'phylo' ); ?>"><?php _e( 'Skip to content', 'phylo' ); ?></a></div>

			<?php
			wp_nav_menu( array( 'theme_location' => 'primary' ) );
			?>

		<div id="card-searchform-shell">
			<form id="card-searchform"  class="card-search" method="get" action="<?php bloginfo( 'url' ); ?>">
                <input id="card-autosearch" name="s" type="text" class="text" value="<?php if ( isset( $_GET['s'] ) ) { echo esc_attr( $_GET['s'] ); } ?>" size="10" tabindex="1" />
                <input name="post_type" value="card" type="hidden"  />
				<input type="submit" class="button" value="Search" tabindex="2" />
            </form>
		</div>

		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="main" class="site-main">
