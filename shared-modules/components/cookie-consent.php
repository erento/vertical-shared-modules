<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
?>

<cookie-consent-component>
    <!-- Overlay -->
    <div id="cookie-modal-overlay-B" class="cookie-modal-overlay"></div>

    <!-- Intro popup -->
    <div id="cookie-consent-intro-container" class="cc-fixed-container">
        <div id="cookie-consent-intro" class="cookie-consent-intro-B2 cookie-consent-styles">
            <div class="left-block">
                <div class="title">Wir verwenden Cookies</div>
                <div class="paragraph">
                    Wir verwenden Cookies, um das Nutzererlebnis zu optimieren und Inhalte zu personalisieren. Per Klick auf <strong>“Cookies Akzeptieren”</strong> stimmen Sie unseren <strong><a href="<?=get_home_url()?>/datenschutz" id="tcc_link_policy">Cookie-Richtlinien</a></strong> zu. Alternativ können Sie Ihre Einstellungen auch anpassen, indem Sie auf die Schaltfläche <strong>“Anpassen”</strong> klicken.
                </div>
                <div class="btn-customize" id="tcc_btn_customize">Anpassen</div>
            </div>
            <div class="cookie-button-solid btn-accept" id="tcc_btn_accept_all_first">Cookies Akzeptieren</div>
        </div>
    </div>

    <!-- Customize popup -->
    <div id="cookie-consent-customize-container" class="cc-fixed-container">
        <div class="cookie-consent-customize cookie-consent-styles">
            <div class="consent-header">
                <div class="title">Zustimmungseinstellungen verwalten</div>
                <div class="close-cookie-customize-popup-button" id="close-cookie-consent-popup"></div>
            </div>
            <div class="customize-content">
                <div class="consent-block">
                    <div class="consent-feature">
                        <div class="title">Erforderliche Cookies</div>
                        <div class="consent-control __active">Immer aktiv</div>
                    </div>
                    <div class="consent-paragraph">Diese Cookies sind essenziell für die Funktion unserer Website und können nicht deaktiviert werden. Sie werden eingesetzt, um z.B. den Login oder das Ausfüllen von Formularen zu ermöglichen.</div>
                </div>

                <div class="consent-block">
                    <div class="consent-feature">
                        <div class="title">Performance Cookies</div>
                        <div class="consent-control"><input type="checkbox" name="checkbox" class="cm-toggle" id="tcc_toggle_performance"></div>
                    </div>
                    <div class="consent-paragraph">Mit diesen Cookies zählen wir Besuche und Traffic-Quellen, um die Leistung unserer Website zu messen und zu verbessern. Alle Daten, die diese Cookies sammeln, sind anonym.</div>
                </div>

                <div class="consent-block">
                    <div class="consent-feature">
                        <div class="title">Targeting-Cookies</div>
                        <div class="consent-control"><input type="checkbox" name="checkbox" class="cm-toggle" id="tcc_toggle_targeting"></div>
                    </div>
                    <div class="consent-paragraph">Diese Cookies werden von Werbepartnern bereitgestellt. Sie geben Aufschluss über Nutzerverhalten und ermöglichen die Personalisierung von Werbung.</div>
                </div>

                <div class="cookie-button-solid btn-accept-preferences" id="tcc_btn_accept_preferences">Meine Auswahl bestätigen</div>
            </div>
            <div class="consent-footer">
                <div class="cookie-button-solid btn-accept" id="tcc_btn_accept_all_second">Cookies Akzeptieren</div>
            </div>
        </div>
    </div>

    <script>
        var TCC_modalB = document.getElementById('cookie-modal-overlay-B');
        var TCC_mainBody = document.getElementsByTagName('BODY')[0];

        var TCC_btn_accept_all_first = document.getElementById('tcc_btn_accept_all_first');
        var TCC_customize_btn = document.getElementById('tcc_btn_customize');
        var TCC_btn_accept_preferences = document.getElementById('tcc_btn_accept_preferences');
        var TCC_btn_accept_all_second = document.getElementById('tcc_btn_accept_all_second');
        var TCC_close_popup = document.getElementById('close-cookie-consent-popup');

        if(TCC_btn_accept_all_first) TCC_btn_accept_all_first.addEventListener('click', function(){AcceptAllCookies()}, false);
        if(TCC_customize_btn) TCC_customize_btn.addEventListener('click', function(){CustomizeCookies()}, false);
        if(TCC_btn_accept_preferences) TCC_btn_accept_preferences.addEventListener('click', function(){AcceptPreferences()}, false);
        if(TCC_btn_accept_all_second) TCC_btn_accept_all_second.addEventListener('click', function(){AcceptAllCookies()}, false);
        if(TCC_close_popup) TCC_close_popup.addEventListener('click', function(){ClosePopup()}, false);

        function CloseCookieConsent(){
            if (TCC_modalB) TCC_modalB.style.display = "none";
            document.getElementById('cookie-consent-intro-container').style.display = "none";
            document.getElementById('cookie-consent-customize-container').style.display = "none";
            TCC_mainBody.classList.remove("cookie-consent-active");
            TCC_mainBody.classList.remove("cookie-no-scroll");
        }

        function AcceptAllCookies(){
            document.cookie = "cookie_consent_ab=accepted; expires=Tue, 1 Feb 2025 00:00:00 UTC; path=/;";
            CloseCookieConsent();
        }

        function ClosePopup(){
            document.cookie = "cookie_consent_ab=accepted; expires=Tue, 1 Feb 2025 00:00:00 UTC; path=/;";
            CloseCookieConsent();
        }

        function AcceptPreferences(){
            document.cookie = "cookie_consent_ab=acceptedPreferences; expires=Tue, 1 Feb 2025 00:00:00 UTC; path=/;";
            CloseCookieConsent();
        }

        function CustomizeCookies(){
            document.getElementById('cookie-consent-intro-container').style.display = "none";
            document.getElementById('cookie-consent-customize-container').style.display = "block";
            if (TCC_modalB){
                TCC_modalB.style.display = "block";
                TCC_mainBody.classList.add("cookie-no-scroll");
            }
        }

        // Check if cookie exsists otherwise show consent banner
        var cookies = document.cookie.split(';');
        var cookiesFlag = false;
        cookies.forEach(function(c){
            if(c.match(/cookie_consent_ab=.+/)){
                // console.log("Cookie exsists!!!");
                cookiesFlag = true;
            }
        });

        if (cookiesFlag !== true){
            document.getElementById('cookie-consent-intro-container').style.display = "block";
            document.getElementsByTagName("BODY")[0].classList.add("cookie-consent-active");
        }
    </script>
</cookie-consent-component>
