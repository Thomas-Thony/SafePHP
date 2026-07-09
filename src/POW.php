<?php

namespace SafePHP;

use RangeException;
use RuntimeException;

/**
 * Handler of "proof of work" (POW)
 */
class POW {

    private int $difficulty;
    private int $maxIterations = 50000000;

    public function __construct(int $difficulty){
        if($difficulty < 1){
            throw new RangeException("The difficulty must be at least of 1 !");
        }

        $this->difficulty = $difficulty;
    }

    public function generateProblem(){
        return bin2hex(random_bytes($this->difficulty));
    }

    public function hashBasedPOW(int $difficulty) : array {
        $nonce = 0;
        $challenge = $this->generateProblem();
        $target = str_repeat("0", $difficulty);

        do {
            if ($nonce >= $this->maxIterations) {
                throw new RuntimeException("No solution found after" . $this->maxIterations . "attempts.");
            }
            $hash = hash("sha256", $challenge . $nonce);
            $nonce++;
        } while (substr($hash, 0, $difficulty) !== $target);

        return [
            "challenge" => $challenge,
            "difficulty" => $difficulty,
            "nonce" => $nonce - 1,
        ];
    }

    public function verifyPOW(string $challenge, int $nonce, int $difficulty) : bool {
        $target = str_repeat("0", $difficulty);
        $hash = hash("sha256", $challenge . $nonce);
        return substr($hash, 0, $difficulty) === $target;
    }
}