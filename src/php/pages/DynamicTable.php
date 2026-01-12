<?php
require_once __DIR__ . '/../../php/error-handling-with-ai.php';
require_once __DIR__ . '/../config/DataBase.php';
$repoFiles = glob(__DIR__ . '/../repositories/*.php');
foreach ($repoFiles as $file) {
    $className = basename($file, '.php');
    if ($className === 'BaseRepository' || $className === 'UserRepository') {
        continue;
    }
    require_once $file;
}
$modelFiles = glob(__DIR__ . '/../model/*.php');
foreach ($modelFiles as $file) {
    $className = basename($file, '.php');
    if ($className === 'User') {
        continue;
    }
    require_once $file;
}

class DynamicTable
{
    private string $tableName;
    private object $model;
    private BaseRepository $repository;
    private array $headers = [];
    private array $data = [];
    public int $totalRecords = 0;

    private array $tableMapping = [
        'users' => ['User', 'UserRepository'],
        'doctors' => ['Doctor', 'DoctorRepository'],
        'patients' => ['Patient', 'PatientRepository'],
        'departments' => ['Department', 'DepartmentRepository'],
        'appointments' => ['Appointment', 'AppointmentRepository'],
        'prescriptions' => ['Prescription', 'PrescriptionRepository'],
        'medications' => ['Medication', 'MedicationRepository'],
    ];

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->initRepository();
    }

    private function initRepository(): void
    {
        if (!array_key_exists($this->tableName, $this->tableMapping)) {
            throw new InvalidArgumentException("Table '{$this->tableName}' not supported");
        }

        [$modelClass, $repoClass] = $this->tableMapping[$this->tableName];

        $this->model = new $modelClass();
        $this->repository = new $repoClass($this->model);
        $this->headers = $this->repository->getHeaders();
    }

    public function loadData(int $limit = 25, int $offset = 0): self
    {
        $this->data = $this->repository->readAll();
        $this->totalRecords = count($this->data);
        return $this;
    }

    public function render(): string
    {
        ob_start();
?>
        <section id="table-<?= htmlspecialchars($this->tableName) ?>"
            class="p-6 space-y-4"
            data-table="<?= htmlspecialchars($this->tableName) ?>">

            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white capitalize">
                        <?= ucfirst($this->tableName) ?>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?= $this->totalRecords ?> total records
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <?php foreach ($this->headers as $header): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium 
                                    text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $header))) ?>
                                </th>
                            <?php endforeach; ?>
                            <th class="px-6 py-3 text-left text-xs font-medium 
                                text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                        <?php if (empty($this->data)): ?>
                            <tr>
                                <td colspan="<?= count($this->headers) + 1 ?>"
                                    class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No records found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($this->data as $index => $obj): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <?php foreach ($this->headers as $header): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            <?= htmlspecialchars($this->getPropertyValue($obj, $header) ?? 'N/A') ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="edit-btn text-indigo-600 hover:text-indigo-900 mr-3 p-1 rounded"
                                            data-id="<?= $obj->getId() ?? $index ?>">
                                            Edit
                                        </button>
                                        <button class="delete-btn text-red-600 hover:text-red-900 p-1 rounded"
                                            data-id="<?= $obj->getId() ?? $index ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
<?php
        return ob_get_clean();
    }

    private function getPropertyValue(object $obj, string $header): ?string
    {
        try {
            $reflection = new ReflectionClass($obj);
            $propName = array_merge([$header => $header], $this->repository->getHeaderToProp())[$header];

            if ($reflection->hasProperty($propName)) {
                $prop = $reflection->getProperty($propName);
                $value = $prop->getValue($obj);

                if ($value instanceof DateTime) return $value->format('Y-m-d H:i');
                if (is_object($value)) return $value->getId();
                if (property_exists($value, 'value')) return $value->value;

                return (string)$value;
            }
        } catch (ReflectionException) {
        }
        return null;
    }
}
