<?php
/**
 * @package Bolt_News
 * @version 1.0
 */
/*
Plugin Name: Bolt News
Plugin URI: http://wordpress.org/plugins/bolt-news/
Description: Simple short News sidebar.
Author: RianGraphics
Version: 1.0
Author URI: http://www.riangraphics.com/bolt-news/
*/

add_action( 'admin_menu', 'bolt_news_menu' );

function bolt_news_menu() {
	add_menu_page( 'Bolt News', 'Bolt News', 'manage_options', 'bolt-news-page.php', 'bolt_news_page', plugin_dir_url( __FILE__ ) . 'img/icon.png', 7  );
    
add_action( 'admin_init', 'register_bolt_news_settings' );

}

function register_bolt_news_settings() {
    
        
    $settingsArray = array (
        'bolt_news_enable',
        'bolt_news_label',
        'bolt_news_data',
        'bolt_news_titolo', 
        'bolt_news_testo'
    );

    foreach ($settingsArray as $setting) {
        register_setting( 'bolt-news-settings-group', $setting);
    }
	//register our settings
    //register_setting( 'bolt-news-settings-group', 'bolt_news_enable' );
    //register_setting( 'bolt-news-settings-group', 'bolt_news_titolo' );
    //register_setting( 'bolt-news-settings-group', 'bolt_news_testo' );
}

function bolt_news_page() {
?>
<div class="wrap">
    
		<h2>Bolt News</h2>
<script>
    jQuery( document ).ready(function() {
    jQuery('.multi-field-wrapper').each(function() {
    var wrapper = jQuery('.multi-fields', this);
    var nums = 0;
    jQuery(".add-field", jQuery(this)).click(function(e) {
        nums++;
        jQuery('.multi-field:first-child', wrapper).clone(true).appendTo(wrapper);
    });
    jQuery('.multi-field .remove-field', wrapper).click(function() {
        if (jQuery('.multi-field', wrapper).length > 1)
            jQuery(this).parent('.multi-field').remove();
    });
});
        });
</script>
<form method="post" action="options.php">
    <?php settings_fields( 'bolt-news-settings-group' ); ?>
    <?php do_settings_sections( 'bolt-news-settings-group' ); ?>
    <table class="form-table multi-field-wrapper">
        <thead>
        <tr valign="top">
        <th scope="row">Enable/Disable</th>
        <td><input name="bolt_news_enable" type="checkbox" value="1" <?php checked( '1', get_option( 'bolt_news_enable' ) ); ?> />
            </td>
        </tr>
        <tr valign="top">
        <th scope="row">Label</th>
        <td><input name="bolt_news_label" type="text" value="<?php echo esc_attr( get_option( 'bolt_news_label' ) ); ?>" />
            </td>
        </tr>
        <tr valign="top">
        <th scope="row">Aggiungi Nuova News</th>
        <td><button type="button" class="add-field">+</button></td>
        </tr>
        </thead>
        <tbody class="multi-fields">
    <?php 
    if( get_option( 'bolt_news_data' ) ) {
    $myopt0 = get_option( 'bolt_news_data' ); 
    $myopt = get_option( 'bolt_news_titolo' ); 
    $myopt2 = get_option( 'bolt_news_testo' );
    } else {
    $myopt0 = array('11-11-2011'); 
    $myopt = array('Your Title'); 
    $myopt2 = array('Your Text');
    }
    $iterator = new MultipleIterator();
    $iterator->attachIterator(new ArrayIterator($myopt0));
    $iterator->attachIterator(new ArrayIterator($myopt));
    $iterator->attachIterator(new ArrayIterator($myopt2));
    foreach ($iterator as $current) :
    $data = $current[0];
    $title = $current[1];
    $text = $current[2];
    ?>
        <tr valign="top" class="multi-field">
        <th scope="row">Data</th>
        <td><input name="bolt_news_data[]" type="date" value="<?php echo esc_attr( $data ); ?>" /></td>
        <th scope="row">Titolo</th>
        <td><input name="bolt_news_titolo[]" type="text" value="<?php echo esc_attr( $title ); ?>" /></td>
        <th scope="row">Testo</th>
        <td>
        <textarea name="bolt_news_testo[]" type="text"><?php echo esc_attr( $text ); ?></textarea>
        </td>
        <td class="remove-field"><button type="button">x</button></td>
        </tr>
            <?php endforeach; ?>
        </tbody>
        
    </table>
    <?php submit_button(); ?>

</form>
</div>
<?php } 


function bolt_news() {
    $mytrue = get_option( 'bolt_news_enable' );
    $label = get_option( 'bolt_news_label' );
	if(!is_admin() && $mytrue == 1 ) {
        
    echo "
    <div class='mypin'>
    <i id='mright' class='fa fa-angle-down' aria-hidden='true'></i>
    <i id='mleft' class='fa fa-angle-up display' aria-hidden='true'></i>
    <i class='fa fa-newspaper-o' aria-hidden='true'></i>  Offerte e Promozioni
    </div>
    <div id='rg-bolt'>
    <div class='rg-label'>$label</div>";
    $myopt0 = get_option( 'bolt_news_data' ); 
    $myopt = get_option( 'bolt_news_titolo' ); 
    $myopt2 = get_option( 'bolt_news_testo' );
                           
    $iterator = new MultipleIterator();
    $iterator->attachIterator(new ArrayIterator($myopt0));
    $iterator->attachIterator(new ArrayIterator($myopt));
    $iterator->attachIterator(new ArrayIterator($myopt2));
    foreach ($iterator as $current) {
    $data = $current[0];
    $title = $current[1];
    $text = $current[2];
        
	echo "
    <div class='bolt-row'>
    <div class='bolt-title'>$title</div>
    <div class='bolt-data'><i class='fa fa-calendar' aria-hidden='true'></i> $data</div>
    <div class='bolt-text'>$text</div>
    </div>
    ";
    }
        echo "</div>";
  }
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'wp_footer', 'bolt_news' );

// We need some CSS to position the paragraph
function bolt_news_css() {
$mytrue = get_option( 'bolt_news_enable' );
if( $mytrue == 1 ) {
	echo '
	<style type="text/css">
    .display {display:none;}
    .mypin {
    font-size: 16px;
    width: auto;
    border: 2px solid #fff;
    color: #fff;
    position: fixed;
    top: 20%;
    background: #009e3c;
    padding: 5px;
     z-index: 999999;
    }    
	#rg-bolt {
    display:none;
    width: 300px;
    padding: 15px;
    margin: 0px;
    height: 60%;
    overflow-y: auto;
    color: #fff;
    position: fixed;
    border: 2px solid #fff;
    z-index: 99999;
    left: -300px;
    background-color: #009e3c;
    top: 20%;
	}
    .rg-bolt-toggle {
    display:block!important;
    left: 40px!important;
	}
    .rg-label {
    font-weight: 700;
    font-size: 16px;
    }
    .bolt-row {
    border-top: 1px dashed #ddd;
    padding-bottom: 5px;
    padding-top: 8px;
    }
    .bolt-data {
    font-size: 11px;
    font-weight: 700;
    padding: 3px;
     margin-left: 10px;
    }
    .bolt-title {
    font-size: 18px;
    font-weight: 700;
    margin-left: 8px;
    }
    .bolt-text {
    font-size: 16px;
    margin-left: 12px;
}

@media screen and (max-width:767px) {
#rg-bolt {
    position: fixed;
}
}
	</style>
	';
}
}

add_action( 'wp_head', 'bolt_news_css' );

function bolt_news_js() {
$mytrue = get_option( 'bolt_news_enable' );
if( $mytrue == 1 ) {
	echo '
	<script>
    jQuery( ".mypin" ).click(function() {
  jQuery( "#rg-bolt" ).toggleClass( "rg-bolt-toggle" );
  jQuery( "#mright" ).toggleClass( "display" );
  jQuery( "#mleft" ).toggleClass( "display" );
});
	</script>
	';
}
}

add_action( 'wp_footer', 'bolt_news_js' );

?>
