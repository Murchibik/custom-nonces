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
    private $secretLength = 15;

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
            if (strlen($this->secretID) > $this->secretLength) {
                $this->secretID = substr($this->secretID, 0, $this->secretLength);
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
     * @param string $customLabel name of custom parameter instead of standard
     * @return string like "custom-nonce=XXXXXXXXXXXXXXXXXXX" where XXXXXXXXXXXXXXXXXXX is created nonce
     * @throws \Exception if string that getting as argument is empty
     */
    public function createNonceForUrl ($string, $customLabel = "") {
        if ($customLabel != "") {
            $this->nonceLabel = strval($customLabel);
        }
        return urlencode($this->nonceLabel . "=" . $this->createNonce($string));
    }

    /**
     * Function that checks hash for incoming string and incoming nonce
     *
     * @param string $string string for check
     * @param string $nonce nonce to compare
     * @return array $result array with two elements boolean 'status' and string 'errors'
     */
    public function checkNonce ($string, $nonce) {
        $result = array (
            'status' => false,
            'errors' => ''
        );
        $timeTick = $this->getTimeTick();
        if (sha1(trim($string . $this->secretID . $timeTick)) == $nonce) {
            //first half of nonce lifetime
            $result['status'] = true;
            return $result;
        } elseif (sha1(trim($string . $this->secretID . ($timeTick-1))) == $nonce) {
            //last half of nonce lifetime
            $result['status'] = true;
            return $result;
        } else {
            $result['errors'] = "String doesn't equal to nonce";
            return $result;
        }
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