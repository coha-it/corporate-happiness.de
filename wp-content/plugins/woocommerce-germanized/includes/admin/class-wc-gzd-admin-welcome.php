<?php
/**
 * Welcome Page Class
 *
 * Feature Overview
 *
 * Adapted from code in EDD (Copyright (c) 2012, Pippin Williamson) and WP.
 *
 * @author 		Vendidero
 * @version     1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add Welcome Screen and Feature Overview
 *
 * @class 		WC_GZD_Admin_Welcome
 * @version		1.0.0
 * @author 		Vendidero
 */
class WC_GZD_Admin_Welcome {

	private $plugin;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		$this->plugin  = 'woocommerce-germanized/woocommerce-germanized.php';

		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome' ) );

	}

	/**
	 * Add admin menus/screens
	 *
	 * @access public
	 * @return void
	 */
	public function admin_menus() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		$welcome_page_name  = __( 'About Germanized', 'woocommerce-germanized' );
		$welcome_page_title = __( 'Welcome to Germanized', 'woocommerce-germanized' );

		switch ( $_GET['page'] ) {
			case 'wc-gzd-about' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'wc-gzd-about', array( $this, 'about_screen' ) );
				add_action( 'admin_print_styles-'. $page, array( $this, 'admin_css' ) );
			break;
		}
	}

	/**
	 * admin_css function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_css() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'fontawesome', plugins_url(  '/assets/css/font-awesome.min.css', WC_GERMANIZED_PLUGIN_FILE ), array(), '4.2.0' );
		wp_enqueue_style( 'woocommerce-activation', plugins_url(  '/assets/css/activation.css', WC_PLUGIN_FILE ), array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce-gzd-activation', plugins_url(  '/assets/css/woocommerce-gzd-activation' . $suffix . '.css', WC_GERMANIZED_PLUGIN_FILE ), array(), WC_GERMANIZED_VERSION );
	}

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_head() {

		remove_submenu_page( 'index.php', 'wc-gzd-about' );

	}

	/**
	 * Into text/links shown on all about pages.
	 *
	 * @access private
	 * @return void
	 */
	private function intro() {

		// Flush after upgrades
		if ( ! empty( $_GET['wc-gzd-updated'] ) || ! empty( $_GET['wc-gzd-installed'] ) )
			flush_rewrite_rules();

		// Drop minor version if 0
		$major_version = substr( WC_germanized()->version, 0, 3 );
		?>
        <style>
            .wc-gzd-admin-welcome-hide-pro .wc-germanized-welcome-pro {
                display: none;
            }
        </style>
		<div class="wc-gzd-news <?php echo ( WC_germanized()->is_pro() ? 'wc-gzd-admin-welcome-hide-pro' : '' ); ?>">
		
			<h1>Willkommen bei Germanized</h1>
			<a class="wc-gzd-logo" href="https://vendidero.de/woocommerce-germanized" target="_blank" style="margin-right: 1em"></a>
			<div class="about-text woocommerce-about-text">
				<?php
					if ( ! empty( $_GET['wc-gzd-installed'] ) )
						$message = 'Super, alles erledigt!';
					elseif ( ! empty( $_GET['wc-gzd-updated'] ) )
						$message = 'Danke, dass du auf die neueste Version aktualisiert hast!';
					else
						$message = 'Danke f??r die Installation!';
					echo $message . '<br/>';
				?>
                Germanized <?php echo $major_version; ?> erweitert deine WooCommerce Installation um wichtige Funktionen f??r den deutschen Markt.</p>
			</div>
			<p class="woocommerce-actions wc-gzd-actions">
				<a href="<?php echo admin_url('admin.php?page=wc-settings&tab=germanized'); ?>" class="button button-primary">Einstellungen</a>
                <a href="https://vendidero.de/woocommerce-germanized#buy" target="_blank" class="button button-primary wc-germanized-welcome-pro">Upgrade zur Pro Version</a>
			</p>
			<div class="changelog vendipro new-feature">
                <h3>Produkteigenschaften im Warenkorb & Checkout</h3>
                <div class="left">
                    <p>
                        Mit der neuesten Version von Germanized k??nnt ihr nun bequem entscheiden, welche Produkteigenschaften im Checkout bzw. im Warenkorb angezeigt werden sollen.
                        Damit k??nnt ihr (neben der Warenkorbkurzbeschreibung) rechtlich relevante Produkteigenschaften bequem verwalten und zuordnen. Weitere Informationen zum Urteil des OLG in Bezug auf den Amazon-Checkout findet ihr <a href="https://www.onlinehaendler-news.de/e-recht/aktuelle-urteile/130463-amazon-check-out-rechtswidrig" target="_blank">hier</a>.
                    </p>
                    <p>Alternativ k??nnt ihr die Option zum Auflisten <strong>aller Produkteigenschaften</strong> im Warenkorb und in der Kasse in den Germanized-Einstellungen unter <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=germanized&section=display' ); ?>">Anzeige</a> aktivieren.</p>
                    <div class="vendipro-buttons wc-germanized-welcome-pro">
                        <a href="https://vendidero.de/woocommerce-germanized#pro" target="_blank" class="button button-primary wc-gzd-button">Pro Version entdecken</a>
                        <p class="price smaller">ab 69,95 ??? inkl. MwSt. - inkl. 1 Jahr Updates & Premium Support!</p>
                    </div>
                </div>
                <div class="right">
                    <img src="<?php echo WC_germanized()->plugin_url();?>/assets/images/attributes.png" />
                </div>
            </div>
			<div class="changelog vendipro new-feature wc-germanized-welcome-pro">
				<h3>Neu: USt.-ID in der Registrierung pr??fen <span class="wc-gzd-pro">pro</span></h3>
				<div class="left">
					<a href="https://vendidero.de/woocommerce-germanized#vat" target="_blank"><img src="<?php echo WC_germanized()->plugin_url();?>/assets/images/vat-check.png" style="border: none" /></a>
				</div>
				<div class="right">
					<p>
                        Viele Shop-Betreiber verkaufen an Firmenkunden (teilweise ausschlie??lich) und m??chten sicherstellen, dass Firmenkunden eine g??ltige USt.-ID besitzen.
                        Deshalb binden wir nun unseren USt.-ID-Check nun auf Wunsch auch in das Registrierungsformular ein. Bei Bedarf auch als Pflichtfeld.
					</p>
					<div class="wc-feature wc-vendipro-features feature-section col two-col">
						<div>
							<h4><i class="fa fa-magic"></i> USt.-ID-Check</h4>
							<p>USt.-ID bei der Registrierung abfragen, validieren und speichern. Die MwSt. wird anschlie??end u.U. abgezogen.</p>
						</div>
						<div class="last-feature">
							<h4><i class="fa fa-flag"></i> Basisland</h4>
							<p>Bei Bedarf kannst du selbst USt.-IDs deines Basislands abfragen und validieren lassen. Insbesondere f??r B2B Shops interessant.</p>
						</div>
					</div>
					<div class="vendipro-buttons">
						<a href="https://vendidero.de/woocommerce-germanized#pro" target="_blank" class="button button-primary wc-gzd-button">Pro Version entdecken</a>
						<p class="price smaller">ab 69,95 ??? inkl. MwSt. - inkl. 1 Jahr Updates & Premium Support!</p>
					</div>
				</div>
			</div>
			<div class="changelog vendipro new-feature wc-germanized-welcome-pro">
				<h3>Mehrstufige Kasse mit Daten??berpr??fung <span class="wc-gzd-pro">pro</span></h3>
				<div class="left">
					<a href="https://vendidero.de/woocommerce-germanized#multistep-checkout" target="_blank"><img src="<?php echo WC_germanized()->plugin_url();?>/assets/images/multistep-checkout.png" /></a>
				</div>
				<div class="right">
					<p>
						Du m??chtest deinen Checkout in mehrere Stufen aufteilen? Mit diesem neuen Feature ist das kein Problem mehr.
						Nutze ??hnlich wie andere gro??e deutsche Shops die Schritte Pers??nliche Daten, Zahlungsart und Best??tigen. Im Best??tigungs-Schritt
						werden dem Kunden alle Eingaben noch einmal aufgef??hrt.
					</p>
					<div class="wc-feature wc-vendipro-features feature-section col two-col">
						<div>
							<h4><i class="fa fa-paint-brush"></i> L??uft mit deinem Theme</h4>
							<p>Die mehrstufige Kasse kommt ganz ohne ??berschreiben von WooCommerce Templates aus.</p>
						</div>
						<div class="last-feature">
							<h4><i class="fa fa-adjust"></i> Farben & Optionen</h4>
							<p>Passe sowohl Farben als auch Beschriftungen einfach in den Einstellungen an.</p>
						</div>
						<div>
							<h4><i class="fa fa-check"></i> Daten Pr??fen</h4>
							<p>Lasse deine Kunden im letzten Schritt ihre Daten vor Bestellabschluss pr??fen und u.U. korrigieren.</p>
						</div>
						<div class="last-feature">
							<h4><i class="fa fa-refresh"></i> Kein Neuladen</h4>
							<p>Die Mehrstufige Kasse funktioniert komplett per Javascript. Inhalte werden asynchron nachgeladen.</p>
						</div>
					</div>
					<div class="vendipro-buttons">
						<a href="https://vendidero.de/woocommerce-germanized#pro" target="_blank" class="button button-primary wc-gzd-button">Pro Version entdecken</a>
						<p class="price smaller">ab 69,95 ??? inkl. MwSt. - inkl. 1 Jahr Updates & Premium Support!</p>
					</div>
				</div>
			</div>
			<div class="changelog vendipro wc-germanized-welcome-pro">
				<h3>VendiPro - Das WooCommerce Theme f??r den deutschen Markt</h3>
				<div class="left">
					<a href="https://vendidero.de/vendipro" target="_blank"><img src="<?php echo WC_germanized()->plugin_url();?>/assets/images/vendidero.jpg" /></a>
				</div>
				<div class="right">
					<p>Endlich ist es soweit - Ein WooCommerce Theme, perfekt auf den deutschen Markt abgestimmt.
					Mit <a href="https://vendidero.de/vendipro" target="_blank">VendiPro</a> wirken alle WooCommerce & Germanized Inhalte einfach noch professioneller.</p>
					<div class="wc-feature wc-vendipro-features feature-section col two-col">
						<div>
							<h4><i class="fa fa-mobile"></i> Responsive Layout</h4>
							<p>VendiPro hinterl??sst sowohl auf Desktop- als auch auf Mobilger??ten einen klasse Eindruck!</p>
						</div>
						<div class="last-feature">
							<h4><i class="fa fa-pencil"></i> Individualit??t</h4>
							<p>Passe VendiPro einfach per WordPress Theme Customizer an deine Bed??rfnisse an.</p>
						</div>
						<div>
							<h4><i class="fa fa-font"></i> Typisch deutsch</h4>
							<p>Gemacht f??r den deutschen Markt - und das merkt man sofort.</p>
						</div>
						<div class="last-feature">
							<h4><i class="fa fa-play-circle"></i> Slideshow</h4>
							<p>Einfach per Shortcode Slideshows und Produkt Carousels erstellen.</p>
						</div>
					</div>
					<div class="vendipro-buttons">
						<a href="https://vendidero.de/vendipro" target="_blank" class="button button-primary wc-gzd-button">mehr erfahren</a>
						<p class="price smaller">ab 49,95 ??? inkl. MwSt. - inkl. 1 Jahr Updates & Premium Support!</p>
					</div>
				</div>
			</div>
			<div class="changelog">
				<h3>Neu in Germanized 2.0</h3>
				<div class="wc-feature feature-section col three-col" style="margin-bottom: -30px">
					<div>
						<h4><i class="fa fa-check-square"></i> Rechtliche Checkboxen</h4>
						<p>
                            Germanized bietet nun ein ??bersichtliches UI zur Verwaltung deiner rechtl. Checkboxen an. Du kannst z.B. selbst festlegen, an welchen Orten die Checkbox angezeigt werden soll. Nutzer der Pro-Version k??nnen eigene Checkboxen hinzuf??gen.
                        </p>
					</div>
					<div>
						<h4><i class="fa fa-star"></i> Bewertungserinnerung Opt-Out</h4>
						<p>
							Kunden von Trusted Shops k??nnen mit Hilfe von Germanized eine Bewertungserinnerung per E-Mail versenden - daf??r gibt es nun eine separate Checkbox.
                            Zudem wird auf Wunsch in der Bestellbest??tigung ein Abmelde-Link platziert.
						</p>
					</div>
					<div class="last-feature">

					</div>
				</div>
				<div class="return-to-dashboard">
					<a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=germanized' ); ?>">zu den Einstellungen</a>
				</div>
			</div>
			<div class="changelog">
				<h3>Germanized - Funktions??bersicht</h3>
				<div class="wc-feature feature-section col three-col">
					<div>
						<h4><i class="fa fa-child"></i> Kleinunternehmerregelung</h4>
						<p>Mit nur einem Klick wird Dein Online-Shop ??19 UStG - kompatibel! Einfach die H??kchen innerhalb der Germanized Einstellungen setzen und schon geht es los.</p>
					</div>
					<div>
						<h4><i class="fa fa-truck"></i> Lieferzeiten</h4>
						<p>Erstelle einfach neue Lieferzeiten f??r deine Produkte. Die Lieferzeiten werden dann sowohl auf der Produktseite als auch im Bestellvorgang dargestellt.
						Die Bearbeitung der Lieferzeiten erfolgt ganz bequem per WordPress Taxonomy.</p>
					</div>
					<div class="last-feature">
						<h4><i class="fa fa-laptop"></i> Darstellungsoptionen</h4>
						<p>Wir haben die Darstellung des Warenkorbs und des Bezahlvorgangs f??r Dich an deutsche Rechtsgrundlagen angepasst. Zus??tzlich kannst Du selbst entscheiden, welche rechtlich relevanten Seiten Du wo und wie verlinken willst.</p>
					</div>
					<div>
						<h4><i class="fa fa-legal"></i> Rechtlich relevante Seiten</h4>
						<p>Erstelle ganz einfach alle rechtlich relevanten Seiten (z.B. Datenschutz, Widerrufsbelehrung).
						Wir setzen den Inhalt automatisch in die von Dir ausgew??hlten E-Mail-Templates ein und f??gen auf Wunsch auch Checkboxen zum Bezahlvorgang hinzu.</p>
					</div>
					<div>
						<h4><i class="fa fa-certificate"></i> Trusted Shops</h4>
						<p>Du m??chtest deine Trusted Shops Mitgliedschaft in WooCommerce nutzen? Kein Problem. Germanized hat die Schnittstelle zu Trusted Shops bereits implementiert.
						Klicke <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wc-settings&tab=germanized&section=trusted_shops' ), 'admin.php' ) ) ); ?>">hier</a> um die n??tigen Einstellungen vorzunehmen.</p>
					</div>
					<div class="last-feature">
						<h4>Und noch vieles mehr</h4>
						<p>Nat??rlich gibt es auch noch viele weitere Optionen, die wir f??r Dich implementiert haben. Du kannst z.B. den Button-Text im Bestellabschluss ganz bequem anpassen oder entscheiden ob du den "zum Warenkorb" - Button wirklich auch in der Produkt??bersicht haben m??chtest.</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<!--<div class="changelog point-releases"></div>-->

			

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wc-settings&tab=germanized' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to Germanized Settings', 'woocommerce-germanized' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Sends user to the welcome page on first activation
	 */
	public function welcome() {
		// Bail if no activation redirect transient is set
	    if ( ! get_transient( '_wc_gzd_activation_redirect' ) ) {
			return;
	    }

		// Delete the redirect transient
		delete_transient( '_wc_gzd_activation_redirect' );

		// Bail if we are waiting to install or update via the interface update/install links
		if ( get_option( '_wc_gzd_needs_update' ) == 1 || get_option( '_wc_gzd_needs_pages' ) == 1 ) {
			return;
		}

		// Bail if activating from network, or bulk, or within an iFrame
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || defined( 'IFRAME_REQUEST' ) ) {
			return;
		}

		if ( ( isset( $_GET['action'] ) && 'upgrade-plugin' == $_GET['action'] ) && ( isset( $_GET['plugin'] ) && strstr( $_GET['plugin'], 'woocommerce-germanized.php' ) ) ) {
			return;
		}

		wp_redirect( admin_url( 'index.php?page=wc-gzd-about' ) );
		exit;
	}
}

new WC_GZD_Admin_Welcome();

?>