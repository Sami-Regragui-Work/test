import "/node_modules/jquery/dist/jquery.min.js";

function reRunCrud() {
    const contentArticle = $("#dynamic-content");
    const modalArticle = $("#hidden-modal");
    const form = modalArticle.find("form");
    const tableName = form.data("table");
    const searchInput = $("#search");
    const addSection = modalArticle.find("section");
    const addBtn = modalArticle.find("button[type='submit']");

    let currentRow = null;

    function closeModal() {
        modalArticle.removeClass("flex");
        modalArticle.addClass("hidden");
        $("body").removeClass("body--no-scroll");
        form[0].reset();
        searchInput.removeAttr("disabled");
    }

    function addSubmit(e) {
        e.preventDefault();
        console.log("submit");
        const formContent =
            form.serialize() + `&table=${encodeURIComponent(tableName)}`;

        $.ajax({
            url: "src/php/action/addRow.php",
            method: "POST",
            dataType: "json",
            data: formContent,
            success(res) {
                closeModal();

                const $table = contentArticle.find("table");
                const $tbody = $table.find("tbody");
                $tbody.append(res.row);
            },
            error: (xhr, stat, err) => {
                console.error(
                    "couldn't add the row",
                    xhr.responseText || err || stat
                );
            },
        });
    }

    function editSubmit(e) {
        e.preventDefault();
        const formContent =
            form.serialize() + `&table=${encodeURIComponent(tableName)}`;

        $.ajax({
            url: "src/php/action/editRow.php",
            method: "POST",
            dataType: "json",
            data: formContent,
            success(res) {
                const cells = currentRow.find("td").slice(0, -1); // skip actions
                const fields = form.find("input, select, textarea");

                fields.each((ind, el) => {
                    const val = $(el).val();
                    cells.eq(ind).text(val);
                });
                closeModal();
            },
            error: (xhr, stat, err) => {
                console.error(
                    "couldn't edit the row",
                    xhr.responseText || err || stat
                );
            },
        });
    }

    function fillForm(btn) {
        const rowId = btn.data("id");
        const row = btn.closest("tr");
        currentRow = row;
        const cells = row.find("td").slice(0, -1); // -1: actions

        const fields = form.find("input, select, textarea");

        fields.each((ind, el) => {
            const cellContent = cells.eq(ind).text().trim();
            $(el).val(cellContent);
        });
    }

    function deleteRow(e) {
        e.preventDefault();

        const btn = $(this);
        const table = btn.data("table");
        const id = btn.data("id");
        const row = btn.closest("tr");

        // console.log($("thead th").first().text().trim());

        const idName = $("thead th").first().text().trim();

        $.ajax({
            url: "src/php/action/deleteRow.php",
            method: "POST",
            dataType: "json",
            data: {
                table: table,
                [idName]: id,
            },
            success(res) {
                console.log(res.debug);
                row.remove();
            },
            error: (xhr, stat, err) => {
                console.error(
                    "couldn't delete the row",
                    xhr.responseText || err || stat
                );
            },
        });
    }

    function openModal() {
        console.log("modal is open");
        const btn = $(this);
        if (btn.data("role") == "table-edit") {
            const title = addSection.data("add-form").slice(0, -1);
            addSection.find("h3").text(`Edit ${title}`);
            fillForm(btn);
            addBtn.text(`Edit ${title}`);
            form.off("submit").on("submit", editSubmit);
        } else {
            const title = addSection.data("add-form").slice(0, -1);
            addSection.find("h3").text(`Add new ${title}`);
            addBtn.text(`Add ${title}`);
            form.off("submit").on("submit", addSubmit);
        }
        modalArticle.removeClass("hidden");
        modalArticle.addClass("flex");
        $("body").addClass("body--no-scroll");
        searchInput.prop("disabled", true);
    }

    contentArticle.on(
        "click",
        "[data-role='table-add'], [data-role='table-edit']",
        openModal
    );
    contentArticle.on("click", "[data-role='table-delete']", deleteRow);
    modalArticle.on("click", "[data-add-cancel]", closeModal);
}

// reRunCrud();

export { reRunCrud };
