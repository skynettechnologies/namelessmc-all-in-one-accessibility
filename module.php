<?php

/**
 * All in One Accessibility
 *
 * @author Skynet Technologies USA LLC
 * @version 1.0.0
 */

class Aioa_Module extends Module
{

    private Language $_language;
    private Language $_aioa_language;

    public function __construct(Language $language, Language $aioa_language, Pages $pages)
    {
        $this->_language = $language;
        $this->_aioa_language = $aioa_language;

        $name = 'All in One Accessibility';
        $author = '<a href="https://www.skynettechnologies.com/" target="_blank" rel="nofollow noopener">Skynet Technologies USA LLC</a>';
        $module_version = '1.0.0';
        $nameless_version = '2.2.3';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);
        $pages->add('All in One Accessibility', '/allinoneaccessibility', 'pages/allinoneaccessibility.php');

        $pages->add('All in One Accessibility', '/allinoneaccessibility/settings', 'templates/settings.tpl');

    }

    public function onInstall()
    {
        // Not necessary for CookieConsent
    }

    public function onUninstall()
    {
        // Not necessary for CookieConsent
    }

    public function onEnable()
    {
        // Not necessary for CookieConsent
    }

    public function onDisable()
    {
        // Not necessary for CookieConsent
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, $smarty, $navs, Widgets $widgets, TemplateBase $template)
    {
        $language = $this->_language;
        $aioa_url = URL::build('/all_in_one_accessibility');

        // AdminCP
        PermissionHandler::registerPermissions($language->get('moderator', 'staff_cp'), [
            'admincp.aioa' => $this->_aioa_language->get('aioa', 'aioa')
        ]);

        if (defined('FRONT_END')) {
            $template->addJSFiles([
                'https://www.skynettechnologies.com/accessibility/js/all-in-one-accessibility-js-widget-minify.js?colorcode=#420083&token=&position=bottom_right' => [],
            ]);
        }

        $template->getEngine()->addVariables([
            'AIOA_URL' => $aioa_url,
            'AIOA_NOTICE_HEADER' => $this->_aioa_language->get('aioa', 'aioa_notice'),
            'AIOA_NOTICE_BODY' => $this->_aioa_language->get('aioa', 'aioa_notice_info'),
            'AIOA_NOTICE_CONFIGURE' => $this->_aioa_language->get('aioa', 'configure_aioa'),
        ]);


        if (defined('BACK_END')) {
            $cache->setCache('panel_sidebar');

            $navs[2]->add('aioa_divider', mb_strtoupper($this->_aioa_language->get('aioa', 'aioa'), 'UTF-8'), 'divider', 'top', null, 10, '');
            $navs[2]->add('aioa_settings', $this->_aioa_language->get('aioa', 'aioa'), URL::build('/allinoneaccessibility'), 'top', '_blank', 10 + 0.1, '');
        }
    }

    public function getDebugInfo(): array
    {
        return [];
    }
}
