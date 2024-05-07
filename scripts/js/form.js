const form = document.querySelector("form");

if (form != null) {
    const btnSubmit = document.getElementById("btnSubmit");

    const formDataKey = getFormDataKey();
    const landSurveyInputsSection = document.getElementById("landSurveyInputsSection");
    let landSurveys = extractSavedLandSurveys();

    loadForm();

    function prepareLandSurveysSubmission() {
        const hiddenInput = document.getElementById("landSurveys");

        if (hiddenInput != null) {
            const landSurveysSubmission = [];

            for (i = 0; i < landSurveys.length; i++) {
                const landSurvey = landSurveys[i];

                if (!(Object.values(landSurvey).every(value => value == 0)))
                    landSurveysSubmission.push(landSurvey);
            }

            hiddenInput.setAttribute("value", JSON.stringify(landSurveysSubmission));
        }
    }

    function resetFormData() {
        const closedOnReset = [
            document.getElementById("savedFieldsAlert"),
            document.getElementById("noTagCharactersAlert")
        ];

        for (const alertElement of closedOnReset) {
            if (alertElement != null) {
                const alert = new bootstrap.Alert(alertElement);
                alert.close();
            }
        }

        /**
         * Added a small delay to fix an issue with radio buttons being
         * reverted to the initally selected one before the form was reset
         * after the page reloads
         */
        setTimeout(() => syncFormData({
            landSurveys: landSurveys = extractDefaultLandSurveys()
        }), .1);
    }

    function toggleSubmitButtonAnimation() {
        if (btnSubmit.dataset.animated) {
            delete btnSubmit.dataset.animated;
            btnSubmit.innerHTML = "";
            btnSubmit.textContent = btnSubmit.dataset.text;
        } else {
            btnSubmit.dataset.animated;
            btnSubmit.dataset.text = btnSubmit.textContent;

            btnSubmit.disabled = true;

            const spinner = document.createElement("span");
            spinner.classList.add("spinner-grow", "spinner-grow-sm");
            spinner.role = "status";
            spinner.ariaHidden = "true";
            btnSubmit.textContent = btnSubmit.textContent + "ing...";
            btnSubmit.prepend(spinner);
        }
    }

    function loadForm() {
        form.addEventListener("input", syncAllFormData);

        form.addEventListener("submit", e => {
            toggleSubmitButtonAnimation();
            prepareLandSurveysSubmission();
        });

        form.addEventListener("reset", e => {
            e.preventDefault();

            resetFormData();

            if (landSurveyInputsSection != null) {
                resetLandSurveyInputs();
                window.scrollTo(0, document.body.scrollHeight);
            }

            for (const input of form.getElementsByTagName("input"))
                if (input.name)
                    if (input.type == "radio" || input.type == "checkbox")
                        input.checked = input.dataset.reset ?? false;
                    else if (input.type != "hidden")
                        input.value = input.dataset.defaultValue ?? "";

            for (const textArea of form.getElementsByTagName("textarea"))
                textArea.value = textArea.dataset.defaultValue ?? "";

            for (const selectBox of form.getElementsByTagName("select")) {
                const options = selectBox.getElementsByTagName("option");
                const defaultIndex = selectBox.dataset.defaultIndex ?? 0;

                for (var i = 0, length = options.length; i < length; i++)
                    options[i].selected = i == defaultIndex;
            }
        });

        const btnAddLandSurveyInputs = document.getElementById("btnAddLandSurveyInputs");

        if (btnAddLandSurveyInputs != null && landSurveyInputsSection != null)
            btnAddLandSurveyInputs.addEventListener("click", () => {
                appendLandSurveyInputRows();
                window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });

                landSurveys.push({ section: 0, township: 0, range: 0 });
                syncAllFormData();
            });

        const deleteLandSurveyInputsBtns = document.querySelectorAll(".land-survey-row.closable .btn-close");

        for (const deleteInputRowBtn of deleteLandSurveyInputsBtns)
            attachDeleteInputsHandler(deleteInputRowBtn);

        for (const landSurveyInput of document.querySelectorAll(".land-survey-row input")) {
            const key = landSurveyInput.placeholder.toLowerCase();
            attachLandSurveyInputHandler(key, landSurveyInput);
        }
    }

    function appendLandSurveyInputRows(givenLandSurveys) {
        if (givenLandSurveys && givenLandSurveys.length > 0)
            for (const landSurvey of givenLandSurveys)
                performAppend(landSurvey.section, landSurvey.township, landSurvey.range);
        else
            performAppend();

        function performAppend(section, township, range) {
            const landSurveyInputRow = createLandSurveyInputRow();
            const landSurveyInputGroup = createLandSurveyInputGroup();
            const inputs = createLandSurveyInputs(section, township, range);
            landSurveyInputGroup.replaceChildren(...inputs);
            landSurveyInputRow.appendChild(landSurveyInputGroup);
            landSurveyInputRow.setAttribute("data-index", landSurveys.length);

            landSurveyInputsSection.appendChild(landSurveyInputRow);

            if (landSurveyInputsSection.firstElementChild != landSurveyInputRow) {
                landSurveyInputRow.appendChild(createDeleteInputsBtn());
                landSurveyInputRow.classList.add("closable");
            }
        }

        function createLandSurveyInputRow() {
            const landSurveyInputRow = document.createElement("div");
            landSurveyInputRow.classList.add("land-survey-row");
            return landSurveyInputRow;
        }

        function createLandSurveyInputGroup() {
            const landSurveyInputGroup = document.createElement("div");
            landSurveyInputGroup.classList.add("input-group");
            return landSurveyInputGroup;
        }

        function createDeleteInputsBtn() {
            const deleteInputRowBtn = createCloseButton();
            attachDeleteInputsHandler(deleteInputRowBtn);

            return deleteInputRowBtn;
        }
    }

    function createLandSurveyInputs(section, township, range) {
        const sectionInput = document.createElement("input");
        sectionInput.setAttribute("value", section || "");
        sectionInput.placeholder = "Section";

        const townshipInput = document.createElement("input");
        townshipInput.setAttribute("value", township || "");
        townshipInput.placeholder = "Township";

        const rangeInput = document.createElement("input");
        rangeInput.setAttribute("value", range || "");
        rangeInput.placeholder = "Range";

        const landSurveyInputs = [sectionInput, townshipInput, rangeInput];

        for (i = 0; i < landSurveyInputs.length; i++) {
            const landSurveyInput = landSurveyInputs[i];
            landSurveyInput.type = "number";
            landSurveyInput.classList.add("form-control");

            const label = document.createElement("label");
            label.textContent = landSurveyInput.placeholder;

            const landSurveyInputContainer = document.createElement("div");
            landSurveyInputContainer.classList.add("form-floating");

            landSurveyInputContainer.appendChild(landSurveyInput);
            landSurveyInputContainer.appendChild(label);

            const key = landSurveyInput.placeholder.toLowerCase();
            attachLandSurveyInputHandler(key, landSurveyInput);

            landSurveyInputs[i] = landSurveyInputContainer;
        }

        return landSurveyInputs;
    }

    function resetLandSurveyInputs() {
        landSurveyInputsSection.innerHTML = "";
        const defaults = extractDefaultLandSurveys();

        if (defaults.length > 0)
            appendLandSurveyInputRows(defaults);
        else
            appendLandSurveyInputRows();

        reindexLandSurveyInputRows();
    }

    function attachLandSurveyInputHandler(key, landSurveyInput) {
        landSurveyInput.addEventListener("input", () => {
            const inputRow = landSurveyInput.parentElement.parentElement.parentElement;
            const rowIndex = parseInt(inputRow.getAttribute("data-index"));
            const index = Math.min(rowIndex, landSurveys.length);

            landSurveys[index][key] = parseInt(landSurveyInput.value) || 0;
        });
    }

    function attachDeleteInputsHandler(deleteInputRowBtn) {
        deleteInputRowBtn.addEventListener("click", e => {
            const landSurveyInputRow = e.target.parentElement;
            landSurveyInputRow.classList.add("fade", "hide");
            landSurveyInputRow.addEventListener("transitionend", () => {
                landSurveyInputRow.remove();

                const index = parseInt(landSurveyInputRow.getAttribute("data-index"));
                delete landSurveys[index];
                landSurveys = landSurveys.filter(notEmpty => notEmpty);

                const firstlandSurveyInputRow = document.querySelector(".land-survey-row");

                if (firstlandSurveyInputRow.classList.contains("closable"))
                    firstlandSurveyInputRow.querySelector(".btn-close").remove();

                reindexLandSurveyInputRows();
                syncAllFormData();
            });
        });
    }

    async function reindexLandSurveyInputRows() {
        let lastPause = Date.now();

        const landSurveyInputRows = document.getElementsByClassName("land-survey-row");

        for (i = 0; i < landSurveyInputRows.length; i++) {
            await pauseIfLongTime();

            const landSurveyInputRow = landSurveyInputRows[i];
            landSurveyInputRow.setAttribute("data-index", i);
        }

        async function pauseIfLongTime() {
            if ((Date.now() - 17) > lastPause) {
                lastPause = Date.now();
                await pause();
            }

            function pause() {
                return new Promise(resolve => setTimeout(resolve));
            }
        }
    }

    function extractDefaultLandSurveys() {
        const defaultsJson = (landSurveyInputsSection
            ? landSurveyInputsSection.getAttribute("data-defaults")
            : "[]") || "[]";
        const defaultLandSurveys = JSON.parse(defaultsJson);

        if (!defaultLandSurveys.length || defaultLandSurveys.length == 0)
            defaultLandSurveys.push({ section: 0, township: 0, range: 0 });
        else
            for (i = 0; i < defaultLandSurveys.length; i++)
                if (typeof defaultLandSurveys[i] === "string")
                    defaultLandSurveys[i] = JSON.parse(defaultLandSurveys[i]);

        return defaultLandSurveys;
    }

    function extractSavedLandSurveys() {
        const savedJson = (landSurveyInputsSection
            ? landSurveyInputsSection.getAttribute("data-saved")
            : "[]") || "[]";
        const savedLandSurveys = JSON.parse(savedJson);

        if (!savedLandSurveys.length || savedLandSurveys.length == 0)
            savedLandSurveys.push({ section: 0, township: 0, range: 0 });
        else
            for (i = 0; i < savedLandSurveys.length; i++)
                if (typeof savedLandSurveys[i] === "string")
                    savedLandSurveys[i] = JSON.parse(savedLandSurveys[i]);

        return savedLandSurveys;
    }

    async function syncAllFormData() {
        await syncFormData({ landSurveys: landSurveys })
    }

    async function syncFormData(extraFormData) {
        const sanitizedFormData = sanitizeFormData(Object.fromEntries(new FormData(form)), extraFormData);
        await post("/scripts/php/syncformdata.php", JSON.stringify(sanitizedFormData));

        saveExtraFormData(extraFormData);

        function sanitizeFormData(mainFormData, extraFormData) {
            for (const key of Object.keys(extraFormData))
                delete mainFormData[key];

            Object.keys(mainFormData).forEach(key => mainFormData[key] === undefined && delete mainFormData[key])

            return {
                main: mainFormData,
                extras: extraFormData
            }
        }
    }

    function saveExtraFormData(extraFormData) {
        localStorage.setItem(formDataKey, JSON.stringify(extraFormData));
    }

    function getExtraFormData() {
        const extraFormDataJson = localStorage.getItem(formDataKey) ?? "{}";
        return JSON.parse(extraFormDataJson);
    }

    function getFormDataKey(url) {
        return `formData-${btoa(url ?? window.location.href)}`;
    }
}
