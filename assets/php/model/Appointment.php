<?php

enum Status: string
{
    case SCHEDULED = 'scheduled';
    case DONE = 'done';
    case CANCELLED = 'cancelled';
}

class Appointment
{
    private ?int $id;
    private ?DateTime $date;
    private ?DateTime $time;
    private ?Doctor $doctor;
    private ?Patient $patient;
    private string $reason;
    private ?Status $status;
    private readonly DateTime $crDate;

    public function __construct(
        ?int $id = null,
        ?DateTime $date = null,
        ?DateTime $time = null,
        ?Doctor $doctor = null,
        ?Patient $patient = null,
        string $reason = "",
        ?Status $status = null
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->time = $time;
        $this->doctor = $doctor;
        $this->patient = $patient;
        $this->reason = $reason;
        $this->status = $status;
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
    public function getTime(): ?DateTime
    {
        return $this->time;
    }
    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }
    public function getPatient(): ?Patient
    {
        return $this->patient;
    }
    public function getReason(): string
    {
        return $this->reason;
    }
    public function getStatus(): ?Status
    {
        return $this->status;
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
    public function setTime(?DateTime $time): void
    {
        $this->time = $time;
    }
    public function setPatient(?Patient $patient): void
    {
        $this->patient = $patient;
    }
    public function setDoctor(?Doctor $doctor): void
    {
        $this->doctor = $doctor;
    }
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }
}
