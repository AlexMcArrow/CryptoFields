<?php

/**
 * @copyright Alex McArrow 2011
 * @author Alex McArrow
 * @package CryptoFields
 * @name Main Class
 * @version 0.2 (05.10.2011)
 */
/**
 * Use soft method: do not delete exist keys
 */
const CF_SOFT = 0;
/**
 * Use hard method: delete exist keys
 */
const CF_HARD = 1;

const CF_SHA1 = 0;
const CF_MD5 = 1;
const CF_CRC = 2;

/**
 * Generator for fields name
 */
class CryptoFields {

    private $Salt;
    private $Method;
    private $Coder;
    private $Keys;
    private $CFKeys;
    private $CFsyeK;
    private $HasEncode;
    private $HasGetData;

    /**
     * Construct
     * @param string $salt
     * @param integer $method CF_SOFT CF_HARD
     * @param integer $coder CF_CHA1 CF_MD5 CF_CRC
     */
    function __construct ($salt, $method = CF_SOFT, $coder = CF_SHA1) {
        $this->Salt = $salt;
        $this->Method = $method;
        $this->Coder = $coder;
        $this->HasEncode = FALSE;
        $this->HasGetData = FALSE;
        $this->Keys = array ();
        $this->CFKeys = array ();
        $this->CFsyeK = array ();
    }

    /**
     * Add new field name
     * @param string $keyname 
     */
    public function addkey ($keyname) {
        $this->Keys[$keyname] = NULL;
    }

    /**
     * Add new fields names
     * @param array $keylist 
     */
    public function addkeylist ($keylist) {
        foreach ($keylist as $key => $value) {
            $this->addkey ($value);
        }
    }

    /**
     * Get uniq field name for $keyname
     * @param string $keyname
     * @return string 
     */
    public function getkey ($keyname) {
        if (!$this->HasEncode) {
            $this->encode ();
        }
        return $this->CFKeys[$keyname];
    }

    /**
     * Get all added uniq fields names
     * @return array 
     */
    public function getkeylist () {
        if (!$this->HasEncode) {
            $this->encode ();
        }
        return $this->CFKeys;
    }

    /**
     * Get value of field transferred by $_REQUEST $_GET $_POST
     * @param string $keyname
     * @return string 
     */
    public function getvalue ($keyname) {
        if (!$this->HasGetData) {
            $this->GetDataFromExt ();
        }
        return $this->Keys[$keyname];
    }

    /**
     * Get values of fields transferred by $_REQUEST $_GET $_POST
     * @return array 
     */
    public function getvaluelist () {
        if (!$this->HasGetData) {
            $this->GetDataFromExt ();
        }
        return $this->Keys;
    }

    /**
     * Generate uniq keyname for added fileds
     */
    public function encode () {
        foreach ($this->Keys as $key => $value) {
            $GENKEY = $this->GenerateCFKey ($key);
            $this->CFKeys[$key] = $GENKEY;
            $this->CFsyeK[$GENKEY] = $key;
        }
        $this->HasEncode = TRUE;
    }

    /**
     * Start analize $_REQUEST $_GET $_POST
     */
    public function decode () {
        if (!$this->HasGetData) {
            $this->GetDataFromExt ();
        }
    }

    /**
     * Analize $_REQUEST $_GET $_POST
     */
    private function GetDataFromExt () {
        $this->DecodeExtData ($_REQUEST);
        $this->DecodeExtData ($_GET);
        $this->DecodeExtData ($_POST);
        $this->HasGetData = TRUE;
    }

    /**
     * Check exist key in store, put values and replace key
     * @param array $from 
     */
    private function DecodeExtData (&$from) {
        if ($this->Method === CF_HARD) {
            foreach ($this->CFKeys as $key => $value) {
                if (isset ($from[$key])) {
                    unset ($from[$key]);
                }
            }
        }
        foreach ($from as $key => $value) {
            if (isset ($this->CFsyeK[$key])) {
                $from[$this->CFsyeK[$key]] = $value;
                $this->Keys[$this->CFsyeK[$key]] = $value;
                unset ($from[$key]);
            }
        }
    }

    /**
     * Generate uniq key with selected coder
     * @param string $key
     * @return string 
     */
    private function GenerateCFKey ($key) {
        switch ($this->Coder) {
            case CF_SHA1:
                return sha1 ($this->Salt . '_' . $key);
                break;
            case CF_MD5:
                return md5 ($this->Salt . '_' . $key);
                break;
            case CF_CRC:
                return crc32 ($this->Salt . '_' . $key);
                break;
        }
    }

}

?>