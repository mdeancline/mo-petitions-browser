{extends file="page.tpl"}
{assign var="pageTitle" value="Error"}

{block name="containerHtml"}
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error</h4>
        <p>An internal error occured while this page was loading. It's recommended that you report it as soon as possible.
        </p>
    </div>
{/block}