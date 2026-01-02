<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="header-container">
		<div class="site-branding">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			}
			?>
			<h1 class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</h1>
			<?php
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) {
				?>
				<p class="site-description"><?php echo esc_html( $description ); ?></p>
				<?php
			}
			?>
		</div>

		<nav class="site-navigation" aria-label="Primary Navigation">
			<ul class="nav-menu">
				<li class="menu-item">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				</li>
				<?php
				// Get all published pages
				$pages = get_pages(
					array(
						'sort_column' => 'menu_order, post_title',
						'post_status' => 'publish',
					)
				);

				// Display each page in navigation
				foreach ( $pages as $page ) {
					?>
					<li class="menu-item">
						<a href="<?php echo esc_url( get_permalink( $page ) ); ?>">
							<?php echo esc_html( $page->post_title ); ?>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		</nav>
	</div>
</header>

<main class="site-main">
