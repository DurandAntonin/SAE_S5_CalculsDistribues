<?php
namespace PHP;

/**
 * Permet de contrôler l'importation des données retournées par un serveur MySQL par exemple.
 *
 * @version 1.0
 *
 * @deprecated
 */
class Pagination{

    /**
     * @var int $limit Nombre de données que l'on veut récupérer
     */
    private int $limit;

    /**
     * @var int $offset Nombre de données à ignorer
     */
    private int $offset;

    /**
     * Constructeur de la classe
     *
     * @param string $parLimit Nombre de données que l'on veut récupérer
     * @param string $parOffset Nombre de données à ignorer
     *
     * @version 1.0
     */
    public function __construct(string $parLimit, string $parOffset)
    {
        $this->limit = $parLimit;
        $this->offset = $parOffset;
    }

    /**
     * Getter du champ _limit_
     *
     * @return int
     *
     * @version 1.0
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Getter du champ _offset_
     *
     * @return int
     *
     * @version 1.0
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Setter du champ _limit_
     *
     * @param int $limit
     *
     * @return void
     *
     * @version 1.0
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * Setter du champ _offset_
     *
     * @param int $offset
     *
     * @return void
     *
     * @version 1.0
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }
}