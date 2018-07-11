<?php
namespace functions;

class LangHelper
{
    public static $translateArray;

    /**
      * Translate function for admin only.
      *
      * @param $termId string term_id from messages.php
      * @return string
      **/
    public static function translate($termId, $replace = null)
    {
        // Set array values
        if (!isset(self::$translateArray)) {
            self::$translateArray = self::getTranslations();
        }

        $lang = self::wpLanguage();

        if (isset(self::$translateArray[$termId])) {
            foreach (self::$translateArray[$termId] as $langText => $value) {
                $langText = strtolower(trim($langText));
                if (preg_match('/-/', $langText) && $langText == strtolower(trim($langText))) {
                    if (!is_null($replace)) {
                        return sprintf($value, $replace);
                    } else {
                        return $value;
                    }
                } elseif (preg_match('/'. $langText .'/', $lang)) {
                    if (!is_null($replace)) {
                        return sprintf($value, $replace);
                    } else {
                        return $value;
                    }
                }
            }
        }
        return $termId.' not found';
    }

    /**
      * Get user language if it have been defined. If not, take blog language
      **/
    private static function wpLanguage()
    {
        $user = wp_get_current_user();
        $userLocale = get_user_meta($user->ID, 'locale', true);
        return $userLocale ? $userLocale : get_bloginfo('language');
    }

    /**
      * Get translation array
      * @return array
      **/
    private static function getTranslations()
    {
        return
            require __DIR__. '../../translations/messages.php';
    }
}
