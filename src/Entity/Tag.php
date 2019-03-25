<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tag")
 *
 */
class Tag implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
    * @Assert\Length(max="255")
     * @ORM\Column(type="string", unique=true)
     */
    private $name;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): string
    {
        return $this->name;
    }
    public function __toString(): string
    {
        return $this->name;
    }
}
