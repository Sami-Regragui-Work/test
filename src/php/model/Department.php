<?php

class Department
{
    private ?int $id;
    private string $name;
    private string $location;
    private readonly DateTime $crDate;

    private array $doctors = [];

    public function __construct(
        ?int $id = null,
        string $name = "",
        string $location = ""
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
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
    public function getLocation(): string
    {
        return $this->location;
    }
    public function getCrDate(): DateTime
    {
        return $this->crDate;
    }
    public function getDoctors(): array
    {
        return $this->doctors;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
    public function setDoctors(array $doctors): void
    {
        foreach ($doctors as $doctor) {
            $this->addDoctor($doctor);
        }
    }

    public function addDoctor(Doctor $doctor): void
    {
        $this->doctors[] = $doctor;
        $doctor->setDep($this);
    }
}
