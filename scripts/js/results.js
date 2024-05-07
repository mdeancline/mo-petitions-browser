const resultComponentsContainer = document.getElementById("results");
if ("resultsView" in localStorage)
    resultComponentsContainer.classList.add(localStorage.getItem("resultsView"));

attachResultsViewHandlers();
attachDeletePetitionHandlers();

function attachResultsViewHandlers() {
    const listViewClass = "list-view";
    const gridViewClass = "grid-view";

    const rowViewBtn = document.getElementById("listViewBtn");
    rowViewBtn.addEventListener("click", function () {
        resultComponentsContainer.classList.remove(gridViewClass);
        resultComponentsContainer.classList.add(listViewClass);

        localStorage.setItem("resultsView", listViewClass);
    });

    const gridViewBtn = document.getElementById("gridViewBtn");
    gridViewBtn.addEventListener("click", function () {
        resultComponentsContainer.classList.remove(listViewClass);

        resultComponentsContainer.classList.add(gridViewClass);
        localStorage.setItem("resultsView", gridViewClass);
    });
}

function attachDeletePetitionHandlers() {
    const deletePetitionBtns = document.querySelectorAll("[data-deletion-id]");

    for (const btn of deletePetitionBtns)
        attachDeletePetitionHandler(btn);
}

function attachDeletePetitionHandler(deletePetitionBtn) {
    let deletionResponse;

    const modalId = deletePetitionBtn.dataset.modalId;
    const modalElem = document.getElementById(modalId);

    modalElem.addEventListener("hidden.bs.modal", () => {
        if (deletionResponse) deletionResponse.then(updateElements);
    });

    const deletionModalBtn = document.querySelector(`[data-bs-target='#${modalId}']`);

    deletePetitionBtn.addEventListener("click", () => {
        deletionModalBtn.setAttribute("disabled", "");
        deletionResponse = deletePetition();
    });

    function deletePetition() {
        return post("/scripts/php/deletepetition.php", JSON.stringify({
            id: parseInt(deletePetitionBtn.dataset.deletionId)
        }));
    }

    async function updateElements(response) {
        const result = await response.json();

        const toastStyle = result.success ? "primary" : "danger";
        const toast = new bootstrap.Toast(createToastElement(result.message, toastStyle));
        toast.show();

        const resultsCountElem = document.getElementById("resultsCount");
        let resultsCount = parseInt(resultsCountElem.textContent.replace(/,/g, ""));

        if (!result.error) {
            resultsCountElem.textContent = (--resultsCount).toLocaleString();

            const cardElem = document.getElementById(deletePetitionBtn.dataset.cardId);
            cardElem.remove();

            const resultsOnPage = document.getElementsByClassName("card");

            if (resultsOnPage.length == 0)
                if (resultsCount == 0) window.open("/", "_self");
                else window.location.reload();
        } else {
            deletionResponse = undefined;
            deletionModalBtn.removeAttribute("disabled");
        }
    }
}
