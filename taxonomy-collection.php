<?php
// Template de la page d'une collection (IT-12).
get_header();
$collection = get_queried_object();
?>
<div id="primary" class="content-area lr-collection">
	<main id="main" class="site-main">
		<header class="lr-collection__entete">
			<h1 class="lr-collection__titre"><?php echo esc_html( $collection->name ); ?></h1>
			<?php if ( $collection->description ) : ?>
				<p class="lr-collection__description"><?php echo esc_html( $collection->description ); ?></p>
			<?php endif; ?>
		</header>
		<ul class="products columns-3">
			<?php
			// Les produits rangés dans cette collection, sans passer par les catégories.
			$produits = new WP_Query( array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'collection',
						'field'    => 'term_id',
						'terms'    => $collection->term_id,
					),
				),
			) );
			while ( $produits->have_posts() ) :
				$produits->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
	</main>
</div>
<?php
get_footer();
