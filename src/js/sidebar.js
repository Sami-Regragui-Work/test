import "/node_modules/jquery/dist/jquery.min.js";
import { reRunCrud } from "./crud.js";
import { dashboardStart } from "./dashboard.js";

const sidebar = $("#sidebar");
const contentArticle = $("#dynamic-content");
const modalSection = $("#hidden-modal");
const modalInnerWrapper = modalSection.children("div");

const sections = {
    dashboard: {
        url: "src/php/section/dashboard.php",
        hasTable: false,
    },
    patients: {
        url: "src/php/section/dynamicTable.php",
        hasTable: true,
    },
    doctors: {
        url: "src/php/section/dynamicTable.php",
        hasTable: true,
    },
    departments: {
        url: "src/php/section/dynamicTable.php",
        hasTable: true,
    },
    error: {
        url: "src/php/section/dynamicTable.php",
        hasTable: true,
    },
};

function loadSection(sectionName) {
    const sectionObj = sections[sectionName] || sections.error;

    const content = {}; // dataObj for ajax
    if (sectionObj.hasTable) content.tableName = sectionName;

    $.ajax({
        url: sectionObj.url,
        method: "GET",
        dataType: "json",
        data: content,
        success: (res) => {
            contentArticle.html(res.content);
            res.modal
                ? modalInnerWrapper.html(res.modal)
                : modalInnerWrapper.empty();

            const url = new URL(window.location.href);
            url.searchParams.set("section", sectionName);
            window.history.pushState({}, "", url.toString());

            sectionName == "dashboard" ? dashboardStart() : reRunCrud();
        },
        error: (xhr, status, err) => {
            console.error("AJAX Error:", xhr.status, xhr.responseText);

            // Handle JSON error responses
            if (xhr.responseJSON?.content) {
                contentArticle.html(xhr.responseJSON.content);
                return;
            }

            // Handle raw HTML 500 errors
            contentArticle.html(`
        <div class="p-6 bg-red-50 border-2 border-red-200 rounded-lg">
            <h2 class="text-xl font-bold text-red-800 mb-2">Load Failed</h2>
            <p class="text-red-600 mb-4">Status: ${xhr.status} ${status}</p>
            <pre class="bg-red-100 p-3 rounded text-sm overflow-auto max-h-40">${xhr.responseText.slice(0, 1000)}</pre>
            <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Retry
            </button>
        </div>
    `);
        },
    });
}

function navigate(e) {
    e.preventDefault();
    const anchor = $(this);
    const sectionName = anchor.data("section");
    if (sectionName) loadSection(sectionName);
}

function sidebarStart() {
    sidebar.on("click", "a[data-section]", navigate);
    const params = new URLSearchParams(window.location.search);
    loadSection(params.get("section") || "dashboard");
}

sidebarStart();
