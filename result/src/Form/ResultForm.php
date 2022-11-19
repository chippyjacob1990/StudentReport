<?php

namespace Drupal\result\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the result entity edit forms.
 */
class ResultForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New result %label has been created.', $message_arguments));
      $this->logger('result')->notice('Created new result %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The result %label has been updated.', $message_arguments));
      $this->logger('result')->notice('Updated new result %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.result.canonical', ['result' => $entity->id()]);
  }

}
