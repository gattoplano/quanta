<?php
namespace Quanta\Qtags;

/**
 * Class FormItemString
 * This class represents a Form Item of type dropdown Select
 */
class FormItemNumber extends FormItemString {
  public $type = 'number';

  function loadAttributes() {
    parent::loadAttributes(); // TODO: Change the autogenerated stub
  }

  /**
   * Make sure the item is a number.
   * TODO: support min / max.
   */
  function validate() {
    $values = $this->getValue();
    foreach ($values as $value) {
      if (!is_numeric($value)) {
        $this->getFormState()->validationError($this->getName(), t('Please input a number'));
      }
    }

    parent::validate();
  }
}
