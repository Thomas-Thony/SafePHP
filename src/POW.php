<?php

namespace SafePHP;

/**
 * Handler of "proof of work" (POW)
 */
class POW {
    /**
     * Size of the keyspace
     * @var int
     */
    private int $length;

    /**
     * Valuse of the keyspace
     * @var string
     */
    private string $keyspace;

    public function __construct(int $length, string $keyspace){
        $this->length = $length;
        $this->keyspace = $keyspace;
    }

    /**
     * Generate a string from the keyspace provided in random order with pseudorandom number generator
     * @throws \RangeException
     * @return string The keyspace randomised
     */
    public function generate_random_keyspace() : string {
        $random_str = [];
        if($this->length < 1){
            throw new \RangeException("Length must be a positive integer");
        }
        
        $max = strlen($this->keyspace) - 1;

        for ($i = 0; $i < $this->length; ++$i){
            $random_str[] = $this->keyspace[random_int(0, $max)];
        }

        return implode('', $random_str);
    }
}