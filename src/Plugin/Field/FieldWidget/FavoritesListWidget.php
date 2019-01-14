<?php 

namespace Drupal\favorites_list\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'field_example_text' widget.
 *
 * @FieldWidget(
 *   id = "icon_list",
 *   label = @Translation("custom Field"),
 *   field_types = {
 *     "icons"
 *   }
 * )
 */
class FavoritesListWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += [
      '#type' => 'select',
      '#options' => [
        'icon-soccer-ball' => $this-> t('Soccer'),
        'icon-camera' => $this-> t('Camera'),
        'icon-gamepad' => $this-> t('Gamepad'),
        'icon-dglasses' => $this-> t('Glasses'),
        'icon-transformers' => $this-> t('Transformers'),
        'icon-bomb' => $this-> t('Bomb'),
      ],
      '#default_value' => $value,
      '#element_validate' => [
        [static::class, 'validate'],
      ],
    ];
    return ['value' => $element];
  }

  /**
   * Validate the color text field.
   */
  public static function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) == 0) {
      $form_state->setValueForElement($element, '');
      return;
    }
  }

}
