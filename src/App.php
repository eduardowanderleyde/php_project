<?php

namespace App;

class App
{
    private string $name;
    
    public function __construct(string $name = 'PHP Project')
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function sayHello(): string
    {
        return "Hello from {$this->name}!";
    }
} 