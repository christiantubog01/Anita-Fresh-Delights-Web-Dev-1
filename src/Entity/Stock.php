<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $stock_description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $min_quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $max_quantity = null;

    #[ORM\Column(length: 20)]
    private ?string $unit = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStockDescription(): ?string
    {
        return $this->stock_description;
    }

    public function setStockDescription(string $stock_description): static
    {
        $this->stock_description = $stock_description;

        return $this;
    }

    public function getMinQuantity(): ?string
    {
        return $this->min_quantity;
    }

    public function setMinQuantity(string $min_quantity): static
    {
        $this->min_quantity = $min_quantity;

        return $this;
    }

    public function getMaxQuantity(): ?string
    {
        return $this->max_quantity;
    }

    public function setMaxQuantity(string $max_quantity): static
    {
        $this->max_quantity = $max_quantity;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTime $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    // added for date
    public function __construct()
    {
        $this->date_created = new \DateTime();
    }
}
