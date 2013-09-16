<?php

/**
 * @file
 * Hook documentation.
 */

use Drupal\Core\Session\AccountInterface;
use Drupal\payment\Entity\PaymentInterface;
use Drupal\payment\Entity\PaymentMethodInterface;
use Drupal\payment\Plugin\payment\status\PaymentStatusInterface;

/**
 * Alters payment status plugins.
 *
 * @param array $definitions
 *   Keys are plugin IDs. Values are plugin definitions.
 */
function hook_payment_status_alter(array &$definitions) {
  // Rename a plugin.
  $definitions['payment_failed']['label'] = 'Something went wrong!';
}

/**
 * Alters payment method plugins.
 *
 * @param array $definitions
 *   Keys are plugin IDs. Values are plugin definitions.
 */
function hook_payment_method_alter(array &$definitions) {
  // Remvove a payment method plugin.
  unset($definitions['foo_plugin_id']);

  // Replace a payment method plugin with another.
  $definitions['foo_plugin_id']['class'] = 'Drupal\foo\FooPaymentMethod';
}

/**
 * Alters line item plugins.
 *
 * @param array $definitions
 *   Keys are plugin IDs. Values are plugin definitions.
 */
function hook_payment_line_item_alter(array &$definitions) {
}

/**
 * Alters payment type plugins.
 *
 * @param array $definitions
 *   Keys are plugin IDs. Values are plugin definitions.
 */
function hook_payment_type_alter(array &$definitions) {
}

/**
 * Responds to a payment status being set.
 *
 * @see Payment::setStatus()
 *
 * @param \Drupal\payment\Entity\PaymentInterface $payment
 * @param \Drupal\payment\Plugin\payment\status\PaymentStatusInterface $previous_status
 *   The status the payment had before the new one was set. This may be
 *   identical to the current/new status.
 *
 * @return NULL
 */
function hook_payment_status_set(PaymentInterface $payment, PaymentStatusInterface $previous_status = NULL) {
  // Notify the site administrator, for instance.
}

/**
 * Executes before the payment type's original context is resumed.
 *
 * @see \Drupal\payment\Plugin\payment\method\Base::resume()
 *
 * @param \Drupal\payment\Entity\PaymentInterface $payment
 */
function hook_payment_type_pre_resume_context(PaymentInterface $payment) {
  if ($payment->getStatus()->isOrHasAncestor('payment_success')) {
    drupal_set_message(t('Your payment was successfully completed.'));
  }
  else {
    drupal_set_message(t('Your payment was not completed.'));
  }
}

/**
 * Checks access for executing a payment.
 *
 * @param \Drupal\payment\Entity\PaymentInterface $payment
 *   $payment->getPaymentMethod() contains the method currently configured, but
 *   NOT the method that $payment should be tested against, which is
 *   $payment_method.
 * @param \Drupal\payment\Entity\PaymentMethodInterface $payment_method
 * @param string $payment_method_brand
 *   See \Drupal\payment\PaymentProcessingInterface for the available brands.
 * @param \Drupal\Core\Session\AccountInterface $account
 *
 * @return boolean
 *   Whether the operation can be performed on the payment.
 *
 * @see \Drupal\payment\Plugin\payment\method\Base::executePaymentAccess()
 * @see \Drupal\payment\Plugin\payment\method\Base::executePaymentAccessEvent()
 */
function hook_payment_pre_execute_access(PaymentInterface $payment, PaymentMethodInterface $payment_method, $payment_method_brand, AccountInterface $account) {}

/**
 * Executes before a payment is executed.
 *
 * @param \Drupal\payment\Entity\PaymentInterface $payment
 *
 * @see \Drupal\payment\Plugin\payment\method\Base::executePayment()
 */
function hook_payment_pre_execute(PaymentInterface $payment) {}
