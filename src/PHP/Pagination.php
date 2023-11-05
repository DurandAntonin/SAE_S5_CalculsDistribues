<?php
namespace PHP;
class Pagination{
    private int $limit;
    private int $offset;

    public function __construct(string $parLimit, string $parOffset)
    {
        $this->limit = $parLimit;
        $this->offset = $parOffset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }
}