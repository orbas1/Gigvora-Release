<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class OptionBuilderService {

    protected $protectedTabKeys = array();
    protected $envTabKeys       = array();
    protected $publicTabKeys    = array();

    public function __construct() {
        $this->protectedTabKeys =  [
            'auth_videoask' => '*',
            '_airtable' => '*',
            '_webhook' => '*',
            '_taxonomies' => '*',
            '_api' => [
                'VIDEOASK_CLIENT_ID',
                'VIDEOASK_CLIENT_SECRET',
                'VIDEOASK_REDIRECT_URI',
                'VIDEOASK_ORGANIZATION_ID',
                'OPENAI_API_KEY',
                'COMETCHAT_API_KEY',
                'COMETCHAT_AUTH_KEY',
                // 'AWS_ACCESS_KEY_ID',
                // 'AWS_SECRET_ACCESS_KEY',
                // 'AWS_DEFAULT_REGION',
                // 'AWS_BUCKET',
                'ALGOLIA_SECRET',
                'COURIER_AUTH_TOKEN',
                // 'STRIPE_SECRET'
            ],
            '_email' => '*',
            '_courier' => '*'
        ];

        $this->publicTabKeys = [
            '_site' => [
                // 'signup_bg',
                // 'final_step_desc',
                // 'reset_password_bg',
                // 'reset_password_des',
                // 'signin_bg',
                // 'signin_description',
                'site_dark_logo',
                'site_lite_logo',
                'site_name',

                // 'site_favicon', // comment for temporary
                // 'terms_condition_page_url',
                // 'signup_description',
                // 'term_condition_page_url',
                // 'website_use_page_url',
                // 'privacy_policy_page_url',
                // 'site_dark_logo_full'
            ],
            // '_social' =>'*'
            
        ];
    }

    private function isPublicKey($tab, $key = null): bool {
        if (!empty($this->publicTabKeys[$tab])) {
            if ($this->publicTabKeys[$tab] == '*')
                return true;
            elseif (!empty($key) && in_array($key, $this->publicTabKeys[$tab]))
                return true;
            else
                return false;
        }
        return false;
    }

    private function isProtectedKey($tab, $key = null): bool {
        if (!empty($this->protectedTabKeys[$tab])) {
            if ($this->protectedTabKeys[$tab] == '*')
                return true;
            elseif (!empty($key) && in_array($key, $this->protectedTabKeys[$tab]))
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * Get The Value of Key Specified
     * @param $key String
     * @return mixed String
     */
    public function get(string  $key = NULL, bool $admin = false) {

        $settings   = $this->getSettings();

        if (!empty($key)) {
            $key        = explode('.', $key);
            $section    = $key[0];
            $key        = !empty($key[1]) ? $key[1] :  '';

            if (empty($key)) { // return all settings
                $sectionSettings = array();
                if (!empty($settings[$section])) {
                    foreach ($settings[$section] as $setting => $value) {
                        $sectionSettings[$setting] = $this->decodeValue($value);
                    }
                }
                return $sectionSettings;
            } elseif (isset($settings[$section][$key])) {           //return selected setting
                return $this->decodeValue($settings[$section][$key]);
            }
        } else {
            $allSettings = [];
            if (!empty($settings)) {
                foreach ($settings as $section => $fields) {
                    foreach ($fields as $settingKey => $value) {
                        if ($admin || !$this->isProtectedKey($section, $settingKey)) {
                            $allSettings[$section][$settingKey] = $this->decodeValue($value);
                        }
                    }
                }
            }
            return $allSettings;
        }
    }

    /**
     * Get Public Keys Only
     * @return array
     */

    public function getPublicKeys() {
        $settings   = $this->getSettings();
        $allSettings = [];
        if (!empty($settings)) {
            foreach ($settings as $section => $fields) {
                foreach ($fields as $settingKey => $value) {
                    if ($this->isPublicKey($section, $settingKey)) {
                        $allSettings[$section][$settingKey] = $this->decodeValue($value);
                    }
                }
            }
        }
        return $allSettings;
    }

    private function decodeValue($settingValue) {

        $value = @unserialize($settingValue);
        if ($value == 'b:0;' || $value !== false) {
            $temp = [];
            foreach ($value as $key => $data) {
                if (is_array($data)) {
                    $temp[$key] = self::jsonDecodedArr($data);
                } else {
                    if (self::isJSON($data)) {
                        $temp[$key] = json_decode($data, true);
                    } else {
                        $temp[$key] = $data;
                    }
                }
            }
            return $temp;
        } else {
            if (self::isJSON($settingValue)) {
                return (json_decode($settingValue, true));
            } else {
                return $settingValue;
            }
        }
    }

    /**
     * get json_decoded array
     * @param $arr Array
     * @param mixed String $value
     * @return Void
     */
    private function jsonDecodedArr(&$arr) {

        foreach ($arr as $key => &$el) {

            if (is_array($el)) {
                self::jsonDecodedArr($el);
            } else {
                if (self::isJSON($el)) {
                    $el = json_decode($el, true);
                }
            }
        }
        return  $arr;
    }
    /**
     * check string is json or not
     * @param $string String
     * @param mixed String $value
     * @return Void
     */
    private function isJSON($string) {

        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public function getSettings() {

        if (config('cache', true)) {

            return Cache::rememberForever('optionbuilder__settings', function () {
                return $this->fetchSettings();
            });
        } else {
            return $this->fetchSettings();
        }
    }


    /**
     * fetch From DB
     * @return array
     */
    private function fetchSettings() {

        $sections = [];
        $settings =  DB::table('optionbuilder__settings')->get();
        if (!empty($settings)) {
            foreach ($settings as $single) {
                $sections[$single->section][$single->key] = $single->value;
            }
        }
        return $sections;
    }

    /**
     * reset the section
     * @param $key String
     * @return Void
     */
    public  function resetSection($key = false) {

        if (!empty($key)) {
            DB::table('optionbuilder__settings')->whereSection($key)->delete();
        } else {
            DB::table('optionbuilder__settings')->truncate();
        }
        if (config('cache', true)) {
            Cache::forget('optionbuilder__settings');
            // Artisan::call('config:clear');
        }
    }

    /**
     * Set The Value of Key Specified
     * @param $key String
     * @param mixed String $value
     * @return Void
     */
    public function set(string $section = NULL,  string $key = NULL, array|string $value) {
        if (is_array($value)) {
            $value = serialize($value);
        }
        $this->store($section, $key, $value);
        if (config('cache', true))
            Cache::forget('optionbuilder__settings');
        // Artisan::call('config:clear');
        return true;
    }

    /**
     * Storing New & Updating existinsg Settings
     * @param $key of setting
     * @param $value of key
     */
    public function store($section, $key, $value) {

        DB::table('optionbuilder__settings')->updateOrInsert(
            [
                'section'   => $section,
                'key'       => $key
            ],
            [
                'section'   => sanitizeTextField($section),
                'key'       => sanitizeTextField($key),
                'value'     => $value
            ]
        );
    }


    // public function uploadFiles($data) {
    //     $arr = [];
    //     if (!empty($data['files'])) {
    //         foreach ($data['files'] as $file) {
    //             if (is_file($file)) {

    //                 $ext        = $file->guessExtension();
    //                 $type       = 'file';
    //                 $thumbnail  = 'vendor/optionbuilder/images/file-preview.png';
    //                 $orgName    = $file->getClientOriginalName();
    //                 $size       = filesize($file);
    //                 // $fileName   = rand(1, 9999) . date('m-d-Y_hia') . $file->getClientOriginalName();
    //                 // $filepath   = $file->storeAs('optionbuilder/uploads', $fileName, 'public');
    //                 $filepath   = (new MediaService())->uploadObFile($file);

    //                 if (substr($file->getMimeType(), 0, 5) == 'image') {
    //                     $type       = 'image';
    //                     $thumbnail  = URL(Storage::url($filepath));
    //                 }

    //                 array_push($arr, [
    //                     'type'          => $type,
    //                     'name'          => $orgName,
    //                     'path'          => URL(Storage::url($filepath)),
    //                     'storage_path'  => $filepath,
    //                     'mime'          => $ext,
    //                     'size'          => $size,
    //                     'thumbnail'     => $thumbnail,
    //                 ]);
    //             }
    //         }
    //     }
    //     return $arr;
    // }
}


