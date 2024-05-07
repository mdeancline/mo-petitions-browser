<!DOCTYPE html>
<html lang="en">

<head>
    {include file="head.tpl"}
</head>

<body>
    <script>
        const isDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
        document.documentElement.setAttribute("data-bs-theme", isDarkMode ? "dark" : "light");
    </script>

    <div id="page-container">
        {block name="containerHtml"}{/block}
    </div>

    <div id="toasts" class="toast-container position-fixed">
    </div>

    <div id="modals">
        {$modalsHtml|default:""}
    </div>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/scripts/js/main.js"></script>
    {block name="scriptHtml"}{/block}
</body>

</html>