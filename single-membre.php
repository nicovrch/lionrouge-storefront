<?php
// Template de la fiche d'un membre de l'équipe (IT-11).
get_header();
?>
<div id="primary" class="content-area lr-equipe">
	<main id="main" class="site-main">
		<?php while ( have_posts() ) : the_post(); ?>
			<nav class="woocommerce-breadcrumb" aria-label="Fil d'Ariane">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> &gt;
				<a href="<?php echo esc_url( get_post_type_archive_link( 'membre' ) ); ?>">L'équipe</a> &gt;
				<?php the_title(); ?>
			</nav>
			<article class="lr-membre lr-membre--fiche">
				<span class="lr-membre__portrait" aria-hidden="true"><?php echo esc_html( get_field( 'initiales' ) ); ?></span>
				<div>
					<h1 class="lr-membre__nom"><?php the_title(); ?></h1>
					<p class="lr-membre__poste"><?php echo esc_html( get_field( 'poste' ) ); ?></p>
					<div class="lr-membre__texte"><?php the_content(); ?></div>
					<a class="button" href="<?php echo esc_url( get_post_type_archive_link( 'membre' ) ); ?>">Toute l'équipe</a>
				</div>
			</article>
		<?php endwhile; ?>
	</main>
</div>
<?php
get_footer();
