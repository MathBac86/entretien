<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @Vich\Uploadable
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $dateMailSend;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $File;

    /**
     * @Vich\UploadableField(mapping="user_file", fileNameProperty="File")
     * @var File
     */
    private $contractFile;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateMailSend(): ?\DateTimeImmutable
    {
        return $this->dateMailSend;
    }

    public function setDateMailSend(?\DateTimeImmutable $dateMailSend): self
    {
        $this->dateMailSend = $dateMailSend;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->File;
    }

    public function setFile(?string $File): self
    {
        $this->File = $File;

        return $this;
    }

    /**
     * Get the value of contractFile
     * @return  File
     */ 
    public function getContractFile()
    {
        return $this->contractFile;
    }

    /**
     * Set the value of contractFile
     * @param  File  $contractFile
     * @return  self
     */ 
    public function setContractFile(File $contractFile)
    {
        $this->contractFile = $contractFile;

        return $this;
    }
}
