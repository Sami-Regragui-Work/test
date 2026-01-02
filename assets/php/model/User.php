<?
enum Role: string
{
    case ADMIN = "admin";
    case DOCTOR = "doctor";
    case PATIENT = "patient";
}

abstract class User
{
    private ?int $id;
    private readonly Role $role;
    private string $user;
    private string $email;
    private string $pass;
    private string $fName;
    private string $lName;
    private string $phone;
    private readonly DateTime $crDate;

    public function __construct(
        ?int $id = null,
        string $user = "",
        string $email = "",
        string $pass = "",
        string $fName = "",
        string $lName = "",
        string $phone = "",
    ) {
        $this->role = $this->whichRole();
        $this->id = $id;
        $this->user = $user;
        $this->email = $email;
        $this->pass = $pass;
        $this->fName = $fName;
        $this->lName = $lName;
        $this->phone = $phone;
        $this->crDate = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getRole(): Role
    {
        return $this->role;
    }
    public function getUser(): string
    {
        return $this->user;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPass(): string
    {
        return $this->pass;
    }
    public function getFName(): string
    {
        return $this->fName;
    }
    public function getLName(): string
    {
        return $this->lName;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getCrDate(): DateTime
    {
        return $this->crDate;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setUser(string $user): void
    {
        $this->user = $user;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPass(string $pass): void
    {
        $this->pass = $pass;
    }
    public function setFName(string $fName): void
    {
        $this->fName = $fName;
    }
    public function setLName(string $lName): void
    {
        $this->lName = $lName;
    }
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    protected abstract function whichRole(): Role;
}

// agregation list from one side and obj from the other
// association