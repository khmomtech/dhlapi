<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12.05.14
 * Time: 11:01
 */

namespace Wk\DhlApiBundle\Lib\B2b;

/**
 * Class IdentCode
 * @package Wk\DhlApiBundle\Lib\B2b
 */
class IdentCode {

    /**
     * @var int
     */
    private $serial = 1;

    /**
     * @var array
     */
    private $accounts = array();

    /**
     * @param array $accounts
     * @throws \InvalidArgumentException
     */
    public function setAccounts(array $accounts)
    {
        // check the array
        if(!count($accounts)) {
            throw new \InvalidArgumentException('The account array is empty');
        }

        foreach($accounts as $name => $account) {
            if(!is_string($name)) {
                throw new \InvalidArgumentException('The name of the account contains no string');
            }

            if(!is_int($account) || !$account) {
                throw new \InvalidArgumentException('The account id contains no unsigned integer');
            }
        }

        $this->accounts = $accounts;
    }

    /**
     * @return array
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set the serial number with max 5 digits
     *
     * @param int $serial
     */
    public function setSerial($serial)
    {
        // check if the serial is an unsigned integer
        if(!is_int($serial) || !$serial) {
            throw new \InvalidArgumentException('Serial number is no unsigned integer');
        }

        // check the maximum length of 5 for this serial
        if(strlen(strval($serial)) > 5) {
            throw new \InvalidArgumentException('Serial number contains more than 5 digits. Start with 1 again or use modulus 100000');
        }

        // Finally set it
        $this->serial = $serial;
    }

    /**
     * @return int
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Generates an ident code with the given account or it uses the first configured one
     *
     * @param null $account
     * @return string
     */
    public function get($account = null)
    {
        if($account === null) {
            $accountId = $this->accounts[0];
        } elseif (array_key_exists($account, $this->accounts)) {
            $accountId = $this->accounts[$account];
        } else {
            throw new \InvalidArgumentException(sprintf("Account '%s' is not configured", $account));
        }

        $code = sprintf('%d%07d', $accountId, $this->serial);

        return sprintf('%s%d', $code, $this->getParityNumber($code));
    }

    /**
     * Gets the formatted ident code
     *
     * @param string $code
     * @return string
     */
    public static function format($code)
    {
        $buffer = "";

        $j = 0;
        for($i=strlen($code)-1; $i>=0; $i--) {
            $buffer = $code[$i] . $buffer;
            if(($j % 6) == 0) $buffer = " " . $buffer;
            if(($j % 6) == 3) $buffer = "." . $buffer;
            $j++;
        }

        return $buffer;
    }

    /**
     * @param string $code
     * @return int
     */
    public static function getParityNumber($code)
    {
        $number = preg_replace('/[^\d]/', '', $code);

        $result = 0;
        $number = (string)$number;

        for($i=strlen($number)-1; $i>=0; $i = $i-2) {
            $result += $number[$i];
        }

        $result *= 4;

        $partRes = 0;
        for($i=strlen($number)-2; $i>=0; $i = $i-2) {
            $partRes += $number[$i];
        }

        $partRes *= 9;

        $result += $partRes;

        $partRes = ceil($result / 10) * 10;


        return $partRes - $result;
    }

}