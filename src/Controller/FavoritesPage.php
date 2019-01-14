<?php

namespace Drupal\favorites_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Unicode;

/**
 * Provides route response from favorites_list module.
 */

class FavoritesPage extends ControllerBase {

    /**
     * Returns a page with a query of all series related to favourites series of current user.
     *
     * @return array
     *	a simple renderable array.
     */

    public function PageController() {

        $connection = \Drupal::database();
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;

        /**
         * Get the ID's of nodes.
         */

        $nids = [];
        $result = $connection->query("SELECT * FROM favorites WHERE uid = :uid", array('uid' => $uid))->fetchAll();
        if (count($result) > 0) {
            foreach ($result as $node) {
                $nids[] = $node->nid;
            }

            $node_type = 'node';
            $entities = \Drupal::entityTypeManager()->getStorage($node_type)->loadByProperties(['nid' => $nids, 'status' => 1]);
            $output = [];
            
            foreach ($entities as $entity) {
                // $output .=  '<h4><li>' . $entity->getTitle() . '</li></h4>';
                $remove = Url::fromUserInput('/user/favourites/' . $entity->id() . '/remove');
                $output[] = ['title' => link::fromTextAndUrl($entity->getTitle(), $entity->toUrl()), 'link' => Link::fromTextAndUrl(' Delete  ', $remove)
                ];
            }

            $encabezado = [
            	'title' => t('Title'), 
            	'title2' => t('Action'),
            ];

            return [
            	'#type' => 'table', 
            	'#header' => $encabezado, 
            	'#rows' => $output, 
            	'#title' => 'Favorites series',
            ];
        }

        return [
        	'#markup' => '<h2 class="text-danger"> There are no favorite series </h2>', 
        	'#title' => 'Favorites'
        ];
    }

    /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request) {
    $results = [];

    // Get the typed string from the URL, if it exists.
    if ($input = $request->query->get('q')) {
      $typed_string = Tags::explode($input);
      $typed_string = Unicode::strtolower(array_pop($typed_string));

      // Load the icon data so we can check for a valid icon.
      $iconData = fontawesome_extract_icons();

      // Check each icon to see if it starts with the typed string.
      foreach ($iconData as $icon => $data) {
        // If the string is found.
        if (strpos($icon, $typed_string) === 0) {
          $results[] = [
            'value' => $icon,
            'label' => $this->t('<i class=":prefix fa-:icon fa-fw fa-2x"></i> :icon', [
              ':prefix' => fontawesome_determine_prefix($iconData[$icon]['styles']),
              ':icon' => $icon,
            ]),
          ];
        }
      }
    }

    return new JsonResponse($results);
  }
}