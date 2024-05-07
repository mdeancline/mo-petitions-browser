{extends file="page.tpl"}
{assign var="pageTitle" value="Results"}

{block name="containerHtml"}
    <div class="row result-controls">
        <div class="col">
            {$resultsAmountHtml}
        </div>

        <div class="col">
            <div class="result-actions">
                <div class="result-views">
                    <button type="button" id="listViewBtn" class="btn">
                    </button>

                    <button type="button" id="gridViewBtn" class="btn">
                    </button>
                </div>
            </div>
        </div>
    </div>

    {$resultsHtml}

    <div class="row">
        {$pageNavHtml}
    </div>
{/block}

{block name="scriptHtml" append}
    <script src="/scripts/js/results.js"></script>
{/block}