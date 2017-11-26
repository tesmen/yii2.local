<?php

namespace app\models;

use Techy\Lib\Core\System\Application;
use Techy\Lib\Core\View\Native;

class AssetManager
{
    static $instance;
    private $assets = [];
    private $scripts = [];

    private function __construct()
    {
    }

    /**
     * @return AssetManager
     */
    public static function instance()
    {
        return static::$instance = new static;
    }

    public function flush()
    {
        $this->renderAssets();
        $this->renderScripts();
    }

    private function renderAssets()
    {
        $this->assets = array_unique($this->assets);
        $this->addAssetVersion();

        echo "\n";
        echo implode("\n", $this->assets);
        echo "\n";
        $this->assets = [];
    }

    private function renderScripts()
    {
        return;
        $viewDir = Application::instance()->getViewDir();

        foreach (array_unique($this->scripts) as $scriptPath) {
            $path = $viewDir . Native::TEMPLATE_DIR . $scriptPath;
            ob_start();

            try {
                include $path;
                $output = ob_get_clean();
            } catch (\Exception $e) {
                ob_end_clean();
                throw $e;
            }

            echo "\n {$output} \n";
        }
    }

    public function addAsset($asset)
    {
        $this->assets[] = $asset;

        return $this;
    }

    public function addScript($script)
    {
        $this->scripts[] = $script;

        return $this;
    }

    private function addAssetVersion()
    {
        $assetVersion = '?vv=' . ASSET_VERSION;

        foreach ($this->assets as &$asset) {
            $asset = str_replace(['.js"', '.css"'], [".js{$assetVersion}\"", ".css{$assetVersion}\""], $asset);
        }
    }

    public function getJquery()
    {
        return $this->addAsset('<script src="/static/js/jquery-3.2.1.min.js"></script>');
    }

    public function getCommonJs()
    {
        return $this->addAsset('<script src="/static/js/common.js"></script>');
    }

    public function getAngular16()
    {
        return $this->addAsset('<script src="/static/js/angular.js"></script>');
    }

    public function getAngularSanitize()
    {
        return $this->addAsset('<script src="/static/js/angular-sanitize.js"></script>');
    }

    public function getMeetingsApplication()
    {
        return $this
            ->addAsset('<script src="/static/js/meetings-config.js"></script>')
            ->addAsset('<script src="/static/js/meetings-app.js"></script>');
    }

    public function getAngularNotify()
    {
        return $this
            ->addAsset('<link rel="stylesheet" type="text/css" href="/css/angular/angular-notify.min.css">')
            ->addAsset('<script src="/javascript/vendor/angular/angular-notify.min.js"></script>');
    }

    public function getAngularRoute()
    {
        return $this->addAsset('<script src="/static/js/angular-route.js"></script>');
    }

    public function getAngularUiBootstrap()
    {
        return $this->addAsset('<script src="/static/ui-bootstrap-tpls-2.5.0.js"></script>');
    }

    public function getTicketServices()
    {
        return $this->addAsset('<script src="/admin/js/tickets/services.js"></script>');
    }

    public function getAdminDirectives()
    {
        return $this->addAsset('<script src="/admin/js/directives.js"></script>');
    }

    public function getAdminAngularBase()
    {
        return $this->addAsset('<script src="/admin/js/admin-angular-base.js"></script>');
    }

    public function getAdminForms()
    {
        return $this
            ->scriptJs18n()
            ->getAngularSanitize()
            ->getAngularSelect()
            ->getAngularUiBootstrap()
            ->addAsset('<script src="/static/js/meetings-directives.js"></script>');
    }

    /**
     * @return AssetManager
     * jsVar admin_i18n
     */
    public function scriptJs18n()
    {
        return $this->addScript('scripts/admin-i18n-script.phtml');
    }

    /**
     * @return AssetManager
     * jsVar admin_new_admin_users
     */
    public function scriptNewAdminUsers()
    {
        return $this->addScript('scripts/new-admin-users-script.phtml');
    }

    public function getFontAwesome()
    {
        return $this->addAsset('<link rel="stylesheet" href="/css/font-awesome/font-awesome.min.css">');
    }

    public function getAngularSelect()
    {
        return $this
            ->addAsset('<link type="text/css" rel="/static/angular-ui-select/select.css">')
            ->addAsset('<script src="/static/angular-ui-select/select.js"></script>');
    }

    public function getAngularMultiSelect()
    {
        return $this
            ->addAsset('<link rel="stylesheet" href="/admin/css/angular-multi-select-tree-0.1.0.css">')
            ->addAsset('<script src="/admin/js/angular-multi-select-tree-0.1.0.js"></script>')
            ->addAsset('<script src="/admin/js/angular-multi-select-tree-0.1.0.tpl.js"></script>');
    }

    public function getSelect2()
    {
        return $this
            ->addAsset('<link rel="stylesheet" type="text/css" href="/packages/select2/select2.css"/>')
            ->addAsset('<link rel="stylesheet" href="/packages/select2-bootstrap-css/select2-bootstrap.min.css"/>')
            ->addAsset('<script src="/packages/select2/select2.min.js"></script>');
    }

    public function getHighChartsHelper()
    {
        return $this->addAsset('<script src="/js/highcharts-hepler.js"></script>');
    }

    public function getDateTimePicker()
    {
        return $this
            ->addAsset('<link href="/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css"/>')
            ->addAsset('<script src="/javascript/vendor/jquery.datetimepicker.js"></script>');
    }
}
