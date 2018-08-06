<?php

namespace App\Helpers\Theme;

class Config {

    CONST WKHTMLTOPDF_CONFIG_KEY = 'frontend.wkhtmltopdf';

    CONST WKHTMLTOPDF_CONFIG_MARGIN = 'margin';

    CONST WKHTMLTOPDF_CONFIG_PREFIX = 'pdf-';

    CONST WKHTMLTOPDF_CONFIG_MARGIN_PAGE = 'page-x';

    CONST WKHTMLTOPDF_CONFIG_HEADER = 'pdf-header';

    CONST WKHTMLTOPDF_CONFIG_FOOTER = 'pdf-footer';

    /**
     *  Configuration which is set in download pdf
     * 
     * @var array 
     */
    protected $config;

    /**
     * Wkhtmltopdf config
     *
     * @var mixed
     */
    public $wkhtmltopdfConfig;


    public $compiledConfig;


    /**
     * Config constructor.
     *
     * @param $config
     */
    public function __construct($config) {
        $this->config            = $config;
        $this->wkhtmltopdfConfig = config(self::WKHTMLTOPDF_CONFIG_KEY);

        $this->_rawConfigToKeyValue();
        $this->_compilePdfHeaderFooterConfig();
        $this->_compilePdfMerginConfig();
    }

    public function getPdfConfig() {
        return $this->compiledConfig;
    }

    private function _compilePdfHeaderFooterConfig() {
        if (count($this->config)) {
            $themeName = isset($this->config['pdf-theme-name']) ? $this->config['pdf-theme-name'] : '';

            if ( ! empty($themeName)) {
                if(isset($this->config[self::WKHTMLTOPDF_CONFIG_HEADER])) {
                    $header = 'uploads/themes/' . $themeName . '/' . $this->config[self::WKHTMLTOPDF_CONFIG_HEADER];
                    if (check_file($header)) {
                        $this->wkhtmltopdfConfig['header-html'] = url($header);
                    }
                }

                if(isset($this->config[self::WKHTMLTOPDF_CONFIG_FOOTER])) {
                    $footer ='uploads/themes/' . $themeName . '/'  . $this->config[self::WKHTMLTOPDF_CONFIG_FOOTER];
                    if (check_file($footer)) {
                        $this->wkhtmltopdfConfig['footer-html'] = url($footer);
                    }

                }
            }
        }
    }

    private function _compilePdfMerginConfig() {
        $marginConfigsEachPage = $this->_getPdfMarginConfigsEachPage();

        if (count($marginConfigsEachPage)) {
            $pdfConfig = ['marginEachPage' => true];

            foreach ($marginConfigsEachPage as $key => $pageMargins) {
                $pageNum             = explode('-', $key);
                $configWithoutMargin = $this->_getConfigPdfWithoutMarginEachPage();

                if (count($pageMargins)) {
                    foreach ($pageMargins as $marginKey => $margin) {
                        if (isset($configWithoutMargin[$marginKey])) {
                            $configWithoutMargin[$marginKey] = $margin;
                        }
                    }
                }

                $pdfConfig[] = [
                    'page' => isset($pageNum[1]) ? $pageNum[1] : 0,
                    'config' => $configWithoutMargin
                ];
            }

        } else {
            $pdfConfig = $this->_getConfigPdfWithoutMarginEachPage();
        }

        $this->compiledConfig = $pdfConfig;
    }

    private function _getConfigPdfWithoutMarginEachPage() {

        if (count($this->config)) {
            foreach($this->config as $configKey => $configValue) {

                $checkCogPdf = substr($configKey, 0, strlen(self::WKHTMLTOPDF_CONFIG_PREFIX));

                if ($checkCogPdf === self::WKHTMLTOPDF_CONFIG_PREFIX) {

                    $realCogKey = $this->_getPdfConfigKey($configKey);

                    if ( ! $this->_checkMarginConfigEachPage($configKey) && isset($this->wkhtmltopdfConfig[$realCogKey])) {
                        $this->wkhtmltopdfConfig[$realCogKey] = $configValue;
                    }
                }
            }

            return $this->wkhtmltopdfConfig;
        }
        
        return $this->wkhtmltopdfConfig;
    }

    private function _getPdfMarginConfigsEachPage() {

        $marginConfigs = [];
        if (count($this->config)) {
            foreach($this->config as $configKey => $configValue) {

                $checkCogPdf = substr($configKey, 0, strlen(self::WKHTMLTOPDF_CONFIG_PREFIX));

                if ($checkCogPdf === self::WKHTMLTOPDF_CONFIG_PREFIX) {

                    $realCogKey = $this->_getPdfConfigKey($configKey);

                    if ($this->_checkMarginConfigEachPage($configKey)) {
                        $page   = $this->_getPdfMarginConfigPage($realCogKey);
                        $margin = substr($realCogKey, 0, strlen($realCogKey) - strlen(self::WKHTMLTOPDF_CONFIG_MARGIN_PAGE) - 1); //Minus 1 because of the "-"

                        $marginConfigs[$page][$margin] = $configValue;
                    }
                }
            }
        }

        return $marginConfigs;
    }

    protected function _checkMarginConfigEachPage($rawConfigKey) {

        if (substr($rawConfigKey, strlen(self::WKHTMLTOPDF_CONFIG_PREFIX), strlen(self::WKHTMLTOPDF_CONFIG_MARGIN)) == self::WKHTMLTOPDF_CONFIG_MARGIN) {

            // Config key is kind of margin-top-page-xxx, margin-botom-page-xxx
            if (count( explode('-', $this->_getPdfConfigKey($rawConfigKey)) ) > 2) {
                return true;
            }
        }

        return false;
    }

    private function _getPdfConfigKey($rawConfigKey) {
        return substr($rawConfigKey, strlen(self::WKHTMLTOPDF_CONFIG_PREFIX), strlen($rawConfigKey) - strlen(self::WKHTMLTOPDF_CONFIG_PREFIX));
    }

    private function _getPdfMarginConfigPage($configKey) {
        return substr($configKey, strlen($configKey) - strlen(self::WKHTMLTOPDF_CONFIG_MARGIN_PAGE), strlen(self::WKHTMLTOPDF_CONFIG_MARGIN_PAGE));
    }

    private function _rawConfigToKeyValue() {

        $config = [];

        if (count($this->config)) {
            foreach ($this->config as $one) {
                $spliter = explode(':', $one);
                $cogKey  = isset($spliter[0]) ? $spliter[0] : '';
                $cogval  = isset($spliter[1]) ? $spliter[1] : '';
                $config[$cogKey] = $cogval;
            }
        }

        $this->config = $config;
    }
}
    