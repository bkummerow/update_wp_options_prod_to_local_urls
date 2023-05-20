<?php

// From & To URL variables & WordPress database credentials
$from = null;
$to = null;
$db_name = null;
$db_user = null;
$db_password = null;
$db_host = null;
$db_port = null;

// Check if the required arguments are provided
if ($argc > 1) {
    // Loop through the arguments to find the values
    for ($i = 1; $i < $argc; $i++) {
        if (strpos($argv[$i], '--from=') === 0) {
            $from = substr($argv[$i], 7);
        } elseif (strpos($argv[$i], '--to=') === 0) {
            $to = substr($argv[$i], 5);
        } elseif (strpos($argv[$i], '--db_name=') === 0) {
            $db_name = substr($argv[$i], 10);
        } elseif (strpos($argv[$i], '--db_user=') === 0) {
            $db_user = substr($argv[$i], 10);
        } elseif (strpos($argv[$i], '--db_password=') === 0) {
            $db_password = substr($argv[$i], 14);
        } elseif (strpos($argv[$i], '--db_host=') === 0) {
            $db_host = substr($argv[$i], 10);
        } elseif (strpos($argv[$i], '--db_port=') === 0) {
            $db_port = substr($argv[$i], 10);
        }
    }
    // Use the values as needed
    if ($from !== null && $to !== null && $db_name !== null && $db_user !== null && $db_password !== null && $db_host !== null && $db_port !== null) {
        echo "Update script working...";
        // Connect to the database
        $db_host_port = $db_host . ":" . $db_port;
        $mysqli = new mysqli($db_host_port, $db_user, $db_password, $db_name);

        // Check connection
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit;
        }
        // Update query
        $query = "UPDATE wp_options SET option_value = REPLACE(option_value, '" . $from . "', '" . $to . "')";
        // Execute the update query
        if ($mysqli->query($query) === TRUE && $from !== null && $to !== null) {
            echo "Update completed successfully.";
        } else {
            echo "Error updating database: " . $mysqli->error;
        }

        if ($from === 'asureti.com') {
            // Not sure why I have to do this, but import is not updating this field value
            // may not be accurate if changed on prod, but at least it looks better locally
            $theme_mod_query = "UPDATE wp_options SET option_value = 'a:17:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:1916;s:21:\"p3_chipmunk_site_logo\";s:67:\"https://asureti.com/wp-content/uploads/2020/09/asureti_logo_h-1.svg\";s:19:\"p3_chipmunk_twitter\";s:30:\"https://twitter.com/Asureti_dp\";s:20:\"p3_chipmunk_facebook\";s:0:\"\";s:20:\"p3_chipmunk_linkedin\";s:41:\"https://www.linkedin.com/company/asureti/\";s:19:\"p3_chipmunk_youtube\";s:0:\"\";s:34:\"p3_chipmunk_footer_callout_1_title\";s:27:\"Get the Asureti newsletter.\";s:36:\"p3_chipmunk_footer_callout_1_content\";s:91:\"We’ll send you the best from our blog, industry news, and more in a brief monthly digest.\";s:34:\"p3_chipmunk_footer_callout_2_title\";s:32:\"Where assurance meets integrity.\";s:32:\"p3_chipmunk_contact_address_long\";s:44:\"1828 Walnut, Suite 301\nKansas City, MO 64108\";s:25:\"p3_chipmunk_contact_email\";s:16:\"info@asureti.com\";s:25:\"p3_chipmunk_contact_phone\";s:14:\"1 888-844-3570\";s:22:\"p3_chipmunk_hero_image\";s:88:\"https://asureti.com/wp-content/uploads/2020/09/leonard-beck-ZmwxGJxLoSk-unsplash-1-1.jpg\";s:24:\"p3_chipmunk_hero_heading\";s:4:\"Blog\";s:24:\"p3_chipmunk_hero_subhead\";s:118:\"Learn. Share. Solve. Bringing together thoughts and perspectives in the security, risk, compliance, and privacy space.\";}' 
            WHERE option_name = 'theme_mods_tardigrade';";

            // Execute the update query
            if ($mysqli->query($theme_mod_query) === TRUE) {
                echo "Update completed successfully.";
            } else {
                echo "Error updating database: " . $mysqli->error;
            }
        }

        // Close the database connection
        $mysqli->close();
    } else {
        echo "Invalid arguments. Please provide --from and --to values.";
    }
} else {
    echo "Insufficient arguments. Please provide --from and --to values.";
}

?>
