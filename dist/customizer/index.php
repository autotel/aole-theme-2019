<?php
function themeslug_customize_register($wp_customize)
{
  include locate_template("customizer/deco-noise.php");
  include locate_template("customizer/header-settings.php");
}
add_action('customize_register', 'themeslug_customize_register');

?>
