<?php
// Template de la page qui liste les membres de l'équipe (IT-11).
get_header();
?>
<div id="primary" class="content-area lr-equipe">
	<main id="main" class="site-main">
		<h1 class="lr-equipe__titre">L'équipe</h1>
		<p class="lr-equipe__intro">Les personnes qui préparent vos commandes au Lion Rouge.</p>
		<ul class="lr-equipe__liste">
			<?php while ( have_posts() ) : the_post(); ?>
				<li class="lr-membre">
					<a class="lr-membre__lien" href="<?php the_permalink(); ?>">
						<span class="lr-membre__portrait" aria-hidden="true"><?php echo esc_html( get_field( 'initiales' ) ); ?></span>
						<span class="lr-membre__nom"><?php the_title(); ?></span>
						<span class="lr-membre__poste"><?php echo esc_html( get_field( 'poste' ) ); ?></span>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	</main>
</div>
<?php
get_footer();
