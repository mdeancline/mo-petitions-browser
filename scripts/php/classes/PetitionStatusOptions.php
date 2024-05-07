<?php
class PetitionStatusOptions extends DivisionContainer implements HTMLDataDrivenMarkup
{
    private const STATUSES = ["petitioned", "closed", "vacated", "dedicated"];

    private string $defaultStatus = "";
    private string $selectedStatus = "";
    private array $formChecks = [];

    public function __construct(bool $createAsCheckboxes = false, bool $required = false)
    {
        parent::__construct();
        parent::withClassList("form-row");

        $this->prepareFormChecks($createAsCheckboxes, $required);
    }

    private function prepareFormChecks(bool $createAsCheckboxes, bool $required)
    {
        $formData = get_form_data();

        $type = $createAsCheckboxes ? InputType::CHECKBOX : InputType::RADIO;
        $i = 0;

        foreach (self::STATUSES as $status) {
            $formCheck = (new Input($type))
                ->withClassList("form-check-input")
                ->withName("status" . ($createAsCheckboxes ? ++$i : ""))
                ->withValue($status);

            $statusKey = $createAsCheckboxes ? "status{$i}" : "status";

            if (isset($formData["main"][$statusKey]) && $formData["main"][$statusKey] == $formCheck->getValue()) {
                $this->selectedStatus = $status;

                if ($type == InputType::CHECKBOX)
                    $formCheck->withAttribute("checked");
            }

            $this->formChecks[$status] = $formCheck;

            if ($required) $formCheck->withAttribute("required");
        }
    }

    public function update(array $requestResult)
    {
        $this->defaultStatus = $requestResult["data"][0]["status"];
        $this->selectedStatus = $this->selectedStatus ?: $this->defaultStatus;
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        foreach ($this->formChecks as $status => $formCheck) {
            $labelText = ucwords($status);
            $label = new RawHTML("<label class='form-check-label'>{$labelText}</label>");

            if ($status === $this->selectedStatus)
                $formCheck->withAttribute("checked");

            if ($status == $this->defaultStatus)
                $formCheck->withAttribute("data-reset", "true");

            $this->withChild((new DivisionContainer("form-check"))
                ->withChild($formCheck)
                ->withChild($label));
        }

        return parent::toDOMNode($document);
    }
}
