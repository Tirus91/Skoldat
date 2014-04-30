<?php
/**
 * Localize.php
 * this file is contains Localize class definition
 *
 * @copyright Copyright (c) 2009 Igor Hlina
 * @license read LICENCE.txt
 *
 */


/**
 * Localize class
 * This is only example of class, which provides localized messages.
 * Localized messages is in this example stored in simple array.
 * Feel free to implement custom storage engine.
 *
 */
class Localize
{

    /**
     * Associative array with translations
     *
     * @var array
     */
    private $translations = array(
        'cs' => array(
            'Error404' => 'Page not found',
            'ErrorDatabase' => 'Database Error',
            'TitleIndex' => 'Welcome',
            'TitleNews' => 'News',
            'TitleTable' => 'Table example',
        )
    );


    /**
     * Constructor
     *
     */
    public function __construct()
    {
        
    }


    /**
     * Return localized message identified by given name
     *
     * @param string $name
     * @param string $lang
     * @return string
     */
    public function getLocalizedMessage($name, $lang)
    {
        if (array_key_exists($lang, $this->translations)) {

            if (array_key_exists($name, $this->translations[$lang])) {
                return $this->translations[$lang][$name];

            } else {
                throw new Exception("Localized message '$name' not found!");
            }

        } else {
            throw new Exception("Translations for language '$lang' not found!");
        }
    }

}
