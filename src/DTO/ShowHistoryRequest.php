<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ShowHistoryRequest
{
    #[Assert\Choice(choices: ["id", "firstIn", "secondIn", "firstOut", "secondOut", "created_at", "updated_at"], message: "Invalid sorting column.")]
    public string $sort;

    #[Assert\Choice(choices: ["asc", "desc"], message: "Invalid sorting direction.")]
    public string $direction;

    #[Assert\GreaterThan(value: 0, message: "Page number should be greater than 0.")]
    public int $page;

    #[Assert\GreaterThan(value: 0, message: "Limit number should be greater than 0.")]
    public int $limit;
}

