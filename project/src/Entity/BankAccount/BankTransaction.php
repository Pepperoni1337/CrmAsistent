<?php

namespace App\Entity\BankAccount;

use App\Entity\Common\IdTrait;
use App\Repository\BankTransactionRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: BankTransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class BankTransaction
{
    use IdTrait;

    public const ID = 'id';
    public const NAME = 'name';
    public const DATE = 'date';
    public const TYPE = 'type';
    public const AMOUNT = 'amount';
    public const STATUS = 'status';
    public const UPDATED_AT = 'updatedAt';

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: 'string', length: 255, enumType: BankTransactionType::class)]
    private BankTransactionType $type;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'string', length: 255, enumType: BankTransactionStatus::class)]
    private BankTransactionStatus $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $name,
        DateTimeImmutable $date,
        BankTransactionType $type,
        float $amount,
        BankTransactionStatus $status
    ) {
        $this->id = Uuid::v7();
        $this->name = $name;
        $this->date = $date;
        $this->type = $type;
        $this->amount = $amount;
        $this->status = $status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getType(): BankTransactionType
    {
        return $this->type;
    }

    public function setType(BankTransactionType $type): void
    {
        $this->type = $type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getStatus(): BankTransactionStatus
    {
        return $this->status;
    }

    public function setStatus(BankTransactionStatus $status): void
    {
        $this->status = $status;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}