{extends file="page_form.tpl"}
{assign var="pageTitle" value="Editing: {$defaultTitle|default:""}"}
{assign var="submitText" value="Edit"}

{block name=formContentsHtml}
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Title</span>
        </div>
        <input type="text" class="form-control" name="title" aria-label="Title"
            data-default-value="{$defaultTitle|default:""}" value="{$title|default:""}" required>
    </div>

    <div class="form-group">
        <small class="text-muted">Date</small>
        <div class="form-row">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Month</span>
                </div>

                {$monthSelectionHtml}
            </div>

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Day</span>
                </div>
                <input type="number" class="form-control" min="0" max="31" name="day" aria-label="Day"
                    data-default-value="{$defaultDay|default:""}" value="{$day|default:""}">
            </div>

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Year</span>
                </div>
                <input type="number" class="form-control" min="1000" name="year" aria-label="Year"
                    data-default-value="{$defaultYear|default:""}" value="{$year|default:""}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea class="form-control" id="notes" name="notes" rows="10"
            data-default-value="{$defaultNotes|default:""}">{$notes|default:""}</textarea>
    </div>

    {$petitionStatusOptionsHtml}

    <div class="form-row">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Box</span>
            </div>
            <input type="number" class="form-control" min="1" max="99" name="box" aria-label="Box"
                data-default-value="{$defaultBox|default:""}" value="{$box|default:""}">
        </div>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Folder</span>
            </div>
            <input type="text" class="form-control text-center" maxlength="5" name="folder" aria-label="Folder"
                data-default-value="{$defaultFolder|default:""}" value="{$folder|default:""}">
        </div>
    </div>

    {$landSurveyInputsHtml}

    <button type="button" id="btnAddLandSurveyInputs" class="btn btn-secondary">+</button>

    <input type="hidden" id="id" name="id" value="{$id}">
    <input type="hidden" id="landSurveys" name="landSurveys" value="{$landSurveys|default:""}">
{/block}