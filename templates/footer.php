<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
    
    $svgs = get_svgs();
    $class = '';
    if (!empty($args['class'])) $class = $args['class'];

    $logo = false;
    if (SPINOFFID === 'sportauto') { $logo = $svgs['logo-sportauto']; }
    elseif (SPINOFFID === 'limo') { $logo = $svgs['logo-limo']; }
    elseif (SPINOFFID === 'zelte') { $logo = $svgs['logo-zelte']; }
    elseif (SPINOFFID === 'oldtimer') { $logo = $svgs['logo-oldtimer']; }
    ?>
            </main>
            <footer id="footer" class="footer <?=$class?>" role="contentinfo">
                <div class="container">
                    <div class="footer-wrapper">
                        <div class="footer-top" itemscope itemtype="http://schema.org/LocalBusiness">
                            <?php if ($logo) { ?>
                                <div class="logo"><?=$logo?></div>
                            <?php } ?>
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
        <script>
            function isObject(checkObject) {
                if (typeof checkObject === 'object' && !Array.isArray(checkObject) && checkObject !== null) return true;
                return false;
            }
            
            function onMapInit() {
                const SearchboxFormLocationInputs = document.querySelectorAll('form.search-box input[name="location"]');
                if (SearchboxFormLocationInputs.length > 0) {
                    SearchboxFormLocationInputs.forEach(function (inputElement, index) {
                        var autocomplete = new google.maps.places.Autocomplete(inputElement);
            
                        autocomplete.setFields(['name']);
            
                        inputElement.parentElement.querySelector('.clear-icon').onclick = function() {
                            autocomplete.set('place',null);
                            inputElement.value = '';
                            inputElement.parentElement.classList.remove('show-clear-btn');
                            inputElement.focus();
                        };
            
                        inputElement.onkeyup = function() {
                            if (inputElement.value != '') inputElement.parentElement.classList.add('show-clear-btn');
                            else inputElement.parentElement.classList.remove('show-clear-btn');
                        };
                    });
                }
            }
        </script>
		<?php wp_footer(); ?>
	</body>
</html>
