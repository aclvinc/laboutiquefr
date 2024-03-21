<?php

namespace App\Tools;

use App\Entity\Category;

class Search 
{

    public ?string $string = ""; 

    /**
     * @var Category[]
     */
    public $categories = [] ; 
/*
    public function getString(): ?string
    {
        return $this->string;
    }

    public function setString(string $value): static
    {
        if ($value == null) {
            $value = "";
        }
        $this->string = $value;
        return $this;
    }
*/
}