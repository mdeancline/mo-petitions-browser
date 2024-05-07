<?php
class MonthSelection extends AbstractOptionSelection implements HTMLDataDrivenMarkup
{
    public function __construct()
    {
        parent::__construct("month");

        $this->withClassList("form-select");
        $this->addOption("N/A");
        for ($m = 1; $m < 13; $m++)
            $this->addOption(date("F", mktime(0, 0, 0, $m, 1, date("Y"))), $m);

        $formData = get_form_data();
        $this->setSelectedOption(intval($formData["main"]["month"] ?? 0));
    }

    public function update(array $requestResult)
    {
        $month = intval($requestResult["data"][0]["month"]) ?? 0;
        $this->setSelectedOption($this->getSelectedOption() == 0 ? $month : $this->getSelectedOption());
        $this->withAttribute("data-default-index", $month);
    }
}
