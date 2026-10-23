<?php

/* ===== Général ===== */

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

// Avis clients sous la fiche produit (IT-14).
function lionrouge_avis() {
	comments_template();
}
add_action( 'woocommerce_after_single_product_summary', 'lionrouge_avis', 25 );

// Titre des suggestions sur la fiche produit et dans le panier (IT-13).
function lionrouge_titre_suggestions() {
	return 'Avec ça ?';
}
add_filter( 'woocommerce_product_upsells_products_heading', 'lionrouge_titre_suggestions' );
add_filter( 'woocommerce_product_cross_sells_products_heading', 'lionrouge_titre_suggestions' );

/* ===== Panier, commande et e-mails ===== */

// Frais de préparation si le panier est sous le seuil, montant et seuil dans « Réglages Lion Rouge » (IT-15).
function lionrouge_frais_preparation( $panier ) {
	$montant = (float) get_field( 'frais_preparation_montant', 'option' );
	$seuil   = (float) get_field( 'frais_preparation_seuil', 'option' );
	if ( $montant > 0 && $panier->get_subtotal() < $seuil ) {
		$panier->add_fee( 'Frais de préparation', $montant );
	}
}
add_action( 'woocommerce_cart_calculate_fees', 'lionrouge_frais_preparation' );

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

/* ===== Recherche et filtrage ===== */

// Filtres prix et taille de la colonne latérale, affichés au-dessus de la grille de produits (IT-18).
function lionrouge_filtres() {
	if ( is_active_sidebar( 'sidebar-1' ) ) {
		echo '<aside class="lr-filtres" aria-label="Filtrer les plats">';
		echo '<h2 class="lr-filtres__titre">Filtrer</h2>';
		dynamic_sidebar( 'sidebar-1' );
		echo '</aside>';
	}
}
add_action( 'woocommerce_before_shop_loop', 'lionrouge_filtres', 6 );
add_action( 'woocommerce_no_products_found', 'lionrouge_filtres', 5 );

// La colonne latérale de Storefront n'est plus affichée : ses widgets sont repris par lionrouge_filtres() (IT-18).
function lionrouge_retirer_colonne_laterale() {
	remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
}
add_action( 'wp', 'lionrouge_retirer_colonne_laterale' );

/* ===== Livraison selon le poids ===== */

// Tarif de la livraison à domicile selon le poids du panier : 3 tranches réglées dans « Réglages Lion Rouge » (IT-19).
function lionrouge_livraison_poids( $tarifs ) {
	$poids = WC()->cart->get_cart_contents_weight();
	$prix  = get_field( 'livraison_tarif_3', 'option' );
	if ( $poids <= get_field( 'livraison_seuil_2', 'option' ) ) {
		$prix = get_field( 'livraison_tarif_2', 'option' );
	}
	if ( $poids <= get_field( 'livraison_seuil_1', 'option' ) ) {
		$prix = get_field( 'livraison_tarif_1', 'option' );
	}
	foreach ( $tarifs as $tarif ) {
		if ( $tarif->get_method_id() === 'flat_rate' ) {
			$tarif->set_cost( $prix );
		}
	}
	return $tarifs;
}
add_filter( 'woocommerce_package_rates', 'lionrouge_livraison_poids' );

// Poids du panier et rappel des 3 tranches, sous le mode de livraison du panier et de la commande (IT-19).
function lionrouge_poids_panier() {
	$tranches = 'Livraison : jusqu’à ' . get_field( 'livraison_seuil_1', 'option' ) . ' kg ' . wc_price( get_field( 'livraison_tarif_1', 'option' ) )
		. ', jusqu’à ' . get_field( 'livraison_seuil_2', 'option' ) . ' kg ' . wc_price( get_field( 'livraison_tarif_2', 'option' ) )
		. ', au-delà ' . wc_price( get_field( 'livraison_tarif_3', 'option' ) ) . '.';
	?>
	<tr class="lr-poids">
		<th>Poids</th>
		<td>
			<?php echo esc_html( wc_format_weight( WC()->cart->get_cart_contents_weight() ) ); ?>
			<p class="lr-poids__tranches"><?php echo wp_kses_post( $tranches ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'woocommerce_cart_totals_after_shipping', 'lionrouge_poids_panier' );
add_action( 'woocommerce_review_order_after_shipping', 'lionrouge_poids_panier' );

/* ===== Créneaux de livraison et de retrait ===== */

// Horaires proposés : heures de retrait au comptoir, plages horaires pour la livraison (IT-20).
function lionrouge_horaires( $retrait ) {
	if ( $retrait ) {
		return array( '12:00', '12:30', '13:00', '13:30', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00' );
	}
	return array( '12:00-13:00', '13:00-14:00', '19:30-20:30', '20:30-21:30', '21:30-22:30' );
}

// Texte d'un horaire : « 19 h 30 » ou « 19 h 30 à 20 h 30 » (IT-20).
function lionrouge_texte_horaire( $horaire ) {
	return str_replace( array( ':', '-' ), array( ' h ', ' à ' ), $horaire );
}

// Choix du jour et de l'horaire dans le récapitulatif de la commande, selon le mode choisi : retrait ou livraison (IT-20).
function lionrouge_choix_creneau() {
	$modes   = WC()->session->get( 'chosen_shipping_methods' );
	$retrait = ! empty( $modes ) && strpos( $modes[0], 'local_pickup' ) === 0;

	// Quand WooCommerce recharge le récapitulatif, il renvoie le formulaire : on garde le choix déjà fait.
	$saisie = array();
	if ( isset( $_POST['post_data'] ) ) {
		parse_str( wp_unslash( $_POST['post_data'] ), $saisie );
	}
	$jour_choisi    = '';
	$horaire_choisi = '';
	if ( isset( $saisie['lr_jour'] ) ) {
		$jour_choisi = $saisie['lr_jour'];
	}
	if ( isset( $saisie['lr_horaire'] ) ) {
		$horaire_choisi = $saisie['lr_horaire'];
	}

	$titre   = 'Créneau de livraison';
	$legende = 'Plage horaire de livraison';
	if ( $retrait ) {
		$titre   = 'Créneau de retrait';
		$legende = 'Heure de retrait';
	}
	?>
	<tr class="lr-creneaux">
		<td colspan="2">
		<h3 class="lr-creneaux__titre"><?php echo esc_html( $titre ); ?></h3>
		<fieldset class="lr-creneaux__groupe">
			<legend class="lr-creneaux__legende">Jour</legend>
			<?php
			// Les 5 prochains jours d'ouverture : le restaurant est fermé le dimanche (7) et le lundi (1).
			$nombre = 0;
			$i      = 1;
			while ( $nombre < 5 ) {
				$jour = strtotime( '+' . $i . ' day', current_time( 'timestamp' ) );
				$i++;
				if ( date( 'N', $jour ) == 7 || date( 'N', $jour ) == 1 ) {
					continue;
				}
				$nombre++;
				$valeur = date( 'Y-m-d', $jour );
				?>
				<label class="lr-creneau">
					<input class="lr-creneau__input" type="radio" name="lr_jour" value="<?php echo esc_attr( $valeur ); ?>"<?php checked( $jour_choisi, $valeur ); ?>>
					<span class="lr-creneau__texte"><?php echo esc_html( date_i18n( 'D j F', $jour ) ); ?></span>
				</label>
				<?php
			}
			?>
		</fieldset>
		<fieldset class="lr-creneaux__groupe">
			<legend class="lr-creneaux__legende"><?php echo esc_html( $legende ); ?></legend>
			<?php foreach ( lionrouge_horaires( $retrait ) as $horaire ) : ?>
				<label class="lr-creneau">
					<input class="lr-creneau__input" type="radio" name="lr_horaire" value="<?php echo esc_attr( $horaire ); ?>"<?php checked( $horaire_choisi, $horaire ); ?>>
					<span class="lr-creneau__texte"><?php echo esc_html( lionrouge_texte_horaire( $horaire ) ); ?></span>
				</label>
			<?php endforeach; ?>
		</fieldset>
		</td>
	</tr>
	<?php
}
add_action( 'woocommerce_review_order_after_shipping', 'lionrouge_choix_creneau', 20 );

// La commande est refusée si le jour ou l'horaire manque, ou si l'horaire ne correspond pas au mode choisi (IT-20).
function lionrouge_verifier_creneau() {
	$retrait = isset( $_POST['shipping_method'][0] ) && strpos( $_POST['shipping_method'][0], 'local_pickup' ) === 0;
	if ( empty( $_POST['lr_jour'] ) ) {
		wc_add_notice( 'Choisissez un jour pour votre commande.', 'error' );
	}
	if ( empty( $_POST['lr_horaire'] ) || ! in_array( $_POST['lr_horaire'], lionrouge_horaires( $retrait ), true ) ) {
		wc_add_notice( 'Choisissez un horaire pour votre commande.', 'error' );
	}
}
add_action( 'woocommerce_checkout_process', 'lionrouge_verifier_creneau' );

// Le créneau est enregistré dans la commande, en toutes lettres (IT-20).
function lionrouge_enregistrer_creneau( $commande ) {
	$jour    = sanitize_text_field( wp_unslash( $_POST['lr_jour'] ) );
	$horaire = sanitize_text_field( wp_unslash( $_POST['lr_horaire'] ) );
	$texte   = date_i18n( 'l j F', strtotime( $jour ) );
	if ( $commande->has_shipping_method( 'local_pickup' ) ) {
		$texte = 'Retrait le ' . $texte . ' à ' . lionrouge_texte_horaire( $horaire );
	} else {
		$texte = 'Livraison le ' . $texte . ' de ' . lionrouge_texte_horaire( $horaire );
	}
	$commande->update_meta_data( 'lr_creneau', $texte );
}
add_action( 'woocommerce_checkout_create_order', 'lionrouge_enregistrer_creneau' );

// Créneau affiché sur la confirmation, le détail de la commande dans Mon compte et les e-mails (IT-20).
function lionrouge_afficher_creneau( $commande ) {
	$creneau = $commande->get_meta( 'lr_creneau' );
	if ( $creneau ) {
		echo '<p class="lr-creneau-choisi"><strong>Créneau :</strong> ' . esc_html( $creneau ) . '</p>';
	}
}
add_action( 'woocommerce_order_details_after_order_table', 'lionrouge_afficher_creneau' );
add_action( 'woocommerce_email_after_order_table', 'lionrouge_afficher_creneau', 5 );
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'lionrouge_afficher_creneau' );

/* ===== Performances (Lighthouse) ===== */

// Retire la police Google de Storefront, le thème a ses propres polices (Lighthouse).
function lionrouge_retirer_police_google() {
	wp_dequeue_style( 'storefront-fonts' );
}
add_action( 'wp_enqueue_scripts', 'lionrouge_retirer_police_google', 20 );
