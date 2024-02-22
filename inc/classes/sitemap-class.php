<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Sitemap extends SpinoffSitemap {
    protected $http_protocol = 'HTTP/1.1';
    protected $staticSrcPrefix = 'https://www.erento.com'; // HARDCODED

    public $sitemapQueryVar;
    public $priorityFrequency = [
        'pdp' => ['weekly', '1.0'],
        'serp' => ['daily', '1.0'],
        'post' => ['monthly', '0.5'],
        'page' => ['monthly', '0.5'],
    ];

    function __construct(string $sitemapQueryVar) {
        $this->sitemapQueryVar = $sitemapQueryVar;

        if ( ! empty( $_SERVER['SERVER_PROTOCOL'] ) ) {
            $this->http_protocol = sanitize_text_field( wp_unslash( $_SERVER['SERVER_PROTOCOL'] ) );
        }

        $this->init();
    }

    public function init() {
        switch ($this->sitemapQueryVar) {
            default:
            case 'root':
                $sitemap = $this->buildRootSitemap();
                break;

            case 'pdp':
                $sitemap = $this->buildPdpsSitemap();
                break;

            case 'serp':
                $sitemap = $this->buildSerpsSitemap();
                break;

            case 'post':
                $sitemap = $this->buildPostsSitemap();
                break;

            case 'page':
                $sitemap = $this->buildPagesSitemap();
                break;
        }

        $this->send_headers();
        $this->outputSitemap($sitemap);
    }

    private function outputSitemap($sitemap) {
        $theme_dir_url = get_template_directory_uri();
        $stylesheet_url = preg_replace('/(^http[s]?:)/', '', $theme_dir_url . '/inc/main-sitemap.xsl');

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<?xml-stylesheet type="text/xsl" href="' . esc_url( $stylesheet_url ) . '"?>';
        echo $sitemap;
    }

    private function send_headers() {
        if ( headers_sent() ) {
            return;
        }

        $headers = [
            $this->http_protocol . ' 200 OK' => 200,
            'X-Robots-Tag: noindex, follow'  => '', // Prevent the search engines from indexing the XML Sitemap.
            'Content-Type: text/xml; charset=UTF-8' => '',
        ];

        foreach ( $headers as $header => $status ) {
            if ( is_numeric( $status ) ) {
                header( $header, true, $status );
                continue;
            }
            header( $header, true );
        }
    }

    private function getSitemapUrl() {
        return getHomeUrl();
    }

    public function getUrlsetTag($openingTag = false) {
        if ($openingTag) return '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
        else return '</urlset>';
    }

    public function generateUrlElement($loc = false, $lastmod = false, $changefreq = false, $priority = false, $images = false) {
        $output = '<url>';
            if ($loc) $output .= '<loc>' . $loc . '</loc>';
            if ($lastmod) $output .= '<lastmod>' . $lastmod . '</lastmod>';
            if ($changefreq) $output .= '<changefreq>' . $changefreq . '</changefreq>';
            if ($priority) $output .= '<priority>' . $priority . '</priority>';
            if ($images) $output .= $images;
        $output .= '</url>';

        return $output;
    }

    public function generateImageElements($imagesArray) {
        $output = false;
        if (empty($imagesArray)) {
            return $output;
        }

        foreach ($imagesArray as $key => $image) {
            $output .= '<image:image>';
            $output .= '<image:loc>' . $this->staticSrcPrefix . $image->src . '</image:loc>';
            $output .= '</image:image>';
        }

        return $output;
    }

    private function buildRootSitemap() {
        $sitemap_url = $this->getSitemapUrl();

        $output = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $output .= '<sitemap><loc>' . $sitemap_url . 'product-sitemap.xml' . '</loc></sitemap>';
            $output .= '<sitemap><loc>' . $sitemap_url . 'serp-sitemap.xml' . '</loc></sitemap>';
            $output .= '<sitemap><loc>' . $sitemap_url . 'post-sitemap.xml' . '</loc></sitemap>';
            $output .= '<sitemap><loc>' . $sitemap_url . 'page-sitemap.xml' . '</loc></sitemap>';
        $output .= '</sitemapindex>';

        return $output;
    }

    private function buildPostsSitemap() {
        $posts = get_posts(['numberposts' => -1, 'post_status' => 'publish']); // Show all posts, show only published posts
        $output = $this->getUrlsetTag(true);
        $priorityFrequency = $this->priorityFrequency['post'];

        foreach ($posts as $key => $post) {
            $post_url = get_permalink($post->ID);
            $post_modified_gmt = get_post_modified_time('Y-m-d', false, $post->ID, false);
            $output .= $this->generateUrlElement($post_url, $post_modified_gmt, $priorityFrequency[0], $priorityFrequency[1]);
        }

        $output .= $this->getUrlsetTag();

        return $output;
    }

    private function buildPagesSitemap() {
        $pages = get_pages();
        $output = $this->getUrlsetTag(true);
        $priorityFrequency = $this->priorityFrequency['page'];

        foreach ($pages as $key => $page) {
            $page_url = get_permalink($page->ID);
            $page_modified_gmt = get_post_modified_time('Y-m-d', false, $page->ID, false);
            $output .= $this->generateUrlElement($page_url, $page_modified_gmt, $priorityFrequency[0], $priorityFrequency[1]);
        }

        $output .= $this->getUrlsetTag();

        return $output;
    }
}
