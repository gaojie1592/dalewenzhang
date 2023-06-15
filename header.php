<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo("charset") ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php global $options; ?>
    <?php echo isset($options['yetoutianjia']) ? $options['yetoutianjia'] : ''; ?>
    <?php wp_head() ?>
</head>