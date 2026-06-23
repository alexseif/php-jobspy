<?php

declare(strict_types=1);

namespace Freeworld\PhpJobspy\DTO;

/**
 * A standard, immutable representation of a scraped Job Post.
 * Replaces associative arrays to guarantee schema structure.
 */
readonly class JobPostDTO
{
    public function __construct(
        public string $site,
        public string $title,
        public string $company,
        public string $company_url,
        public string $job_url,
        public string $location,
        public bool $is_remote,
        public string $description,
        public ?string $job_type = null,
        public ?string $interval = null,
        public int|float|null $min_amount = null,
        public int|float|null $max_amount = null,
        public ?string $currency = null,
        public ?string $date_posted = null
    ) {
    }

    /**
     * Converts the DTO to an associative array for serialization.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
