<?php

namespace Drupal\favorites_list\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'favorites_list' formatter.
 *
 * @FieldFormatter(
 *   id = "icons_list",
 *   label = @Translation("custom Field"),
 *   field_types = {
 *     "icons"
 *   }
 * )
 */
class FavoritesListFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the random string.');
    return $summary;
  }
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = [
        '#type' => 'markup',
        '#attached' => [
          'library' => [
            'favorites_list/icons-styles'
          ]
        ],
        '#markup' => '<i class="'.$item->value.' fa-3x"></i>'
      ];
    }

    return $element;
  }


   /**
 * {@inheritdoc}
 */
public static function defaultSettings() {
  return [
    // Declare a setting named 'text_length', with
    // a default value of 'short'
    'text_length' => 'short',
  ] + parent::defaultSettings();

}

}