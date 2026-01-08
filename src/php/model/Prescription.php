<?php

class Prescription
{
    private ?int $id;
    private ?DateTime $date;
    private ?Doctor $doctor;
    private ?Patient $patient;
    private ?Medication $medication;
    private string $dosInst;
    private readonly DateTime $crDate;

    public function __construct(
        ?int $id = null,
        ?DateTime $date = null,
        ?Doctor $doctor = null,
        ?Patient $patient = null,
        ?Medication $medication = null,
        string $dosInst = ""
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->doctor = $doctor;
        $this->patient = $patient;
        $this->medication = $medication;
        $this->dosInst = $dosInst;
        $this->crDate = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDate(): ?DateTime
    {
        return $this->date;
    }
    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }
    public function getPatient(): ?Patient
    {
        return $this->patient;
    }
    public function getMedication(): ?Medication
    {
        return $this->medication;
    }
    public function getDosInst(): string
    {
        return $this->dosInst;
    }
    public function getCrDate(): DateTime
    {
        return $this->crDate;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }
    public function setPatient(?Patient $patient): void
    {
        $this->patient = $patient;
    }
    public function setDoctor(?Doctor $doctor): void
    {
        $this->doctor = $doctor;
    }
    public function setMedication(?Medication $medication): void
    {
        $this->medication = $medication;
    }
    public function setDosInst(string $dosInst): void
    {
        $this->dosInst = $dosInst;
    }
}
