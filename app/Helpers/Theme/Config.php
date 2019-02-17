<?php

namespace App\Helpers\Theme;

/**
 * Resume configuration is a string of json format
 *
 * {% config %}
 * JSON_FORMAT
 * {% endconfig %}
 *
 * To set config for wkhtmltopdf the json must contain the key 'pdf'
 * "pdf" => [
 *  {
        //configuration for page 1
 *      margin-top: xx,
 *      margin-bottom: xx,
 *      ...
 *      ...
 *  },
 *  {
        //configuration for page 2,
 *      ...
 *      ...
 *  },
 * `{...}
 * ]
 *
 */
class Config {

    /**
     * Wkhmltopdf config prefix.
     */
    const WKHTMLTOPDF_CONFIG_PREFIX = 'pdf';

    /**
     * List of wkhtmltopdf's config that are allowed.
     */
    CONST WKHTMLTOPDF_CONFIG_ALLOW = [
        'margin-top',
        'margin-right',
        'margin-bottom',
        'margin-left',
    ];

    /**
     * Default wkhtmltopdf config
     *
     * @var array $wkhtmltopdfConfig
     */
    protected $wkhtmltopdfDefaultConfig;

    /**
     * Raw config
     *
     * @var array $rawConfig
     */
    protected $rawConfig;

    /**
     * Resume config
     *
     * @var array
     */
    protected $config;

    /**
     * Config constructor.
     *
     * @param array $rawConfig An array of mixing config from html file
     */
    public function __construct($rawConfig) {
        $rawConfig    = trim($rawConfig);
        $this->config = (empty($rawConfig)) ? [] : json_decode($rawConfig, true);
    }

    /**
     * Get config
     *
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Get config for wkhtmltopdf
     *
     * array(wkhtmltopdf_config1, wkhtmltopdf_config_2)
     *
     * @return array|mixed
     */
    protected function _getWkhtmltopdfConfig() {

        if (count($this->rawConfig) && isset($this->rawConfig['pdf']) && count($this->rawConfig['pdf'])) {
            $rawPdfConfig     = $this->rawConfig['pdf'];
            $pdfConfigPerPage = [];

            foreach ($rawPdfConfig as $cogs) {
                if (count($cogs)) {
                    $defaultConfig = $this->wkhtmltopdfDefaultConfig;
                    foreach ($cogs as $k => $cog) {
                        // Check config is allowed to use or not.
                        if (in_array($k, self::WKHTMLTOPDF_CONFIG_ALLOW)) {
                            $defaultConfig[$k] = $cog;
                        }
                    }

                    $pdfConfigPerPage[] = $defaultConfig;
                }
            }

            return $pdfConfigPerPage;
        }

        return [$this->wkhtmltopdfDefaultConfig];
    }
}
    