<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <meta charset="<?php bloginfo('charset'); ?>" />
    <?php if (is_singular() && pings_open(get_queried_object())) : ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>
    <?php wp_head(); ?>
    <title><?= wp_title() ?></title>

</head>

<body <?php echo body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php echo get_template_part("template-parts/header-menu"); ?>