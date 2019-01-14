<?php

namespace Drupal\favorites_list\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Drupal\user\Entity\User;

/**
 * Class RemoveForm.
 *
 * @package Drupal\favoritelist_block\Form
 */

class RemoveForm extends ConfirmFormBase {

    /**
     * {@inheritdoc}
     */

    public function getFormId() {
        return 'remove_form';
    }

    public $nid;

    public function getQuestion() {
        //$current_uri = \Drupal::request()->getRequestUri();
        $entity = \Drupal::entityTypeManager()->getStorage('node')->load($this->id);

        return t('You want to delete @nid?', ['@nid' => $entity->getTitle() ]);
    }
    public function getCancelUrl() {
        return new Url('favorites_list.favourites_page');
    }
    public function getDescription() {
        return t('<h2 class="text-danger"> ¿Are you sure?</h2>');
    }

    /**
     * {@inheritdoc}
     */

    public function getConfirmText() {
        return t('Delete');
    }
    /**
     * {@inheritdoc}
     */
    public function getCancelText() {
        return t('<button type="button" class="btn btn-success">Cancel</button>');
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state, $nid = NULL) {

        $this->id = $nid;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */

    public function validateForm(array & $form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */

    public function submitForm(array & $form, FormStateInterface $form_state) {
      
        $user = User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;
        $query = \Drupal::database();
        $query->delete('favorites')->condition('nid', $this->id)->condition('uid', $uid)->execute();
        drupal_set_message("Successfully removed");
        $form_state->setRedirect('favorites_list.favourites_page');
    }
}