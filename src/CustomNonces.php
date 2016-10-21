<?php

namespace CustomNonces;

/**
 * CustomNonce is a class for implementing functionality similar to Wordpress Nonces.
 * They are used to prevent changes in urls and check integrity of string.
 */

class CustomNonce {

    /**
     * integer LIFETIME of nonce in seconds by default 4 hours
     */
    const LIFETIME = 14400;

    /**
     * string nonceLabel name of created url parameter
     */
    private $nonceLabel = "custom-nonce";

    /**
     * integer max length of secret key
     */
    const SECRET_LENGTH = 15;

    /**
     * string variable to hold secret key
     */
    private $secretID = '';

    /**
     * Constructor of the class that get secret string to complicate creating nonce
     * it can be session id or something else
     *
     * @param string $secretID
     * @throws \Exception if secret string is empty
     */
    public function __construct ($secretID) {
        if (empty($secretID) || trim($secretID) == '') {
            throw new \Exception("Secret id can't be empty");
        } else {
            $this->secretID = $secretID;
            if (strlen($this->secretID) > self::SECRET_LENGTH) {
                $this->secretID = substr($this->secretID, 0, self::SECRET_LENGTH);
            }
        }
    }

    /**
     * Function that creates nonce for incoming string
     *
     * @param string $string incoming string for creating hash
     * @throws \Exception if string that getting as argument is empty
     * @return string nonce(hash) for $string
     */
    public function createNonce ($string) {
        if (empty($string) || trim($string) == '') {
            throw new \Exception("String shouldn't be empty");
        }
        $timeTick = $this->getTimeTick();
        $hashString = sha1(trim($string . $this->secretID . $timeTick));
        return $hashString;
    }

    /**
     * Function for returning nonce as url part
     *
     * @param string $string url or part of url that should be hashed
     * @return string like "custom-nonce=XXXXXXXXXXXXXXXXXXX" where XXXXXXXXXXXXXXXXXXX is created nonce
     */
    public function createNonceForUrl ($string) {
        return urlencode($this->nonceLabel . "=" . $this->createNonce($string));
    }

    /**
     * Set custom label that function createNonceForUrl uses for returning
     *
     * @param string $customLabel name of custom parameter instead of standard
     * @throws \Exception if $customLabel is empty
     * @return boolean true if function set label
     */
    public function setCustomLabel ($customLabel) {
        if (trim($customLabel) == "") {
            throw new \Exception("Label for url part can't be empty string");
        } else {
            $this->nonceLabel = strval($customLabel);
        }
        return true;
    }

    /**
     * Function that checks hash for incoming string and incoming nonce
     *
     * @param string $string string for check
     * @param string $nonce nonce to compare
     * @return array $result array with two elements boolean 'status' and string 'errors'
     */
    public function checkNonce ($string, $nonce) {
        $result =  false;
        $timeTick = $this->getTimeTick();
        if (sha1(trim($string . $this->secretID . $timeTick)) == $nonce) {
            //first half of nonce lifetime
            $result = true;
        } elseif (sha1(trim($string . $this->secretID . ($timeTick-1))) == $nonce) {
            //last half of nonce lifetime
            $result = true;
        }
        return $result;
    }

    /**
     * Function for counting number of half periods passed from the start of The Unix Epoch
     *
     * @return integer number of half periods passed from the start of The Unix Epoch
     */
    private function getTimeTick () {
        return ceil(time() / (self::LIFETIME/2));
    }
}