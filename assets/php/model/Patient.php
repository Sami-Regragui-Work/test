<?php

enum Gender: string
{
    case M = 'M';
    case F = 'F';
    case Other = 'Other';
}

class Patient extends User
{
    private ?Gender $gender;
    private ?DateTime $dOB;
    private string $address;

    private array $prescriptions = [];
    private array $appointments = [];

    public function __construct(
        ?int $id = null,
        string $user = "",
        string $email = "",
        string $pass = "",
        string $fName = "",
        string $lName = "",
        string $phone = "",
        ?Gender $gender = null,
        ?DateTime $dOB = null,
        string $address = ""
    ) {
        parent::__construct($id, $user, $email, $pass, $fName, $lName, $phone);
        $this->gender = $gender;
        $this->dOB = $dOB;
        $this->address = $address;
    }

    protected function whichRole(): Role
    {
        return Role::PATIENT;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }
    public function getDOB(): ?DateTime
    {
        return $this->dOB;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function getPrescriptions(): array
    {
        return $this->prescriptions;
    }
    public function getAppointments(): array
    {
        return $this->appointments;
    }

    public function setGender(?Gender $gender): void
    {
        $this->gender = $gender;
    }
    public function setDOB(?DateTime $dOB): void
    {
        $this->dOB = $dOB;
    }
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
    public function setPrescriptions(array $prescriptions): void
    {
        foreach ($prescriptions as $prescription) {
            $this->addPrescription($prescription);
        }
    }
    public function setAppointments(array $appointments): void
    {
        foreach ($appointments as $appointment) {
            $this->addAppointment($appointment);
        }
    }

    public function addPrescription(Prescription $prescription): void
    {
        $this->prescriptions[] = $prescription;
        $prescription->setPatient($this);
    }
    public function addAppointment(Appointment $appointment): void
    {
        $this->appointments[] = $appointment;
        $appointment->setPatient($this);
    }
}
