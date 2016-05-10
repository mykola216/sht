<?php
/*
Plugin Name: WooCommerce Feedback Company
Depends: WooCommerce
Plugin URI: http://www.feedbackcompany.nl/
Description: Handige The Feedback Company integratie in WooCommerce
Version: 1.0
License: GPLv2
*/

if (!defined('ABSPATH'))
    {
    exit;
    // exit if accessed directly
    }

// check if WooCommerce is active

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    {
?>
<div id="message" class="error">
    <p>De WooCommerce Feedback Company plugin kan niet werken als Woocommerce zelf niet actief is. Activeer WooCommerce!</p>
</div>
<?php
    }

class WPfeedback
    {
    /**
     * start 
     */
    static $instance = false;
    private function __construct()
        {
        add_action('admin_menu', array(
            $this,
            'menu_settings'
        ));
        add_action('admin_init', array(
            $this,
            'reg_settings'
        ));
        add_action('admin_head', array(
            $this,
            'admin_css'
        ));
        add_action('init', array(
            $this,
            'feedback'
        ));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(
            $this,
            'fc_settings_link'
        ));
        }
    
    public static function getInstance()
        {
        
        if (!self::$instance)
            self::$instance = new self;
        return self::$instance;
        }
    
    public function menu_settings()
        {
        add_submenu_page('options-general.php', 'The Feedback Company', 'The Feedback Company', 'manage_options', 'fc-settings', array(
            $this,
            'fc_settings_display'
        ));
        }
    
    public function reg_settings()
        {
        register_setting('fc_options', 'fc_options');
        }
    
    public function admin_css()
        {
?>
     <style type="text/css">
        div#icon-fc {
            background:url(<?php
        echo plugins_url('/lib/img/fc-icon.png', __FILE__);
?>) no-repeat 0 0!important;
        }
        </style>
    <?php
        }
    
    /**
     * adds settings to plugin page
     */
    function fc_settings_link($links)
        {
        $url            = get_admin_url() . 'options-general.php?page=fc-settings';
        $settings_link1 = '<a href="' . $url . '">' . __('Settings') . '</a>';
        $settings_link2 = '<a href="https://www.feedbackcompany.nl/" target="_blank">feedbackcompany.nl</a>';
        array_unshift($links, $settings_link1, $settings_link2);
        return $links;
        }
    
    /**
     * trigger script loads based on settings
     */
    public function feedback()
        {
        $fc_options = get_option('fc_options');
        // no values have been entered. bail.
        
        if (empty($fc_options['connector']))
            add_action('admin_notices', array(
                $this,
                'my_admin_error_notice_connector'
            ));
        // fire if the order is set to pending
        elseif ($fc_options['trigger'] == 'iedere nieuwe bestelling')
            add_action('woocommerce_thankyou', array(
                $this,
                'wc_feedback'
            ), 10, 1);
        // fire if the order is set to proces
        elseif ($fc_options['trigger'] == 'een bestelling in verwerking')
            add_action('woocommerce_order_status_processing', array(
                $this,
                'wc_feedback'
            ), 10, 1);
        // fire if the order is set to completed
        elseif ($fc_options['trigger'] == 'een voltooide bestelling')
            add_action('woocommerce_order_status_completed', array(
                $this,
                'wc_feedback'
            ), 10, 1);
        else
            {
            return;
            }
        
        }
    
    /**
     * Error notice if no connector code is entered
     */
    function my_admin_error_notice_connector()
        {
        echo "<div id=\"message\" class=\"error\"><p>Je moet een connector code invullen! Deze tref je aan bij het The Feedback Company account.</p></div>";
        }
    
    /**
     * Error notice if cUrl and/or allow_url_fopen is not activ
     */
    function my_admin_error_notice_curl()
        {
        echo "<div id=\"message\" class=\"error\"><p>The Feedback Company plugin: helaas is de PHP extensie cUrl niet geïnstalleerd en/of allow_url_fopen is niet actief! De plugin kan daardoor geen connectie maken met The Feedback Company :-(</p></div>";
        }
    
    /**
     * WooCommerce transaction
     */
    
    
    public function wc_feedback($order_id)
        {
        $fc_options = get_option('fc_options');
        // A few conditionals
        $double     = $fc_options['double_feedback'];
        
        if ($double == 'Nee')
            {
            $fc_sdp = 0;
            }
        else
            {
            $fc_sdp = 1;
            }
        
        $tone_voice = $fc_options['tone'];
        
        if ($tone_voice == 'Informeel')
            {
            $fc_fct = 0;
            }
        else
            {
            $fc_fct = 1;
            }
        
        // get the WooCommerce details
        global $woocommerce;
        
        if (!$order_id)
            {
            return;
            }
        
        $order = new WC_Order($order_id);
        
        if ($order->has_status('failed'))
            {
            return;
            }
        else
            {
            // sets delay days
            $delay_time          = $fc_options['delay'];
            // sets delay remember
            $delay_remember      = $fc_options['remember'];
            // sets double invitation or not
            $send_double         = $fc_sdp;
            // sets tone of voice
            $tone_title          = $fc_fct;
            // connector code
            $cc_code             = $fc_options['connector'];
            // woocommerce billing e-mailaddress
            $user_mail           = $order->billing_email;
            // woocommerce order number
            $order_number        = $order->id;
            // user first name
            $order_first_name    = $order->billing_first_name;
            // user last name
            $order_last_name     = $order->billing_last_name;
            // informal salutation, combine first and last name
            $order_name_inf_comb = $order_first_name . ' ' . $order_last_name;
            // informal salutation, replace space with %20
            $order_name_inf      = str_replace(' ', '%20', $order_name_inf_comb);
            // formal salutation, last name
            $order_name_for_comb = 'heer/mevrouw%20' . $order_last_name;
            // formal salutation, replace space with %20
            $order_name_for      = str_replace(' ', '%20', $order_name_for_comb);
            // set email address to lower case
            $str_mailaddress     = strtolower($user_mail);
            // remove white space(s) and/or space(s) from connector code
            $cc_code             = str_replace(' ', '', $cc_code);
            $cc_code             = preg_replace('/\s+/', '', $cc_code);
            // do the Chksum
            $chsum               = $str_mailaddress;
            $arr1                = str_split($chsum);
            $sum				 = 0;
            foreach ($arr1 as $item)
                {
                $sum += ord($item);
                }
            // check salutation
            if ($fc_fct == 0)
                {
                $order_name = $order_name_inf;
                }
            else
                {
                $order_name = $order_name_for;
                }
            // construct the url
            $fc_url = 'https://connect.feedbackcompany.nl/feedback/?action=sendInvitation&connector=' . $cc_code . '&user=' . $str_mailaddress . '&delay=' . $delay_time . '&remindDelay=' . $delay_remember . '&resendIfDouble=' . $send_double . '&orderNumber=' . $order_number . '&aanhef=' . $order_name . '&chksum=' . $sum;
            
            // writing to error log during testing
            // write_log('THIS IS THE START OF MY CUSTOM DEBUG');
            // write_log($fc_url);
            
            // call the url with curl and if that does not work call the url with file_get_contents. If both fail display admin error message.
            $fc_urlsend = $fc_url;
            
            if (function_exists('curl_version'))
                {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $fc_urlsend);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $content = curl_exec($curl);
                curl_close($curl);
                }
            else if (file_get_contents(__FILE__) && ini_get('allow_url_fopen'))
                {
                $content = file_get_contents($fc_urlsend);
                }
            else
                {
                add_action('admin_notices', 'my_admin_error_notice_curl');
                }
            
            }
        
        }
     	
	/**
	 * display user settings
	 */
	 
    public function fc_settings_display() { ?>
    
    	<link href="<?php echo plugins_url( 'admin.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css">
        <div class="pea_admin_box"><img align="right" src="<?php echo plugins_url('/lib/img/logo.png', __FILE__); ?>">
        
        <h2>The Feedback Company  | Online klantfeedback en reviews voor bedrijven en webshops</h2>		<h3>Een plugin voor klantfeedback integratie</h3><p>Voor een ondernemer is het belangrijk om te weten wat er zich afspeelt onder zijn klanten. Wanneer klanten de mogelijkheid krijgen om feedback te geven, weet de ondernemer precies wat er onder hen leeft. Deze kennis kan het bedrijf inzetten om zijn processen verder te optimaliseren, klanttevredenheid en klantvertrouwen te verhogen.</p>
        <p><a href="http://www.feedbackcompany.nl" target="_blank">feedbackcompany.nl</a> | <a href="https://beheer.feedbackcompany.nl" target="_blank">beheer.feedbackcompany.nl</a></p>
		</div>
        <div class="wrap">
        <div class="icon32" id="icon-fc"><br></div>
        <h2><?php _e('Instellingen') ?></h2>

            <div class="options">
              <div class="fb_form_options">
                <form method="post" action="options.php">
                <?php
                settings_fields( 'fc_options' );
                $fc_options = get_option('fc_options');
                $fc_trigger = $fc_options['trigger'];
                $fc_double_feedback = $fc_options['double_feedback'];
                $fc_tone = $fc_options['tone'];
                ?>

                <table class="form-table fc-table">
                <tbody>
                    <tr>
                        <th><label for="fc_options[connector]"><?php _e('Uw connector code:') ?></label></th>
                        <td>
                            <input type="text"  placeholder="Voer hier je code in" style="width: 150px;" class="small-text" value="<?php if(isset($fc_options['connector'] )) echo $fc_options['connector']; ?>" id="connector" name="fc_options[connector]">
                            <span class="description"><?php _e('Vier hier de unieke connector code in van The Feedback Company.') ?></span>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="fc_options[delay]"><?php _e('Vertraging:') ?></label></th>
                        <td>
                            <input type="text" style="width: 35px;" class="small-text" value="<?php if(isset($fc_options['delay'] )) echo $fc_options['delay']; ?><?php if(!isset($fc_options['delay'] ) || $fc_options['delay'] == null) echo '7'; ?>" id="delay" name="fc_options[delay]">
                            <span class="description"><?php _e('Het aantal dagen dat het verzenden van de beoordeling vertraagd moet worden. 0 is direct versturen. Standaardwaarde is 7.') ?></span>
                        </td>
                    </tr>
              
                    <tr>
                        <th><label for="fc_options[remember]"><?php _e('Herinneringsvertraging:') ?></label></th>
                        <td>
                            <input type="text" style="width: 35px;" class="small-text" value="<?php if(isset($fc_options['remember'] )) echo $fc_options['remember']; ?><?php if(!isset($fc_options['remember'] ) || $fc_options['remember'] == null) echo '14'; ?>" id="remember" name="fc_options[remember]">
                            <span class="description"><?php _e('Het aantal dagen waarna een herinnering moet worden verstuurd. Standaardwaarde is 14. 0 is geen herinnering versturen.') ?></span>
                        </td>
                    </tr>
                    
					<tr>
					    <th><label for="fc_options[double_feedback]"><?php _e('Verstuur dubbele beoordelingen:') ?></label></th>
					    <td>
					   				<select id="fc_double_feedback" name="fc_options[double_feedback]">
					   					<option <?php if ('Nee' == $fc_double_feedback)echo 'selected="selected"'; ?>>Nee</option>
					   					<option <?php if ('Ja' == $fc_double_feedback)echo 'selected="selected"'; ?>>Ja</option>
					   				</select>		
					   				<span class="description"><?php _e('Gebruik deze optie om aan te geven of een klant meerdere beoordelingsverzoeken per order mag ontvangen.') ?></span>	
					   				</td>
					</tr>
					
					<tr>
					    <th><label for="fc_options[tone]"><?php _e('Aanhef beoordelingsverzoek:') ?></label></th>
					    <td>
					   				<select id="fc_tone" name="fc_options[tone]">
					   					<option <?php if ('Informeel' == $fc_tone)echo 'selected="selected"'; ?>>Informeel</option>
					   					<option <?php if ('Formeel' == $fc_tone)echo 'selected="selected"'; ?>>Formeel</option>
					   				</select>		
					   				<span class="description"><?php _e('Informeel is "Beste Theo Testpersoon". Formeel is "Geachte heer/mevrouw Testpersoon".') ?></span>	
					   				</td>
					</tr>
		
					<tr>
					    <th><label for="fc_options[trigger]"><?php _e('Verstuur het beoordelingsverzoek bij:') ?></label></th>
					    <td>
					   				<select id="fc_trigger" name="fc_options[trigger]">
					   			     	<option <?php if ('een voltooide bestelling' == $fc_trigger)echo 'selected="selected"'; ?>>een voltooide bestelling</option>
					   			     	<option <?php if ('een bestelling in verwerking' == $fc_trigger)echo 'selected="selected"'; ?>>een bestelling in verwerking</option>
					   					<option <?php if ('iedere nieuwe bestelling' == $fc_trigger)echo 'selected="selected"'; ?>>iedere nieuwe bestelling</option>
					   					
					   					
					   				</select>		
					   				<span class="description"><?php _e('Kies hier het moment waarop het beoordelingsverzoek naar The Feedback Company wordt verstuurd.') ?></span>	
					   				</td>
					</tr>
					
                </tbody>
                </table>
                
  				<p><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
                </form>
                </div>
            </div>
        </div>
    <?php }

}
$WPfeedback = WPfeedback::getInstance();