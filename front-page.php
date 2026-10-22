<?php
// Template de la page d'accueil (IT-16).
get_header();
?>
<div id="primary" class="content-area lr-accueil">
	<main id="main" class="site-main">
		<section class="lr-accroche">
			<img class="lr-accroche__image" src="<?php echo esc_url( get_theme_file_uri( 'img/salle-lion-rouge.jpg' ) ); ?>" alt="La salle du restaurant Le Lion Rouge" width="568" height="426">
			<div class="lr-accroche__texte">
				<p class="lr-accroche__script">Buon appetito !</p>
				<h1 class="lr-accroche__titre">Le Lion Rouge</h1>
				<p class="lr-accroche__intro">Restaurant italien à Angoulême. Commandez en ligne, retirez au comptoir.</p>
				<div class="lr-accroche__boutons">
					<a class="lr-bouton lr-bouton--or" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Voir la carte</a>
					<a class="lr-bouton lr-bouton--contour" href="#infos-pratiques">Infos pratiques</a>
				</div>
			</div>
		</section>

		<section class="lr-ajouts">
			<h2 class="lr-ajouts__titre">Derniers ajouts</h2>
			<ul class="lr-ajouts__liste">
				<?php
				// Les 3 derniers produits ajoutés au catalogue : le plus grand ID est le plus récent.
				$derniers = new WP_Query( array(
					'post_type'      => 'product',
					'posts_per_page' => 3,
					'orderby'        => 'ID',
					'order'          => 'DESC',
				) );
				while ( $derniers->have_posts() ) :
					$derniers->the_post();
					$produit = wc_get_product( get_the_ID() );
					?>
					<li class="lr-ajout">
						<a class="lr-ajout__lien" href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'lr-ajout__image' ) ); ?>
							<span class="lr-ajout__nom"><?php the_title(); ?></span>
							<span class="lr-ajout__prix"><?php echo $produit->get_price_html(); ?></span>
						</a>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		</section>

		<section class="lr-pratique" id="infos-pratiques">
			<h2 class="lr-pratique__titre">Infos pratiques</h2>
			<ul class="lr-pratique__liste">
				<li class="lr-pratique__ligne">
					<svg class="lr-pratique__icone" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z" /><circle cx="12" cy="9.5" r="2.5" /></svg>
					<span>3 place Francis Louvel, 16000 Angoulême</span>
				</li>
				<li class="lr-pratique__ligne">
					<svg class="lr-pratique__icone" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" /></svg>
					<a class="lr-pratique__lien" href="tel:+33545200835">05 45 20 08 35</a>
				</li>
				<li class="lr-pratique__ligne">
					<svg class="lr-pratique__icone" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
					<span>Mardi au jeudi : 12 h à 14 h et 19 h 30 à 22 h 30<br>Vendredi et samedi : 12 h à 14 h et 19 h 30 à 23 h</span>
				</li>
			</ul>
		</section>
	</main>
</div>
<?php
get_footer();
