<?php

namespace Drupal\favorites_list\services;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MyPlaylistHelper {

    /**
     * @var Drupal\Core\Database\Connection $connection
     */

    protected $connection;

    /**
     * @var Drupal\Core\Session\AccountInterface $account
     */

    protected $account;

    public function __construct(Connection $connection, AccountInterface $account) {

        $this->connection = $connection;
        $this->account = $account;
    }

    public static function create(ContainerInterface $container) {

        return new static (
            
            $container->get('database'), 
            $container->get('current_user')
        );
    }

    public function Save($nids) {

        /**
         * get connection to database and load the current user.
         */
    
        foreach ($nids as $nid) {
            $result = $this->connection->merge('favorites')
            ->Fields(['uid' => $this->account->id(), 'nid' => $nid, ])
            ->key(['nid' => $nid])->execute();
        }
        
        
    }
}