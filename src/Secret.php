<?php

namespace SafePHP;

use Dotenv\Dotenv;

/**
 * Manage environnement variables and other secret keys
 */
class Secret {
    /**
     * The path where the .env file is located
     * @var string
     */
    private string $envPath;

    /**
     * Construct a .env file path
     * @param string $anEnvPath The file path where the .env file is located
     */
    public function __construct(string $anEnvPath){
        $this->envPath = $anEnvPath;
    }

    /**
     * Import the .env file
     * @return void
     */
    public function getEnv() {
        $dotenv = Dotenv::createImmutable($this->envPath);
        $dotenv->load();
    }
}