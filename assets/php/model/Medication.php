<?php

class Medication
{
    private ?int $id;
    private string $name;
    private string $instruct;
    private ?Prescription $prescription;
    private readonly DateTime $crDate;



    public function __construct(
        ?int $id = null,
        string $name = "",
        string $instruct = "",
        ?Prescription $prescription = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->instruct = $instruct;
        $this->prescription = $prescription;
        $this->crDate = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getInstruct(): string
    {
        return $this->instruct;
    }
    public function getPrescription(): ?Prescription
    {
        return $this->prescription;
    }
    public function getCrDate(): DateTime
    {
        return $this->crDate;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setInstruct(string $instruct): void
    {
        $this->instruct = $instruct;
    }
    public function setPrescription(?Prescription $prescription): void
    {
        $this->prescription = $prescription;
    }
}
