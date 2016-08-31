<?php
/*
Plugin Name: Feedback Company XML feed
Depends: A Feedback Company account
Plugin URI: http://www.feedbackcompany.nl/
Description: Handige The Feedback Company XML integratie in WordPress
Version: 1.2.3
License: GPLv2
*/

if (!defined('ABSPATH')) {
    exit;
    // exit if accessed directly
}

class WPfeedback_xml {
    
    // start
    
    static $instance = false;
    private function __construct() {
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
        add_action('wp_enqueue_scripts', array(
            $this,
            'fc_xml_style_front'
        ));
        add_action('admin_enqueue_scripts', array(
            $this,
            'fc_xml_style_back'
        ));
        add_action('init', array(
            $this,
            'feedback_xml'
        ));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(
            $this,
            'fc_xml_settings_link'
        ));
    }
    
    public static function getInstance() {
        if (!self::$instance)
            self::$instance = new self;
        return self::$instance;
    }
    
    public function menu_settings() {
        add_submenu_page('options-general.php', 'The Feedback Company XML feed', 'The Feedback Company XML feed', 'manage_options', 'fc-xml-settings', array(
            $this,
            'fc_xml_settings_display'
        ));
    }
    
    public function reg_settings() {
        register_setting('fc_xml_options', 'fc_xml_options');
    }
    
    // add stylesheet to front end
    
    function fc_xml_style_front() {
        wp_enqueue_style('prefix-style', plugins_url('/css/fc_style.css', __FILE__));
    }
    
    // add stylesheet to front end
    
    function fc_xml_style_back() {
        wp_enqueue_style('prefix-style', plugins_url('/css/admin.css', __FILE__));
    }
    
    public function admin_css() {
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
    
    // adds settings to plugin page
    
    function fc_xml_settings_link($links) {
        $url            = get_admin_url() . 'options-general.php?page=fc-xml-settings';
        $settings_link1 = '<a href="' . $url . '">' . __('Settings') . '</a>';
        $settings_link2 = '<a href="https://www.feedbackcompany.nl/" target="_blank">feedbackcompany.nl</a>';
        array_unshift($links, $settings_link1, $settings_link2);
        return $links;
    }
    
    // trigger script load based on settings
    
    public function feedback_xml() {
        $fc_xml_options = get_option('fc_xml_options');
        
        // no values have been entered. bail.
        
        if (empty($fc_xml_options['feed']))
            add_action('admin_notices', array(
                $this,
                'my_admin_error_notice_xml_feed'
            ));
        else {
            $this->feeder();
        }
    }
    
    // error notice if no feed is entered
    
    function my_admin_error_notice_xml_feed() {
        echo "<div id=\"message\" class=\"error\"><p>U moet een XML feed invullen! Deze treft u aan bij het The Feedback Company account.</p></div>";
    }
    
    // cache XML feed and parse content
    
    public function feeder() {
        $cache_time     = 3600 * 12; // 12 hour cycle for catching feed
        $cache_file     = plugin_dir_path(__FILE__) . 'cache/feed-' . get_current_blog_id() . '.xml'; // insert blog_id in filename for multinetwork
        $timedif        = @(time() - filemtime($cache_file));
        $fc_xml_options = get_option('fc_xml_options');
        $xml_feed       = $fc_xml_options['feed'];
        
        // remove white space(s) and/or space(s) from connector code
        
        $xml_feed = str_replace(' ', '', $xml_feed);
        $xml_feed = preg_replace('/\s+/', '', $xml_feed);
        if (file_exists($cache_file) && $timedif < $cache_time) {
            $fc_curl = plugin_dir_url(__FILE__) . 'cache/feed-' . get_current_blog_id() . '.xml';
            $fc_ch   = curl_init();
            curl_setopt($fc_ch, CURLOPT_URL, $fc_curl);
            curl_setopt($fc_ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($fc_ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($fc_ch, CURLOPT_USERAGENT, 'User-Agent: curl/7.39.0');
            $string = curl_exec($fc_ch);
            curl_close($fc_ch);
        } else {
            $fc_ch = curl_init();
            curl_setopt($fc_ch, CURLOPT_URL, $xml_feed);
            curl_setopt($fc_ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($fc_ch, CURLOPT_CONNECTTIMEOUT, 15); // time out if machine is down
            curl_setopt($fc_ch, CURLOPT_TIMEOUT, 15); // time out if feed is down
            $string = curl_exec($fc_ch);
            curl_close($fc_ch);
            
            if ($f = @fopen($cache_file, 'w')) {
                fwrite($f, $string, strlen($string));
                fclose($f);
            }
        }
        
        $xml_feed   = simplexml_load_string($string);
        $xml_data_s = sprintf("%s", $xml_feed->score);
        $xml_data_r = sprintf("%s", $xml_feed->noReviews);
        $xml_data_b = sprintf("%s", $xml_feed->scoremax);
        
        // set global variables for score and reviews to access them sitewide
        
        global $xml_global_s;
        $xml_global_s = $xml_data_s;
        global $xml_global_r;
        $xml_global_r = $xml_data_r;
        global $xml_global_b;
        $xml_global_b = $xml_data_b;
        global $xml_global_f;
        $xml_global_f = $xml_feed;
        
        // setup star rating
        $rating = strtolower($xml_feed->score);
        global $star_global;
        
        // check if base score is 5
        if ($xml_global_b == 5) {
            
            switch ($rating) {
                case '0':
                    echo '';
                    break;
                
                case $rating >= 0.1 and $rating <= 0.5:
                    $star_global = 1;
                    break;
                
                case $rating >= 0.6 and $rating <= 1:
                    $star_global = 2;
                    break;
                
                case $rating >= 1.1 and $rating <= 1.5:
                    $star_global = 3;
                    break;
                
                case $rating >= 1.6 and $rating <= 2:
                    $star_global = 4;
                    break;
                
                case $rating >= 2.1 and $rating <= 2.5:
                    $star_global = 5;
                    break;
                
                case $rating >= 2.6 and $rating <= 3:
                    $star_global = 6;
                    break;
                
                case $rating >= 3.1 and $rating <= 3.5:
                    $star_global = 7;
                    break;
                
                case $rating >= 3.6 and $rating <= 4:
                    $star_global = 8;
                    break;
                
                case $rating >= 4.1 and $rating <= 4.5:
                    $star_global = 9;
                    break;
                
                case $rating >= 4.6 and $rating <= 5:
                    $star_global = 10;
                    break;
            }
        }
        // if base score is not 5 it has to be 10, right?
        else {
            switch ($rating) {
                case '0':
                    echo '';
                    break;
                
                case $rating >= 0.1 and $rating <= 1:
                    $star_global = 1;
                    break;
                
                case $rating >= 1.1 and $rating <= 2:
                    $star_global = 2;
                    break;
                
                case $rating >= 2.1 and $rating <= 3:
                    $star_global = 3;
                    break;
                
                case $rating >= 3.1 and $rating <= 4:
                    $star_global = 4;
                    break;
                
                case $rating >= 4.1 and $rating <= 5:
                    $star_global = 5;
                    break;
                
                case $rating >= 5.1 and $rating <= 6:
                    $star_global = 6;
                    break;
                
                case $rating >= 6.1 and $rating <= 7:
                    $star_global = 7;
                    break;
                
                case $rating >= 7.1 and $rating <= 8:
                    $star_global = 8;
                    break;
                
                case $rating >= 8.1 and $rating <= 9:
                    $star_global = 9;
                    break;
                
                case $rating >= 9.1 and $rating <= 10:
                    $star_global = 10;
                    break;
            }
        }
    }
    
    // display user settings
    
    public function fc_xml_settings_display() {
?>
	    
	    	
	        <div class="pea_admin_box"><img align="right" src="<?php
        echo plugins_url('/lib/img/logo.png', __FILE__);
?>">
	        
	        <h2>The Feedback Company  | Online klantfeedback en reviews voor bedrijven en webshops</h2>		<h3>Een plugin voor klantfeedback XML feed integratie</h3><p>Voor een ondernemer is het belangrijk om te weten wat er zich afspeelt onder zijn klanten. Wanneer klanten de mogelijkheid krijgen om feedback te geven, weet de ondernemer precies wat er onder hen leeft. Deze kennis kan het bedrijf inzetten om zijn processen verder te optimaliseren, klanttevredenheid en klantvertrouwen te verhogen.</p>
	        <p><a href="http://www.feedbackcompany.nl" target="_blank">feedbackcompany.nl</a> | <a href="https://beheer.feedbackcompany.nl" target="_blank">beheer.feedbackcompany.nl</a></p>
			</div>
	        <div class="wrap">
	        <div class="icon32" id="icon-fc"><br /></div>
	        <h2><?php
        _e('Instellingen');
?></h2>
	              <div class="options">
	              <div class="fb_form_options">
	                <form method="post" action="options.php">
	                <?php
        settings_fields('fc_xml_options');
        $fc_xml_options = get_option('fc_xml_options');
        $fc_xml_load    = (isset($fc_xml_options['load']) && $fc_xml_options['load'] == 'true' ? 'checked="checked"' : '');
?>
					
	                <table class="form-table fc-table">
	                <tbody>
	                <tr>
	                    <th><label><?php
        _e('Wat u moet weten:');
?></label></th>
	                                    <td>
	                                       <p>De juiste werking van deze plugin is afhankelijk van de ingevoerde XML-feed. Let even goed op twee elementen in de feed die aanwezig moeten zijn. Hieronder een testfeed van The Feedback Company als voorbeeld:<br /><br /><font color=blue>https://beoordelingen.feedbackcompany.nl/samenvoordeel/scripts/flexreview/getreviewxml.cfm?ws=2789&publishIDs=1<font color=red>&nor=20</font>&publishDetails=1&publishDetailScores=1&publishOnHold=0&sort=desc<font color=red>&Basescore=5</font>&foreign=1&emlpass=test&v=3</font><br /><br /></p>
	                                       <p>Belangrijk is dat het element <font color=red>&nor=20</font> aanwezig is en dus het getal 20 bevat. Dit zijn namelijk het aantal reviews die de feed bevat. In de settings van de widgets die met deze plugin worden ge&iuml;nstalleerd kunt u het aantal te tonen reviews makkelijk aanpassen. Verder is het element <font color=red>&Basescore=5</font> of <font color=red>&Basescore=10</font> belangrijk. De feed moet dit element bevatten. Afhankelijk van deze twee settings (5 of 10) gebruikt de plugin een score op basis van 5 of 10.</p>
	                                       <br /><p>Deze plugin bevat twee widgets. Ga naar de widgetinstellingen om ze in uw site op te nemen. "The Feedback Company Score" en "The Feedback Company Reviews" widgets moeten op uw homepage staan zodat de Google zoekmachine uw score kan zien en dus de bekende sterren in de resultaten kan toevoegen. Dit zal overigens niet meteen het geval zijn. Dat kan best een paar weken duren.</p><br />
	                                      <img align="left" width="350px" src="<?php
        echo plugins_url('/lib/img/snippet.jpg', __FILE__);
?>"
	                                    </td>
	                                </tr>
	                    <tr>
	                        <th><label for="fc_xml_options[feed]"><?php
        _e('Uw XML feed:');
?></label></th>
	                        <td>
	                            <input type="text"  placeholder="Voer hier de feed in" style="width: 450px;" class="small-text" value="<?php
        if (isset($fc_xml_options['feed']))
            echo $fc_xml_options['feed'];
?>" id="feed" name="fc_xml_options[feed]">
	                            <span class="description"><?php
        _e('Voer hier de XML feed in van The Feedback Company.');
?></span>
	                        </td>
	                    </tr>
	                    
	                                    <tr>
	                                                    <th><label><?php
        _e('Leeg cache:');
?></label></th>
	                                                                    <td>
	                                                                       <p>De XML-feed wordt tijdelijk opgeslagen in de map "cache" van deze plugin. Als u wijzigingen aanbrengt aan de XML-feed dan verwijder daarna het bestand "feed-(nummer).xml" in de cache map van deze plugin.</p>
	                                                                       
	                                                                       	                                                       </td>
	                                                                </tr>
	                    
	                    
	                    <tr>
	                        <th><label for="fc_xml_options[load]"><?php
        _e('Font Awesome');
?></label></th>
	                        <td><label for="fc_xml_options[load]"><input type="checkbox" id="v" name="fc_xml_options[load]" value="true" <?php
        echo $fc_xml_load;
?> /> Gebruik geen Font Awesome in deze plugin.<span class="description"> (Deze plugin gebruikt de icons van <a href="http://fortawesome.github.io/Font-Awesome/" target="_blank">Font Awesome</a>. Kan natuurlijk zijn dat uw theme deze iconset reeds gebruikt. Als dat zo is dan vink deze optie aan. Dan worden ze namelijk maar 1 keer geladen.)</span>
	                        </td>
	                    </tr>
	                       	
	                </tbody>
	                </table>
	  				<p><input type="submit" class="button-primary" value="<?php
        _e('Save Changes');
?>" /></p>
	                </form>
	                </div>
	            </div>
	        </div>
	    <?php
    }
}

// font awesome checks

$fc_xml_options = get_option('fc_xml_options');

// check on or off font awesome

$fc_xml_load = (isset($fc_xml_options['load']) && $fc_xml_options['load'] == 'true' ? true : false);

// fire

if ($fc_xml_load === false)
    add_action('wp_head', 'add_fawesome_mc');

// adds font awesome

function add_fawesome_mc() {
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
}


// creating Feedback Company Score Widget

class fcs_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
        // base ID of your widget
            'fcs_widget', 
        // widget name
            __('The Feedback Company Score', 'fcs_widget_domain'), 
        // widget description
            array(
            'description' => __('Publiceer uw score.', 'fcs_widget_domain')
        ));
    }
    
    // creating widget front-end
    
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        
        // the variables from the widget settings
        
        $fcs_bn     = $instance['bname'];
        $fcs_t1     = $instance['text_1'];
        $fcs_t2     = $instance['text_2'];
        $fcs_t3     = $instance['text_3'];
        $fcs_t4     = $instance['text_4'];
        $star_sizes = $instance['star_size'];
        
        // setting up the global
        
        global $global_fcs_bn;
        $global_fcs_bn = $fcs_bn;
        
        // before and after widget arguments are defined by themes
        
        echo $args['before_widget'];
        
        // display the widget title if one was input (before and after defined by themes)
        
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        
        // display the review stars depending on size setting
        
        global $star_global;
        if (($star_global == 1) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star-half-o"></i></p>';
        }
        
        if (($star_global == 1) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star-half-o fa-lg"></i></p>';
        }
        
        if (($star_global == 1) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star-half-o fa-2x"></i></p>';
        }
        
        if (($star_global == 1) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star-half-o fa-3x"></i></p>';
        }
        
        if (($star_global == 2) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i></p>';
        }
        
        if (($star_global == 2) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i></p>';
        }
        
        if (($star_global == 2) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i></p>';
        }
        
        if (($star_global == 2) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i></p>';
        }
        
        if (($star_global == 3) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></p>';
        }
        
        if (($star_global == 3) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star-half-o fa-lg"></i></p>';
        }
        
        if (($star_global == 3) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star-half-o fa-2x"></i></p>';
        }
        
        if (($star_global == 3) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star-half-o fa-3x"></i></p>';
        }
        
        if (($star_global == 4) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i></p>';
        }
        
        if (($star_global == 4) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i></p>';
        }
        
        if (($star_global == 4) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i></p>';
        }
        
        if (($star_global == 4) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i></p>';
        }
        
        if (($star_global == 5) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></p>';
        }
        
        if (($star_global == 5) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star-half-o fa-lg"></i></p>';
        }
        
        if (($star_global == 5) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star-half-o fa-2x"></i></p>';
        }
        
        if (($star_global == 5) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star-half-o fa-3x"></i></p>';
        }
        
        if (($star_global == 6) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></p>';
        }
        
        if (($star_global == 6) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i></p>';
        }
        
        if (($star_global == 6) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i></p>';
        }
        
        if (($star_global == 6) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i></p>';
        }
        
        if (($star_global == 7) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></p>';
        }
        
        if (($star_global == 7) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star-half-o fa-lg"></i></p>';
        }
        
        if (($star_global == 7) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star-half-o fa-2x"></i></p>';
        }
        
        if (($star_global == 7) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star-half-o fa-3x"></i></p>';
        }
        
        if (($star_global == 8) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></p>';
        }
        
        if (($star_global == 8) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i></p>';
        }
        
        if (($star_global == 8) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i></p>';
        }
        
        if (($star_global == 8) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i></p>';
        }
        
        if (($star_global == 9) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></p>';
        }
        
        if (($star_global == 9) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star-half-o fa-lg"></i></p>';
        }
        
        if (($star_global == 9) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star-half-o fa-2x"></i></p>';
        }
        
        if (($star_global == 9) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star-half-o fa-3x"></i></p>';
        }
        
        if (($star_global == 10) && ($instance['star_size'] == 'klein')) {
            echo '<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></p>';
        }
        
        if (($star_global == 10) && ($instance['star_size'] == 'middel')) {
            echo '<p><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i><i class="fa fa-star fa-lg"></i></p>';
        }
        
        if (($star_global == 10) && ($instance['star_size'] == 'groot')) {
            echo '<p><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i><i class="fa fa-star fa-2x"></i></p>';
        }
        
        if (($star_global == 10) && ($instance['star_size'] == 'mega')) {
            echo '<p><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i><i class="fa fa-star fa-3x"></i></p>';
        }
        
        $instance['star_size'];
        
        // setting globals
        
        global $xml_global_s;
        global $xml_global_r;
        global $xml_global_b;
        
        // display text
        
?>

	<div class="fc_rating" itemscope itemtype="http://schema.org/LocalBusiness">
		<meta itemprop="name" content="<?php
        echo $fcs_bn;
?>">
		<p><span itemscope  itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
		<?php
        echo $fcs_t1;
?> <span class="fc_rating_score" itemprop="ratingValue"><?php
        echo $xml_global_s;
?></span> <?php
        echo $fcs_t2;
?> <span class="fc_rating_best" itemprop="bestRating"><?php
        echo $xml_global_b;
?></span> <?php
        echo $fcs_t4;
?> <span class="fc_rating_reviews" itemprop="reviewCount"><?php
        echo $xml_global_r;
?></span>  <meta itemprop="worstRating" content="1"/>
		<?php
        echo $fcs_t3;
?>
		</span></p>
		</div>
		
		<?php
        
        // after widget (defined by themes)
        
        echo $args['after_widget'];
    }
    
    // widget Backend
    
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Uw titel', 'fcs_widget_domain');
        }
        
        $defaults = array(
            'bname' => __('Uw bedrijfsnaam of sitenaam', 'example'),
            'text_1' => __('Wij scoren', 'example'),
            'text_2' => __('van', 'example'),
            'text_4' => __('sterren, berekend uit', 'example'),
            'text_3' => __('reviews van klanten.', 'example'),
            'star_size' => 'middel'
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        
        // widget admin form
        
?>
<p>
<label for="<?php
        echo $this->get_field_id('title');
?>"><?php
        _e('Title:');
?></label> 
<input class="widefat" id="<?php
        echo $this->get_field_id('title');
?>" name="<?php
        echo $this->get_field_name('title');
?>" type="text" value="<?php
        echo esc_attr($title);
?>" />
</p>

		<!-- user information -->
		<p>U kunt de tekst in de widget aanpassen. De volgorde van de onderdelen in de widget is:<br /><br /><font color="blue">{tekst 1}</font> <font color="red">{score reviews}</font> <font color="blue">{tekst 2}</font> <font color="red">{max aantal (5 of 10) sterren}</font> <font color="blue">{tekst 3}</font> <font color="red">{aantal reviews}</font> <font color="blue">{tekst 4}</font></p>
		
		<!-- name business -->
		<p>
			<label for="<?php
        echo $this->get_field_id('bname');
?>"><?php
        _e('Bedrijfsnaam (niet zichtbaar, nodig voor Rich Snippets):', 'example');
?></label>
			<input id="<?php
        echo $this->get_field_id('bname');
?>" name="<?php
        echo $this->get_field_name('bname');
?>" value="<?php
        echo $instance['bname'];
?>" style="width:100%;" />
		</p>
		
		<!-- text 1 input -->
		<p>
			<label for="<?php
        echo $this->get_field_id('text_1');
?>"><?php
        _e('Tekst 1:', 'example');
?></label>
			<input id="<?php
        echo $this->get_field_id('text_1');
?>" name="<?php
        echo $this->get_field_name('text_1');
?>" value="<?php
        echo $instance['text_1'];
?>" style="width:100%;" />
		</p>
		
		<!-- text 2 input -->
		<p>
			<label for="<?php
        echo $this->get_field_id('text_2');
?>"><?php
        _e('Tekst 2:', 'example');
?></label>
			<input id="<?php
        echo $this->get_field_id('text_2');
?>" name="<?php
        echo $this->get_field_name('text_2');
?>" value="<?php
        echo $instance['text_2'];
?>" style="width:100%;" />
		</p>
		
		<!-- text 4 input -->
		<p>
			<label for="<?php
        echo $this->get_field_id('text_4');
?>"><?php
        _e('Tekst 3:', 'example');
?></label>
			<input id="<?php
        echo $this->get_field_id('text_4');
?>" name="<?php
        echo $this->get_field_name('text_4');
?>" value="<?php
        echo $instance['text_4'];
?>" style="width:100%;" />
		</p>
		
		<!-- text 3 input -->
		<p>
			<label for="<?php
        echo $this->get_field_id('text_3');
?>"><?php
        _e('Tekst 4:', 'example');
?></label>
			<input id="<?php
        echo $this->get_field_id('text_3');
?>" name="<?php
        echo $this->get_field_name('text_3');
?>" value="<?php
        echo $instance['text_3'];
?>" style="width:100%;" />
		</p>
		
		<!-- star size: Select Box -->
		<p>
			<label for="<?php
        echo $this->get_field_id('star_size');
?>"><?php
        _e('Afmeting sterren:', 'example');
?></label> 
			<select id="<?php
        echo $this->get_field_id('star_size');
?>" name="<?php
        echo $this->get_field_name('star_size');
?>" class="widefat" style="width:100%;">
				<option <?php
        if ('klein' == $instance['star_size'])
            echo 'selected="selected"';
?>>klein</option>
				<option <?php
        if ('middel' == $instance['star_size'])
            echo 'selected="selected"';
?>>middel</option>
				<option <?php
        if ('groot' == $instance['star_size'])
            echo 'selected="selected"';
?>>groot</option>
				<option <?php
        if ('mega' == $instance['star_size'])
            echo 'selected="selected"';
?>>mega</option>
			</select>
		</p>
		

<?php
    }
    
    // updating widget replacing old instances with new
    
    public function update($new_instance, $old_instance) {
        $instance              = array();
        $instance['title']     = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['bname']     = strip_tags($new_instance['bname']);
        $instance['text_1']    = strip_tags($new_instance['text_1']);
        $instance['text_2']    = strip_tags($new_instance['text_2']);
        $instance['text_3']    = strip_tags($new_instance['text_3']);
        $instance['text_4']    = strip_tags($new_instance['text_4']);
        $instance['star_size'] = $new_instance['star_size'];
        return $instance;
    }
} // class fcs_widget ends here

// register and load the widget

function fcs_load_widget() {
    register_widget('fcs_widget');
}

add_action('widgets_init', 'fcs_load_widget');

// creating Feedback Company Reviews Widget

class fcr_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
        // base ID of your widget
            'fcr_widget', 
        // widget name
            __('The Feedback Company Reviews', 'fcr_widget_domain'), 
        // widget description
            array(
            'description' => __('Publiceer uw reviews.', 'fcr_widget_domain')
        ));
    }
    
    // creating widget front-end
    
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        
        // the variables from the widget settings
        
        $fc_count        = $instance['fc_review_count'];
        $fc_truncate     = $instance['fc_truncate'];
        $fc_link         = $instance['link_all'];
        $fc_bnamereviews = $instance['bnamereviews'];
        
        // before and after widget arguments are defined by themes
        
        echo $args['before_widget'];
        
        // display the widget title if one was input (before and after defined by themes)
        
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        
        // setting globals
        
        global $xml_global_f;
        
        // display reviews
        
        $count = 0;
        $max   = $fc_count;
        
        // truncate review text
        
        function myxmlTruncate($fc_string, $fc_limit, $fc_break=".", $fc_pad="(...)")
        {
          // return with no change if string is shorter than $limit
          if(strlen($fc_string) <= $fc_limit) echo $fc_string;
        
          // is $break present between $limit and the end of the string?
          elseif(false !== ($fc_breakpoint = stripos($fc_string, $fc_break, $fc_limit))) {
            if($fc_breakpoint < strlen($fc_string) - 1) {
              $fc_string = substr($fc_string, 0, $fc_breakpoint) . $fc_pad;
            }
			   echo $fc_string;
		   } 
        }
                
        // spitting out the seperate reviews on chrono order
?>	
		<div itemprop="itemReviewed" itemscope itemtype="http://schema.org/LocalBusiness"></div>
		<div id="fc_xml_box">
		    <ul>
		<?php
        
        if ($instance['fc_sorting'] == 'chronologisch') {
            
            foreach ($xml_global_f->reviewDetails->reviewDetail as $val) {
                if ($count < $max) {
?>
		        <li><p><i class="fa fa-comment-o fa-flip-horizontal fa-lg"></i>&nbsp;&nbsp;
		        <span itemprop="review" itemscope itemtype="http://schema.org/Review">
		            <span itemprop="name"><strong><?php
                    echo $val->user;
?></strong></span><br /><br>
		            <span itemprop="reviewBody"> <?php           
		     		$trun_xml_string = $val->text;
		     		$limit = $instance['fc_truncate'];
		     		myxmlTruncate($trun_xml_string, $limit);
?></span>
		       <span content="<?php
                    echo $val->user;
?>" itemprop="author"></span><span content="<?php
                    echo $fc_bnamereviews;
?>" itemprop="itemReviewed"></span></p></li>
		       <?php
                }
                
                $count++;
            }
        }
?>
	<?php
        // spitting out the seperate reviews in random order
        
        if ($instance['fc_sorting'] == 'willekeurig') {
            
            foreach ($xml_global_f->reviewDetails->reviewDetail as $val)
                $fcs_array[] = $val;
            shuffle($fcs_array);
            foreach ($fcs_array as $val) {
                if ($count < $max) {
?>
		        <li><p><i class="fa fa-comment-o fa-flip-horizontal fa-lg"></i>&nbsp;&nbsp;
		        <span itemprop="review" itemscope itemtype="http://schema.org/Review">
		            <span itemprop="name"><strong><?php
                    echo $val->user;
?></strong></span><br /><br>
		            <span itemprop="reviewBody"> <?php
                    $trun_xml_string = $val->text;
                    $limit = $instance['fc_truncate'];
                    myxmlTruncate($trun_xml_string, $limit);
?></span>
		       <span content="<?php
                    echo $val->user;
?>" itemprop="author"></span><span content="<?php
                    echo $fc_bnamereviews;
?>" itemprop="itemReviewed"></span></p></li>
		       <?php
                }
                
                $count++;
            }
        }
?>
		  </ul>
		 </div>
		<?php
        
        // spitting out the all reviews link
        
        echo '
		    </ul>
		    <p><i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;<a href="';
        echo $fc_link;
        echo '" target="_blank">Alle reviews</a></p>
		';
        
        // after widget (defined by themes)
        
        echo $args['after_widget'];
    }
    
    // widget Backend
    
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Uw titel', 'fcr_widget_domain');
        }
        
        $defaults = array(
            'fc_review_count' => '2',
            'fc_sorting' => 'chronologisch',
            'fc_truncate' => '250',
            'link_all' => __('https://beoordelingen.feedbackcompany.nl/', 'example'),
            'bnamereviews' => __('Uw bedrijfsnaam of sitenaam', 'example')
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        
        // widget admin form
        
?>
		<p>
		<label for="<?php
        echo $this->get_field_id('title');
?>"><?php
        _e('Title:');
?></label> 
		<input class="widefat" id="<?php
        echo $this->get_field_id('title');
?>" name="<?php
        echo $this->get_field_name('title');
?>" type="text" value="<?php
        echo esc_attr($title);
?>" />
		</p>

		<!-- review count: select box -->
		<p>
			<label for="<?php
        echo $this->get_field_id('fc_review_count');
?>"><?php
        _e('Aantal te tonen reviews:', 'example');
?></label> 
			<select id="<?php
        echo $this->get_field_id('fc_review_count');
?>" name="<?php
        echo $this->get_field_name('fc_review_count');
?>" class="widefat" style="width:100%;">
				<option <?php
        if ('1' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>1</option>
				<option <?php
        if ('2' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>2</option>
				<option <?php
        if ('3' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>3</option>
				<option <?php
        if ('4' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>4</option>
				<option <?php
        if ('5' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>5</option>
				<option <?php
        if ('6' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>6</option>
				<option <?php
        if ('7' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>7</option>
				<option <?php
        if ('8' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>8</option>
				<option <?php
        if ('9' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>9</option>
				<option <?php
        if ('10' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>10</option>
				<option <?php
        if ('11' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>11</option>
				<option <?php
        if ('12' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>12</option>
				<option <?php
        if ('13' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>13</option>
				<option <?php
        if ('14' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>14</option>
				<option <?php
        if ('15' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>15</option>
				<option <?php
        if ('16' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>16</option>
				<option <?php
        if ('17' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>17</option>
				<option <?php
        if ('18' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>18</option>
				<option <?php
        if ('19' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>19</option>
				<option <?php
        if ('20' == $instance['fc_review_count'])
            echo 'selected="selected"';
?>>20</option>
			</select>
		</p>
		
		
		<!-- sorting reviews -->
		<p>
			<label for="<?php
        echo $this->get_field_id('fc_sorting');
?>"><?php
        _e('Sorteer reviews:', 'example');
?></label> 
			<select id="<?php
        echo $this->get_field_id('fc_sorting');
?>" name="<?php
        echo $this->get_field_name('fc_sorting');
?>" class="widefat" style="width:100%;">
				<option <?php
        if ('chronologisch' == $instance['fc_sorting'])
            echo 'selected="selected"';
?>>chronologisch</option>
				<option <?php
        if ('willekeurig' == $instance['fc_sorting'])
            echo 'selected="selected"';
?>>willekeurig</option>
			</select>
		</p>
		
		<!-- truncating text reviews -->
		<p>
			<label for="<?php
        echo $this->get_field_id('fc_truncate');
?>"><?php
        _e('Begrens de reviewtekst op basis van +/- aantal karakters', 'example');
?></label> 
			<select id="<?php
        echo $this->get_field_id('fc_truncate');
?>" name="<?php
        echo $this->get_field_name('fc_truncate');
?>" class="widefat" style="width:100%;">
<option <?php
        if ('100' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>100</option>
				<option <?php
        if ('150' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>150</option>
				<option <?php
        if ('200' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>200</option>
				<option <?php
        if ('250' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>250</option>
				<option <?php
        if ('300' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>300</option>
				<option <?php
        if ('350' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>350</option>
				<option <?php
        if ('400' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>400</option>
				<option <?php
        if ('450' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>450</option>
				<option <?php
        if ('500' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>500</option>
				<option <?php
        if ('550' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>550</option>
				<option <?php
        if ('600' == $instance['fc_truncate'])
            echo 'selected="selected"';
?>>600</option>
			</select>
		</p>
		
		<!-- business name -->
		<p>
			<label for="<?php
        echo $this->get_field_id('bnamereviews');
?>"><?php
        _e('Bedrijfsnaam (niet zichtbaar, nodig voor Rich Snippets):', 'example');
?></label>
			<input id="<?php
        echo $this->get_field_id('bnamereviews');
?>" name="<?php
        echo $this->get_field_name('bnamereviews');
?>" value="<?php
        echo $instance['bnamereviews'];
?>" style="width:100%;" />
		</p>
		
		
		<!-- input link all reviews -->
		<p>
			<label for="<?php
        echo $this->get_field_id('link_all');
?>"><?php
        _e('Link naar alle reviews:', 'example');
?></label>
			<input id="<?php
        echo $this->get_field_id('link_all');
?>" name="<?php
        echo $this->get_field_name('link_all');
?>" value="<?php
        echo $instance['link_all'];
?>" style="width:100%;" />
		</p>
		
<?php
    }
    
    // updating widget replacing old instances with new
    
    public function update($new_instance, $old_instance) {
        $instance                    = array();
        $instance['title']           = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['fc_review_count'] = $new_instance['fc_review_count'];
        $instance['fc_sorting']      = $new_instance['fc_sorting'];
        $instance['fc_truncate']      = $new_instance['fc_truncate'];
        $instance['link_all']        = strip_tags($new_instance['link_all']);
        $instance['bnamereviews']    = strip_tags($new_instance['bnamereviews']);
        return $instance;
    }
} // class fcr_widget ends here

// register and load the widget

function fcr_load_widget() {
    register_widget('fcr_widget');
}

add_action('widgets_init', 'fcr_load_widget');

// instance

$WPfeedback_xml = WPfeedback_xml::getInstance();