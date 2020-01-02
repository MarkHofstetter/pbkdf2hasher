<?php

namespace MarkHofstetter\Pbkdf2Hasher\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Contracts\Hashing\HashManager;

class Pbkdf2Hasher extends Facade
{
    protected $algo = 'sha512';
    protected $iterations = '100001';
    protected $length = null;

    # fixed at the moment
    protected $hashing_method = 'pbkdf2'; 

    public function __construct(array $options = [])
    {}



    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pbkdf2hasher';
    }


    public function info($hashedValue){}


    /**
     * create hash value + algo info 
     * to be compatible with https://werkzeug.palletsprojects.com/en/0.15.x/utils/#module-werkzeug.security
    */
    public function make($value, array $options = [])
    {
        $this->algo = $options['algo'] ?? $this->algo;
        $this->iterations = $options['iterations'] ?? $this->iterations;
        $this->length = $options['length'] ?? $this->length;
        $this->salt = $options['salt'] ??  $salt = bin2hex(openssl_random_pseudo_bytes(8));
        $hash = hash_pbkdf2($this->algo, $value, $this->salt, $this->iterations, $this->length);
        return sprintf("%s:%s:%s$%s$%s", $this->hashing_method, $this->algo, $this->iterations, $this->salt, $hash);
    }


    public function check($value, $hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0 || strlen($value) === 0) {
            return false;
        }

        preg_match('/(.+?)\:(.+?)\:(.+?)\$(.+?)\$(.*)/', $hashedValue, $matches);
        if ($matches) {
            $options['algo'] = $matches[2];
            $options['iterations'] = $matches[3];
            $options['salt'] =  $matches[4];
            $hash =  $matches[5];
        } else {
            return false;
        }

        return ($this->make($value, $options) === $hashedValue);
    }


    public function needsRehash($hashedValue, array $options = []) {}

}
