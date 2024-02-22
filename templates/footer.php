<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
    
    $svgs = get_svgs();
    $class = '';
    if (!empty($args['class'])) $class = $args['class'];
    if (SPINOFFID ==='sportauto') $class .= ' dark';
?>
            </main>
            <footer id="footer" class="footer <?=$class?>" role="contentinfo">
                <div class="container">
                    <div class="footer-wrapper">
                        <div class="footer-top" itemscope itemtype="http://schema.org/LocalBusiness">
                            <div class="logo"><?=$svgs['logo']?></div>
                            <div class="company-block">
                                <b itemprop="name"><?=get_option('custom_site_company_name')?></b><br/>

                                <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                    <span itemprop="streetAddress"><?=get_option('custom_site_company_address')?></span>,
                                    <span itemprop="postalCode"><?=get_option('custom_site_company_zip')?></span> <span itemprop="addressLocality"><?=get_option('custom_site_company_city')?></span>, <span itemprop="addressCountry"><?=get_option('custom_site_company_country')?></span>
                                </div>
                            </div>
                            <div class="contact">
                                <?php
                                    $site_phone = get_option('custom_site_phone');
                                    if ($site_phone != '') { ?>
                                        <a href="tel:<?=get_option('custom_site_phone')?>" itemprop="telephone">
                                            t:&nbsp;<span><?=get_option('custom_site_phone')?></span>
                                        </a>
                                        <br>
                                <?php } ?>
                                <a href="mailto:<?=get_option('custom_site_email')?>" itemprop="email">
                                    m:&nbsp;<span><?=get_option('custom_site_email')?></span>
                                </a>
                                <?php if (SPINOFFID === 'sportauto'): ?>
                                    <div class="soc-icons">
                                        <a href="https://www.instagram.com/mietedeinsportauto/" class="icon" target="_blank"><?=$svgs['ig']?></a>
                                        <a href="https://www.facebook.com/mietedeinsportauto/" class="icon" target="_blank"><?=$svgs['fb']?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="footer-bottom">
                            <?php wp_nav_menu( array('theme_location' => 'footer-legal-menu', 'menu_class' => 'footer-legal-menu' )); ?>
                            <div class="copyright"><?php echo date("Y"); echo _t('COPYRIGHT')?></div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
		<?php wp_footer(); ?>
        <?php get_shared_template_part('components/cookie-consent'); ?>
	</body>
</html>
