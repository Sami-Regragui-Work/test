<?php

require __DIR__ . "/User.php";

class Doctor extends User
{
    private string $spec;
    private ?Department $dep;

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
        string $spec = "",
        ?Department $dep = null
    ) {
        parent::__construct(
            $id,
            $user,
            $email,
            $pass,
            $fName,
            $lName,
            $phone
        );
        $this->spec = $spec;
        $this->dep = $dep;
    }

    protected function whichRole(): Role
    {
        return Role::DOCTOR;
    }

    public function getSpec(): string
    {
        return $this->spec;
    }
    public function getDep(): ?Department
    {
        return $this->dep;
    }
    public function getPrescriptions(): array
    {
        return $this->prescriptions;
    }
    public function getAppointments(): array
    {
        return $this->appointments;
    }

    public function setSpec(string $spec): void
    {
        $this->spec = $spec;
    }
    public function setDep(?Department $dep): void
    {
        $this->dep = $dep;
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
        $prescription->setDoctor($this);
    }
    public function addAppointment(Appointment $appointment): void
    {
        $this->appointments[] = $appointment;
        $appointment->setDoctor($this);
    }
}
