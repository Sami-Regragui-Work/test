<?php
require_once __DIR__ . '/../config/DataBase.php';

class Dashboard
{
    private array $tables = [
        'users',
        'doctors',
        'patients',
        'departments',
        'appointments',
        'prescriptions',
        'medications'
    ];
    private array $stats = [];

    public function loadStats(): self
    {
        $db = DataBase::openDB();
        foreach ($this->tables as $table) {
            $query = "SELECT COUNT(*) AS rowsCount FROM {$table}";
            $stmt = $db->getPdo()->query($query);
            $this->stats[$table] = $stmt ? (int) $stmt->fetch()['rowsCount'] : 0;
        }
        return $this;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function render(): string
    {
        $stats = $this->stats;
        $tables = $this->tables; // Same array everywhere
        $total = array_sum($stats);

        $colors = [
            'users' => 'dark:text-[#3b82f6]',
            'doctors' => 'dark:text-[#22c55e]',
            'patients' => 'dark:text-[#eab308]',
            'departments' => 'dark:text-[#ec4899]',
            'appointments' => 'dark:text-[#f97316]',
            'prescriptions' => 'dark:text-[#8b5cf6]',
            'medications' => 'dark:text-[#6b7280]'
        ];

        ob_start();
?>
        <section id="dashboard" class="p-4 space-y-6" data-stats='<?= json_encode($stats) ?>'>
            <header>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Overview of rows across clinic tables (<?= $total ?> total).
                </p>
            </header>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 [&>div]:overflow-hidden">
                <?php foreach ($tables as $table):
                    $color = $colors[$table] ?? 'dark:text-[#6b7280]';
                ?>
                    <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 capitalize"><?= ucfirst($table) ?></h2>
                        <p class="mt-2 text-2xl font-semibold text-gray-900 <?= $color ?>"><?= $stats[$table] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800 h-64">
                <h2 class="mb-4 text-sm font-medium text-gray-500 dark:text-gray-400">All Tables Overview</h2>
                <canvas id="chart" height="120"></canvas>
            </div>
        </section>
<?php
        return ob_get_clean();
    }
}
