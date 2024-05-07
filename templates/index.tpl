{extends file="page_form_with_menu.tpl"}
{assign var="pageTitle" value="Search a Petition"}
{assign var="submitText" value="Search"}

{block name="formContentsHtml"}
    <div class="form-description">
        <small class="text-muted">Leave all fields and options blank to search for all petitions. Otherwise, input specific
            information to
            narrow the search.</small>
        <small class="text-muted">All fields marked with a * are searchable with relative values. For example, if you want
            to
            search for all petitions
            pertaining to the 1900s, you would enter "19" in the year field.</small>
    </div>

    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Title*</span>
        </div>
        <input type="text" class="form-control" name="title" aria-label="Title" value="{$title|default:""}">
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
                    value="{$day|default:""}">
            </div>

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Year*</span>
                </div>
                <input type="number" class="form-control" min="1" name="year" aria-label="Year" value="{$year|default:""}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="notes">Notes*</label>
        <textarea class="form-control" id="notes" name="notes" rows="1">{$notes|default:""}</textarea>
    </div>

    {$petitionStatusOptionsHtml}

    <div class="form-row">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Section</span>
            </div>
            <input type="number" class="form-control" min="1" max="99" name="section" aria-label="Section"
                value="{$section|default:""}">
        </div>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Township</span>
            </div>
            <input type="number" class="form-control" min="1" max="99" name="township" aria-label="Township"
                value="{$township|default:""}">
        </div>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Range</span>
            </div>
            <input type="number" class="form-control" min="1" max="99" name="range" aria-label="Range"
                value="{$range|default:""}">
        </div>
    </div>
{/block}