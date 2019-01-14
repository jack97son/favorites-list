<?php

/**
 * @file
 * Contains \Drupal\article\Plugin\Block\FavoritesListBlock.
 */

namespace Drupal\favorites_list\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "article_block",
 *   admin_label = @Translation("Favorites List block"),
 *   category = @Translation("Blocks")
 * )
 */

class FavoritesListBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */

    public function build() {

        $form = \Drupal::formBuilder()->getForm('Drupal\favorites_list\Form\FavoritesListForm');
        
        return $form;
    }
}