<?php
if (file_exists('.env')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}