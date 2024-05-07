{extends file="page.tpl"}

{block name="containerHtml"}
    <div class="row">
        <form class="form" action={$action} method="post">
            {block name="formContentsHtml"}{/block}

            <div class="grid-view">
                <button type="reset" id="btnReset" class="btn btn-primary">Reset</button>
                <button type="submit" id="btnSubmit" class="btn btn-primary">{$submitText}</button>
            </div>
        </form>
    </div>
{/block}

{block name="scriptHtml"}
    <script src="/scripts/js/form.js"></script>
{/block}