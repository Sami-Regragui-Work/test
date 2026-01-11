<?php

require_once __DIR__ . '/../../php/error-handling-with-ai.php';

$dbName = $db;
$dbLink = DATABASE::openDB()->getPdo();

// gender ENUM options for patients.gender
$genderOptions = [];

$enumQuery = <<<SQL
SELECT COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = "{$dbName}"
    AND TABLE_NAME = 'patients'
    AND COLUMN_NAME = 'gender'
SQL;
$enumRes = $dbLink->query($enumQuery);
if ($enumRes && ($enumRow = $enumRes->fetch())) {
    $enumStr = $enumRow['COLUMN_TYPE'];
    if (str_starts_with($enumStr, "enum(")) {
        $enumTypesStr = substr($enumStr, 5, -1);
        $genderOptions = array_map(
            fn($option) => htmlspecialchars(trim($option, " '\""), ENT_QUOTES, "UTF-8"), // trim spaces and single quotes or double quotes
            explode(',', $enumTypesStr)
        );
    }
}


// departments list for doctors.department_id select
$departments = [];
$depQuery = <<<SQL
SELECT department_id, department_name 
FROM departments 
ORDER BY department_name
SQL;
$depRes = $dbLink->query($depQuery);
if ($depRes) {
    $departments = $depRes->fetchAll();
}

// Container for all add forms
$addForms = [];

// Patients add form
ob_start();
?>
<section
    id="add-form-patients"
    class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow-y-auto modal--scroll"
    data-add-form="patients">
    <!-- Header -->
    <header class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Add new patient
        </h3>
        <button
            type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
            data-add-cancel="patients">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0
             111.414 1.414L11.414 10l4.293 4.293a1 1 0
             01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0
             01-1.414-1.414L8.586 10 4.293 5.707a1 1 0
             010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </header>

    <!-- Body -->
    <div class="p-6 space-y-6">
        <form id="add-form" data-form="patients" autocomplete="off" data-table="patients">
            <input type="hidden" name="patient_id" aria-hidden="true" />

            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        First name
                    </label>
                    <input
                        type="text"
                        name="first_name"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="John"
                        required />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Last name
                    </label>
                    <input
                        type="text"
                        name="last_name"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Doe"
                        required />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Gender
                    </label>
                    <select
                        name="gender"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option value="" disabled selected>Select gender</option>
                        <?php foreach ($genderOptions as $opt): ?>
                            <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars(ucfirst($opt), ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Date of birth
                    </label>
                    <input
                        type="date"
                        name="date_of_birth"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Phone number
                    </label>
                    <input
                        type="text"
                        name="phone_number"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="+212 6 12 34 56 78"
                        required />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="patient@example.com" />
                </div>

                <div class="col-span-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Address
                    </label>
                    <textarea
                        name="address"
                        rows="3"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border
                   border-gray-300 focus:ring-primary-500 focus:border-primary-500
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Street, city, ZIP"></textarea>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700 flex justify-end gap-2">
        <button
            type="button"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-950"
            data-add-cancel="patients">
            Cancel
        </button>
        <button
            type="submit"
            form="add-form"
            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
             font-medium rounded-lg text-sm px-5 py-2.5 text-center
             dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
            data-add-submit="patients">
            Add patient
        </button>
    </footer>
</section>

<?php
$addForms['patients'] = ob_get_clean();


// Departments add form
ob_start();
?>
<section
    id="add-form-departments"
    class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow-y-auto modal--scroll"
    data-add-form="departments">
    <!-- Header -->
    <header class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Add new department
        </h3>
        <button
            type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
            data-add-cancel="departments">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0
             111.414 1.414L11.414 10l4.293 4.293a1 1 0
             01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0
             01-1.414-1.414L8.586 10 4.293 5.707a1 1 0
             010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </header>

    <!-- Body -->
    <div class="p-6 space-y-6">
        <form id="add-form" data-form="departments" autocomplete="off" data-table="departments">
            <!-- department_id (hidden) -->
            <input type="hidden" name="department_id" aria-hidden="true" />

            <div class="grid grid-cols-6 gap-6">
                <!-- name -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Department name
                    </label>
                    <input
                        type="text"
                        name="department_name"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Cardiology"
                        required />
                </div>

                <!-- location -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Location
                    </label>
                    <input
                        type="text"
                        name="location"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Building A, Floor 2" />
                </div>

                <!-- description -->
                <div class="col-span-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Description
                    </label>
                    <textarea
                        name="description"
                        rows="3"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border
                   border-gray-300 focus:ring-primary-500 focus:border-primary-500
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Short description of the department"></textarea>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700 flex justify-end gap-2">
        <button
            type="button"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-950"
            data-add-cancel="departments">
            Cancel
        </button>
        <button
            type="submit"
            form="add-form"
            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
             font-medium rounded-lg text-sm px-5 py-2.5 text-center
             dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
            data-add-submit="departments">
            Add department
        </button>
    </footer>
</section>

<?php
$addForms['departments'] = ob_get_clean();


// Doctors add form
ob_start();
?>
<section
    id="add-form-doctors"
    class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow-y-auto modal--scroll"
    data-add-form="doctors">
    <!-- Header -->
    <header class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Add new doctor
        </h3>
        <button
            type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
            data-add-cancel="doctors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0
             111.414 1.414L11.414 10l4.293 4.293a1 1 0
             01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0
             01-1.414-1.414L8.586 10 4.293 5.707a1 1 0
             010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </header>

    <!-- Body -->
    <div class="p-6 space-y-6">
        <form id="add-form" data-form="doctors" autocomplete="off" data-table="doctors">
            <!-- doctor_id (hidden) -->
            <input type="hidden" name="doctor_id" aria-hidden="true" />

            <div class="grid grid-cols-6 gap-6">
                <!-- first_name -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        First name
                    </label>
                    <input
                        type="text"
                        name="first_name"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="John"
                        required />
                </div>

                <!-- last_name -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Last name
                    </label>
                    <input
                        type="text"
                        name="last_name"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Doe"
                        required />
                </div>

                <!-- specialization -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Specialization
                    </label>
                    <input
                        type="text"
                        name="specialization"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Cardiology, Pediatrics, ..."
                        required />
                </div>

                <!-- phone_number -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Phone number
                    </label>
                    <input
                        type="text"
                        name="phone_number"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="+212 6 12 34 56 78"
                        required />
                </div>

                <!-- email -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="doctor@example.com" />
                </div>

                <!-- department_id -->
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Department
                    </label>
                    <select
                        name="department_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                   rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                   dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option value="" disabled selected>Select department</option>
                        <?php foreach ($departments as $dep): ?>
                            <option value="<?= (int)$dep['department_id'] ?>">
                                <?= (int)$dep['department_id'] ?> :
                                <?= htmlspecialchars($dep['department_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700 flex justify-end gap-2">
        <button
            type="button"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-950"
            data-add-cancel="doctors">
            Cancel
        </button>
        <button
            type="submit"
            form="add-form"
            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
             font-medium rounded-lg text-sm px-5 py-2.5 text-center
             dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
            data-add-submit="doctors">
            Add doctor
        </button>
    </footer>
</section>

<?php
$addForms['doctors'] = ob_get_clean();
