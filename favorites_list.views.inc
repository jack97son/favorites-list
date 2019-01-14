<?php

/**
 * @file
 * Provide views data for dblog.module.
 */

/**
 * Implements hook_views_data().
 */
function favorites_list_views_data() {
  $data = [];

  $data['favorites'] = [];
  $data['favorites']['table'] = [];
  $data['favorites']['table']['group'] = t('favorites');

  $data['favorites']['table']['base'] = 
  ['field' => 'id',
   'title' => t('Favorites View'), 
   'help' => t('Favorites View created'), 
   'weight' => -10 ,
   ]; 


  $data['favorites']['id'] = [
    'title' => t('ID'),
    'help' => t('Unique favorites event ID.'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['favorites']['uid'] = [
    'title' => t('UID'),
    'help' => t('The user ID of the user on which the log entry was written.'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'relationship' => [
      'title' => t('User'),
      'help' => t('The user on which the log entry as written.'),
      'base' => 'users_field_data',
      'base field' => 'uid',
      'id' => 'standard',
    ],
  ];
  
  $data['favorites']['nid'] = [
    'title' => t('NID'),
    'help' => t('The favorites page node.'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],

    'relationship' => [
      'base' => 'node_field_data',
      'id' => 'standard',
      'label' => t('favorites Page'),
    ],
  ];

  return $data;
}
