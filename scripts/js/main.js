document.addEventListener("DOMContentLoaded", () => {
    const autoShownModal = document.getElementById("autoShownModal");
    if (autoShownModal != null) {
        const modal = new bootstrap.Modal(autoShownModal);
        modal.show();
    }
});

function createCloseButton(target, ...classList) {
    const closeBtn = document.createElement("div");
    closeBtn.classList.add("btn-close", "m-auto", ...classList);
    closeBtn.ariaLabel = "Close";
    if (target) closeBtn.dataset.bsDismiss = target;

    return closeBtn;
}

function createToastElement(text, style = "primary") {
    const toastElem = document.createElement("div");
    toastElem.classList.add("toast", "align-items-center", `text-bg-${style}`, "border-0");

    const flexContainer = document.createElement("div");
    flexContainer.classList.add("d-flex");

    const toastBody = document.createElement("div");
    toastBody.classList.add("toast-body");
    toastBody.appendChild(document.createTextNode(text));

    flexContainer.appendChild(toastBody);
    flexContainer.appendChild(createCloseButton("toast", "me-2"));
    toastElem.appendChild(flexContainer);
    document.getElementById("toasts").appendChild(toastElem);

    return toastElem;
}

function post(url, body) {
    return fetch(url, {
        method: "POST",
        body: body,
        headers: {
            "Content-type": "application/json; charset=UTF-8"
        }
    });
}
