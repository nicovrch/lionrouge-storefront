<?php

/* ===== Général ===== */

// Chargement du style du thème parent Storefront (IT-1).
function lionrouge_enqueue_styles() {
	wp_enqueue_style(
		'storefront-parent-style',
		get_parent_theme_file_uri( 'style.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'lionrouge_enqueue_styles' );

// Retire la police Google de Storefront : le thème utilise les polices du Design System (IT-2).
function lionrouge_retirer_polices_storefront() {
	wp_dequeue_style( 'storefront-fonts' );
}
add_action( 'wp_enqueue_scripts', 'lionrouge_retirer_polices_storefront', 20 );

// Retire des éléments de Storefront et WooCommerce absents de la maquette (IT-2).
// Lancé sur le hook « wp » : le functions.php du thème enfant est lu avant celui de Storefront.
function lionrouge_retirer_hooks() {
	remove_action( 'storefront_header', 'storefront_site_branding', 20 );
	remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
	remove_action( 'storefront_header', 'storefront_product_search', 40 );
	remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
	remove_action( 'storefront_footer', 'storefront_credit', 20 );
	remove_action( 'storefront_footer', 'storefront_handheld_footer_bar', 999 );
	remove_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999 );

	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );
	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	remove_action( 'woocommerce_after_single_product_summary', 'storefront_single_product_pagination', 30 );
}
add_action( 'wp', 'lionrouge_retirer_hooks' );

/* ===== En-tête et pied de page ===== */

// Garde « La carte » actif dans le menu sur les pages de catégorie et de produit (IT-2).
function lionrouge_menu_la_carte( $classes, $item ) {
	if ( ( is_product_category() || is_product() ) && $item->object_id == wc_get_page_id( 'shop' ) ) {
		$classes[] = 'current-menu-item';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'lionrouge_menu_la_carte', 10, 2 );

// En-tête : logo du restaurant à la place du titre du site (IT-2).
function lionrouge_logo() {
	?>
	<div class="site-branding">
		<a class="lr-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php echo esc_url( get_theme_file_uri( 'img/logo-header-lion-rouge.svg' ) ); ?>" alt="Le Lion Rouge, accueil" width="40" height="40">
		</a>
	</div>
	<?php
}
add_action( 'storefront_header', 'lionrouge_logo', 20 );

// En-tête : icône « Mon compte » (IT-2).
function lionrouge_lien_compte() {
	?>
	<a class="lr-compte" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
		<svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
			<circle cx="12" cy="8" r="4" />
			<path d="M4.5 20.5c1.2-3.8 4-5.8 7.5-5.8s6.3 2 7.5 5.8" />
		</svg>
		<span class="screen-reader-text">Mon compte</span>
	</a>
	<?php
}
add_action( 'storefront_header', 'lionrouge_lien_compte', 55 );

// Pied de page de la maquette (IT-2).
function lionrouge_pied_de_page() {
	?>
	<div class="lr-footer">
		<div class="lr-footer__inner">
			<div>
				<img class="lr-footer__logo" src="<?php echo esc_url( get_theme_file_uri( 'img/logo-footer-lion-rouge.svg' ) ); ?>" alt="Le Lion Rouge, Angoulême" width="140" height="140">
				<p>Restaurant italien à Angoulême. Commandez en ligne, retirez au comptoir.</p>
			</div>
			<div>
				<h2 class="lr-footer__title">Adresse</h2>
				<address>3 place Francis Louvel<br>16000 Angoulême<br><a href="tel:+33545200835">05 45 20 08 35</a></address>
			</div>
			<div>
				<h2 class="lr-footer__title">Horaires</h2>
				<dl class="lr-hours">
					<dt>Mardi au jeudi</dt>
					<dd>12 h à 14 h et 19 h 30 à 22 h 30</dd>
					<dt>Vendredi et samedi</dt>
					<dd>12 h à 14 h et 19 h 30 à 23 h</dd>
					<dt>Dimanche et lundi</dt>
					<dd>Fermé</dd>
				</dl>
			</div>
			<nav aria-label="Informations">
				<h2 class="lr-footer__title">Informations</h2>
				<ul class="lr-footer__links">
					<li><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>">Mentions légales</a></li>
					<li><a href="<?php echo esc_url( home_url( '/conditions-generales-de-vente/' ) ); ?>">Conditions générales de vente</a></li>
					<li><a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">Confidentialité</a></li>
					<li><a href="https://www.instagram.com/lionrougerestaurant/">Instagram</a></li>
				</ul>
			</nav>
		</div>
		<div class="lr-footer__bottom">
			<p>© 2026 Le Lion Rouge</p>
			<p>Paiement au retrait · Retrait gratuit</p>
		</div>
	</div>
	<?php
}
add_action( 'storefront_footer', 'lionrouge_pied_de_page', 10 );

/* ===== La carte (grille de produits) ===== */

// Onglets des catégories au-dessus de la grille de produits (IT-2).
function lionrouge_onglets_categories() {
	$categories = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'term_id',
	) );
	?>
	<nav class="lr-tabs" aria-label="Catégories de la carte">
		<ul class="lr-tabs__list">
			<?php
			foreach ( $categories as $categorie ) {
				$actif = '';
				if ( is_product_category( $categorie->slug ) ) {
					$actif = ' aria-current="page"';
				}
				echo '<li><a class="lr-tabs__link" href="' . esc_url( get_term_link( $categorie ) ) . '"' . $actif . '>' . esc_html( $categorie->name ) . '</a></li>';
			}
			?>
		</ul>
	</nav>
	<?php
}
add_action( 'woocommerce_before_shop_loop', 'lionrouge_onglets_categories', 5 );

// Grille de produits : description courte sous le nom (IT-2).
function lionrouge_description_carte() {
	echo '<div class="lr-description">';
	the_excerpt();
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'lionrouge_description_carte', 7 );

// Toute la carte sur une seule page (IT-2).
function lionrouge_produits_par_page() {
	return 24;
}
add_filter( 'loop_shop_per_page', 'lionrouge_produits_par_page', 20 );

// Produits variables : bouton « Personnaliser » dans la grille (IT-3).
function lionrouge_texte_bouton( $texte, $produit ) {
	if ( $produit->is_type( 'variable' ) ) {
		return 'Personnaliser';
	}
	return $texte;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'lionrouge_texte_bouton', 10, 2 );

// Produits variables : « À partir de » + prix le plus bas (IT-3).
function lionrouge_prix_a_partir_de( $prix, $produit ) {
	return '<span class="lr-price-from">À partir de</span> ' . wc_price( $produit->get_variation_price( 'min' ) );
}
add_filter( 'woocommerce_variable_price_html', 'lionrouge_prix_a_partir_de', 10, 2 );

// Champs SCF « Allergènes » et « Provenance », sur la carte et la fiche produit (IT-4).
function lionrouge_infos_plat() {
	$allergenes = get_field( 'allergenes' );
	$provenance = get_field( 'provenance' );
	if ( $allergenes ) {
		echo '<p class="lr-infos"><strong>Allergènes :</strong> ' . esc_html( $allergenes ) . '</p>';
	}
	if ( $provenance && is_product() ) {
		echo '<p class="lr-infos"><strong>Provenance :</strong> ' . esc_html( $provenance ) . '</p>';
	}
}
add_action( 'woocommerce_single_product_summary', 'lionrouge_infos_plat', 22 );
add_action( 'woocommerce_after_shop_loop_item_title', 'lionrouge_infos_plat', 8 );

/* ===== Fiche produit ===== */

// Fiche produit : prix déplacé après la description (IT-2), donc après les allergènes (IT-4).
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );

/* ===== Panier, commande et e-mails ===== */

// Instructions pour le retrait pendant la commande, texte modifiable dans « Réglages Lion Rouge » (IT-6).
function lionrouge_instructions_retrait() {
	$instructions = get_field( 'instructions_retrait', 'option' );
	if ( $instructions ) {
		?>
		<div class="lr-retrait">
			<h3 class="lr-retrait__titre">Instructions pour le retrait</h3>
			<p><?php echo wp_kses_post( $instructions ); ?></p>
		</div>
		<?php
	}
}
add_action( 'woocommerce_review_order_before_payment', 'lionrouge_instructions_retrait' );

// Instructions pour le retrait dans les e-mails des commandes à retirer en boutique (IT-8).
function lionrouge_instructions_email( $commande, $pour_admin, $texte_brut ) {
	$instructions = get_field( 'instructions_retrait', 'option' );
	if ( ! $instructions || ! $commande->has_shipping_method( 'local_pickup' ) ) {
		return;
	}
	if ( $texte_brut ) {
		echo "\nINSTRUCTIONS POUR LE RETRAIT\n" . wp_strip_all_tags( $instructions ) . "\n\n";
	} else {
		echo '<h2>Instructions pour le retrait</h2><p>' . wp_kses_post( $instructions ) . '</p>';
	}
}
add_action( 'woocommerce_email_after_order_table', 'lionrouge_instructions_email', 10, 3 );
