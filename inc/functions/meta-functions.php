<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    function addMetaTitle($serpTitle) {
        add_filter( 'wp_title', function($title) use ($serpTitle) {
            return $serpTitle;
        });

        add_action( 'wp_head', function() use ($serpTitle) {
            echo '<meta property="og:title" content="' . $serpTitle . '">';
            echo '<meta name="twitter:title" content="' . $serpTitle . '">';
        }, 0);
    }

    function addMetaDesc($serpDesc) {
        add_action( 'wp_head', function() use ($serpDesc) {
            echo '<meta name="description" content="' . $serpDesc . '">';
            echo '<meta property="og:description" content="' . $serpDesc . '">';
        }, 0);
    }

    function addMetaImg($serpImg) {
        if (!$serpImg) {
            $blog_id = get_option('page_for_posts');
            $page_thumbnail = get_the_post_thumbnail_url($blog_id, 'og_size');
            if (isset($page_thumbnail) && !empty($page_thumbnail)) $serpImg = $page_thumbnail;
        }

        if ($serpImg) {
            add_action( 'wp_head', function() use ($serpImg) {
                echo '<meta name="twitter:image" content="' . $serpImg . '">';
                echo '<meta property="og:image" content="' . $serpImg . '">';
                echo '<meta property="og:image:width" content="1200">';
                echo '<meta property="og:image:height" content="630">';
            }, 0);
        }
    }

    function addOgSiteName() {
        $site_name = get_bloginfo('name');

        add_action( 'wp_head', function() use ($site_name) {
            echo '<meta property="og:site_name" content="' . $site_name . '">';
        }, 0);
    }

    function addOgUrl($siteUrl) {
        add_action( 'wp_head', function() use ($siteUrl) {
            echo '<meta property="og:url" content="' . $siteUrl . '">';
        }, 0);
    }

    function addOgType() {
        add_action( 'wp_head', function() {
            echo '<meta property="og:type" content="website">';
        }, 0);
    }

    function addCanonical($siteUrl) {
        add_action( 'wp_head', function() use ($siteUrl){
            echo '<link href="' . $siteUrl . '" rel="canonical">';
        }, 0);
    }

    function buildMetaData($metaTitle, $metaDescription, $metaImage = false) {
        $current_url = getCurrentUrl();

        addMetaTitle($metaTitle);
        addMetaDesc($metaDescription);
        addMetaImg($metaImage);
        addOgSiteName();
        addOgType();
        addOgUrl($current_url);
        addCanonical($current_url);
    }

    function buildRobots($robots) {
        add_action( 'wp_head', function() use ($robots) {
            echo '<meta name="robots" content="' . $robots . '">';
        }, 0);
    }
