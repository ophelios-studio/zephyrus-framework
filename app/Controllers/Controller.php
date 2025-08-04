<?php namespace Controllers;

use Models\Core\Entities\Now;
use Zephyrus\Application\Controller as BaseController;
use Models\Core\Application;
use Zephyrus\Application\Configuration;
use Zephyrus\Application\Flash;
use Zephyrus\Network\Response;
use Zephyrus\Security\ContentSecurityPolicy;
use Zephyrus\Security\SecureHeader;

abstract class Controller extends BaseController
{
    public function before(): ?Response
    {
        return parent::before();
    }

    public function render(string $page, array $args = []): Response
    {
        $projectName = Configuration::getApplication('project');
        $arguments = array_merge($args, [

            /**
             * Previous page the user accessed.
             */
            "referer" => $this->request->getReferer(),

            /**
             * Keep the defined controller Root attribute (for easier navigation).
             */
            "route_root" => $this->request->getRouteDefinition()->getRouteRoot(),

            /**
             * String representation of the currently loaded language (e.g. français (Canada)).
             */
            "loaded_language" => $this->getLoadedLanguage(),
            "loaded_locale" => Application::getInstance()->getLocalization()->getLocale(),

            /**
             * List of all installed and available languages.
             */
            "installed_languages" => Application::getInstance()->getSupportedLanguages(),

            /**
             * Token for script execution.
             */
            "nonce" => nonce(),

            /**
             * All flash messages (necessary for zf-flash() component).
             */
            "flash" => Flash::readAll(),

            /**
             * Current date values.
             */
            "now" => new Now(),

            /**
             * Name of the application that should be used within every page as browser title.
             */
            "project_name" => $projectName
        ]);
        return parent::render($page, $arguments);
    }

    protected function setupSecurityHeaders(SecureHeader $secureHeader): void
    {
        $csp = new ContentSecurityPolicy();
        $csp->setFontSources(["'self'", 'https://fonts.googleapis.com', 'https://fonts.gstatic.com']);
        $csp->setStyleSources(["'self'", 'https://fonts.googleapis.com', ContentSecurityPolicy::UNSAFE_INLINE]);
        $csp->setScriptSources(["'self'", 'https://cdn.jsdelivr.net']);
        $csp->setChildSources(["'self'"]);
        $csp->setWorkerSources(["blob:"]);
        $csp->setConnectSources(["'self'"]);
        $csp->setImageSources(["'self'", 'blob:', 'data:']);
        $csp->setBaseUri([$this->request->getUrl()->getBaseUrl()]);
        $secureHeader->setContentSecurityPolicy($csp);
    }

    /**
     * Retrieves the currently loaded language as a string with its country. E.g. "français (Canada)".
     *
     * @return string
     */
    private function getLoadedLanguage(): string
    {
        $localization = Application::getInstance()->getLocalization();
        $loadedLanguage = "";
        foreach ($localization->getInstalledLanguages() as $language) {
            if ($language->locale == $localization->getLocale()) {
                $loadedLanguage = $language->lang . ' (' . $language->country . ')';
            }
        }
        return $loadedLanguage;
    }
}
