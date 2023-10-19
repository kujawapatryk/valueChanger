<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExchangeValue
{
    #[Assert\NotBlank]
    #[Assert\Type("integer")]
public int $first;

    #[Assert\NotBlank]
    #[Assert\Type("integer")]
public int $second;
}