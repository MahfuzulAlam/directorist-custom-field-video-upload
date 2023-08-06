<?php

/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if (!defined('ABSPATH')) exit;

$done = str_replace('|||', '', $value);
$name_arr = explode('/', $done);
$filename = end($name_arr);

$ext = $done ? pathinfo($done, PATHINFO_EXTENSION) : 'mp4';
$type = 'video/' . $ext;
?>

<div class="directorist-single-info directorist-single-info-file">

    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon($icon); ?></span>
        <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
    </div>

    <video width="100%" height="300" controls>
        <source src="<?php echo $done; ?>" type="<?php echo $type; ?>">
    </video>

</div>