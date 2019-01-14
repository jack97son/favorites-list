<?php
/**
 * @file
 * Contains \Drupal\favorites_list\Form\FavoritesListForm.
 */
namespace Drupal\favorites_list\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\favorites_list\services\MyPlaylistHelper;

class FavoritesListForm extends FormBase {

    protected $entityTypeManager;
    protected $queryFactory;
    protected $playlist;

    /**
     * {@inheritdoc}
     */
    //Esto debe devolver una cadena que sea el ID único de su formulario. Espacio de nombres el ID de formulario basado en el nombre de su módulo.

    public function getFormId() {
        return 'favorites_list_form';
    }

    /**
     * {@inheritdoc}
     */
    /**
     * Constructs a new OpController object.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
     * @param \Drupal\Core\Entity\Query\QueryFactory $queryFactory
     */

    public function __construct(EntityTypeManagerInterface $entityTypeManager, QueryFactory $queryFactory, MyPlaylistHelper $playlist) {

        $this->entityTypeManager = $entityTypeManager;
        $this->queryFactory = $queryFactory;
        $this->playlist = $playlist;
    }

    /**
     * {@inheritdoc}
     */

    public static function create(ContainerInterface $container) {

        return new static (
          $container->get('entity_type.manager'), 
          $container->get('entity.query'), 
          $container->get('favorites_list.say')
        );
    }
    //Esto devuelve una matriz de API de formulario que define cada uno de los elementos de los que está compuesto su formulario.
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['series'] = [
          '#type' => 'fieldset', 
          '#title' => t('<h4>Choose your favorite series:</h4>'), 
          '#attributes' => [
            'id' => 'edit-select-user', 
            'style' => 'width:260px',
          ], 
        ];

        $form['series']['node'] = [
          '#type' => 'select', 
          '#multiple' => TRUE,
          '#attributes' => [
            'id' => 'edit-select-user', 
            'style' => 'width:220px',
          ], 
          '#options' => $this->GetData(), 
        ];

        $form['series']['actions']['#type'] = 'actions';
        $form['series']['actions']['submit'] = [
          '#type' => 'submit', 
          '#value' => $this->t('Save'), 
           '#ajax' => [
            'callback' => '::Ajax',
            'event' => 'click',
            'wrapper' => 'submit',
            'method' => 'append',
            'progress' => [
              'type' => 'throbber',
              'message' => NULL,
          '#button_type' => 'info',
            ],
          ],
        ];

        /**
         * @RenderElement("link");
         */

        $form['link_favourites'] = [
          '#type' => 'link', 
          '#title' => $this->t('<button type="button" class="btn btn-info">★Your favorite series★</button>'),
          '#url' => \Drupal\Core\Url::fromRoute('favorites_list.favourites_page'), 
        ];

        return $form;
    }
    public function GetData() {

        $query = $this->entityTypeManager->getStorage('node');
        $query_result = $query->getQuery()->condition('status', 1)->condition('type', 'serie')->sort('title', 'ASC')->execute();
        $entities = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($query_result);

        foreach ($entities as $entity) {
            $output[$entity->id() ] = $entity->getTitle();
        }
        return $output;
    }

    /**
     * {@inheritdoc}
     */
    
    //los valores recopilados del usuario cuando se envió el formulario están en este punto y podemos asumir que ya se han validado y están listos para que los utilicemos.
    
    public function submitForm(array & $form, FormStateInterface $form_state) {

        $nids = $form_state->getValue('node');
        
        $this->playlist->Save($nids);      
                
    }
}