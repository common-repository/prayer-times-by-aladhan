<?php
require_once(realpath(__DIR__) . '/../vendor/autoload.php');
require_once(realpath(__DIR__) . '/../classes/Render.php');

use AlAdhanApi\TimesByAddress;
?>
<div class="wrap">
    <h2>AlAdhan Plugin Settings</h2>
    <?php 
    $address = esc_attr( get_option('prayer_times_address') ) == '' ? 'Makkah, Saudi Arabia' : esc_attr( get_option('prayer_times_address') );
    $displayFormat = esc_attr( get_option('prayer_times_display_format') ) == '' ? 'horizontal' : esc_attr( get_option('prayer_times_display_format') ); 
    $method =  esc_attr( get_option('prayer_times_method') ) == '' ? '4' : esc_attr( get_option('prayer_times_method') );
    $school = esc_attr( get_option('prayer_times_school') ) == '' ? '0' : esc_attr( get_option('prayer_times_school') );
    $latitudeMethod = esc_attr( get_option('prayer_times_latitude_adjustment_method') ) == '' ? '3' : esc_attr( get_option('prayer_times_latitude_adjustment_method') );
    $displayHeading = esc_attr( get_option('prayer_times_display_heading') ) == '' ? 'Prayer Times Today' :  esc_attr( get_option('prayer_times_display_heading') );
    $displayHeadingBgColour = esc_attr( get_option('prayer_times_display_heading_bg') );
    $displayHeadingColour = esc_attr( get_option('prayer_times_display_heading_text') );
    $prayerTimings = (new TimesByAddress($address, null, $method, $latitudeMethod, $school))->get();
    ?>
    
        <form method="post" action="options.php">
        <?php settings_fields( 'aladhan-settings-group' ); ?>
        <?php do_settings_sections( 'aladhan-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Masjid Address</th>
                <td><input type="text" name="prayer_times_address" value="<?php echo $address; ?>" style="width: 100%;" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Calculation Method</th>
                <td>
                    <select name="prayer_times_method">
                        <option value="4" <?php echo $method == '4' ? 'selected' : ''; ?>>Umm al-Qura, Makkah</option>
                        <option value="0" <?php echo $method == '0' ? 'selected' : ''; ?>>Shia Ithna-Ashari</option>
                        <option value="1" <?php echo $method == '1' ? 'selected' : ''; ?>>University of Islamic Sciences, Karachi</option>
                        <option value="2" <?php echo $method == '2' ? 'selected' : ''; ?>>Islamic Society of North America (ISNA)</option>
                        <option value="3" <?php echo $method == '3' ? 'selected' : ''; ?>>Muslim World League (MWL)</option>
                        <option value="5" <?php echo $method == '5' ? 'selected' : ''; ?>>Egyptian General Authority of Survey</option>
                        <option value="7" <?php echo $method == '7' ? 'selected' : ''; ?>>Institute of Geophysics, University of Tehran</option>
                    </select>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">School</th>
                <td>
                    <select name="prayer_times_school">
                    <option value="0" <?php echo $school == '0' ? 'selected' : ''; ?>>Shafi</option>
                    <option value="1" <?php echo $school == '1' ? 'selected' : ''; ?>>Hanafi</option>
                    </select>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row">Latitude Adjustment Method</th>
                <td>
                    <select name="prayer_times_latitude_adjustment_method">
                        <option value="1" <?php echo $latitudeMethod == '1' ? 'selected' : ''; ?>>Middle of the Night Method</option>
                        <option value="2" <?php echo $latitudeMethod == '2' ? 'selected' : ''; ?>>One Seventh Rule</option>
                        <option value="3" <?php echo $latitudeMethod == '3' ? 'selected' : ''; ?>>Angle Based Method </option>
                    </select>
                </td>
            </tr>
            
            
            <tr valign="top">
                <th scope="row">Display Format</th>
                <td>
                    <input type="radio" id="prayer_times_display_format_horizontal" name="prayer_times_display_format" value="horizontal" <?php echo $displayFormat == 'horizontal' ? 'checked' : '' ; ?> /> <label for="prayer_times_display_format_horizontal">Horizontal</label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="radio" id="prayer_times_display_format_vertical" name="prayer_times_display_format" value="vertical" <?php echo $displayFormat == 'vertical' ? 'checked' : '' ; ?> /> <label for="prayer_times_display_format_vertical">Vertical</label>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row">Heading</th>
                <td><input type="text" name="prayer_times_display_heading" value="<?php echo $displayHeading; ?>" style="width: 100%;" /></td>
            </tr>
            
            <tr>
                <th scope="row">Heading Background Colour</th>
                <td>
                    <input type="text" value="<?php echo $displayHeadingBgColour; ?>" class="wp-color-picker-field" name="prayer_times_display_heading_bg" data-default-color="#cccccc" />
                </td>
            </tr>
            
            <tr>
                <th scope="row">Heading Text Colour</th>
                <td>
                    <input type="text" value="<?php echo $displayHeadingColour; ?>" class="wp-color-picker-field" name="prayer_times_display_heading_text" data-default-color="#000000" />
                </td>
            </tr>
            
            <tr>
                <th scope="row">Salat Name Background Colour</th>
                <td>
                    <input type="text" value="<?php echo esc_attr( get_option('prayer_times_display_header_bg') ); ?>" class="wp-color-picker-field" name="prayer_times_display_header_bg" data-default-color="#cccccc" />
                </td>
            </tr>
            <tr>
                <th scope="row">Salat Name Text Colour</th>
                <td>
                    <input type="text" value="<?php echo esc_attr( get_option('prayer_times_display_header_text') ); ?>" class="wp-color-picker-field" name="prayer_times_display_header_text" data-default-color="#000000" />
                </td>
            </tr>
            <tr>
                <th scope="row">Salat Time Row Background Colour</th>
                <td>
                    <input type="text" value="<?php echo esc_attr( get_option('prayer_times_display_row_bg') ); ?>" class="wp-color-picker-field" name="prayer_times_display_row_bg" data-default-color="#ffffff" />
                </td>
            </tr>
            <tr>
                <th scope="row">Salat Time Row Text Colour</th>
                <td>
                    <input type="text" value="<?php echo esc_attr( get_option('prayer_times_display_row_text') ); ?>" class="wp-color-picker-field" name="prayer_times_display_row_text" data-default-color="#000000" />
                </td>
            </tr>
        </table>
        <h2>
        Over-Write Computed Timings
        </h2>
            <p>
                Please use the military time format. Example: 13:46 for 1:46 pm.
            </p>
        <table class="form-table">
            <tr>
                <th>Fajr</th>
                <td><input type="text" name="prayer_times_override_fajr" value="<?php echo esc_attr( get_option('prayer_times_override_fajr') ); ?>" /></td>
            </tr>
            <tr>
                <th>Zhuhr</th>
                <td><input type="text" name="prayer_times_override_dhuhr" value="<?php echo esc_attr( get_option('prayer_times_override_dhuhr') ); ?>" /></td>
            </tr>
            <tr>
                <th>Asr</th>
                <td><input type="text" name="prayer_times_override_asr" value="<?php echo esc_attr( get_option('prayer_times_override_asr') ); ?>" /></td>
            </tr>
            <tr>
                <th>Maghrib</th>
                <td><input type="text" name="prayer_times_override_maghrib" value="<?php echo esc_attr( get_option('prayer_times_override_maghrib') ); ?>" /></td>
            </tr>
            <tr>
                <th>Isha</th>
                <td><input type="text" name="prayer_times_override_isha" value="<?php echo esc_attr( get_option('prayer_times_override_isha') ); ?>" /></td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
    
    <h2>Preview</h2>
    <?= Render::Timings($displayFormat,
                        $prayerTimings,
                        $displayHeadingBgColour,
                        $displayHeadingColour,
                        $displayHeading,
                        esc_attr( get_option('prayer_times_display_header_bg') ),
                        esc_attr( get_option('prayer_times_display_row_bg') ),
                        esc_attr( get_option('prayer_times_display_header_text') ),
                        esc_attr( get_option('prayer_times_display_row_text') ),
                        esc_attr( get_option('prayer_times_override_fajr') ),
                        esc_attr( get_option('prayer_times_override_dhuhr') ),
                        esc_attr( get_option('prayer_times_override_asr') ),
                        esc_attr( get_option('prayer_times_override_maghrib') ),
                        esc_attr( get_option('prayer_times_override_isha') )
                       ); ?>
    
    
    
   
</div>

<script>
jQuery(document).ready(function($){
    $('.wp-color-picker-field').wpColorPicker();
});
</script>
