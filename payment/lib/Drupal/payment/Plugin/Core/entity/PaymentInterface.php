<?php

/**
 * @file
 * Definition of Drupal\payment\Plugin\Core\entity\PaymentInterface.
 */

namespace Drupal\payment\Plugin\Core\entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Executable\ExecutableInterface;
use Drupal\payment\Plugin\Core\entity\PaymentMethodInterface;
use Drupal\payment\Plugin\payment\context\PaymentContextInterface;
use Drupal\payment\Plugin\payment\line_item\PaymentLineItemInterface;
use Drupal\payment\Plugin\payment\status\PaymentStatusInterface;

/**
 * Defines a payment entity type .
 */
interface PaymentInterface extends EntityInterface, ExecutableInterface {

  /**
   * Sets the machine name of the context that created this Payment, such as a
   * payment form, or a module.
   *
   * @param \Drupal\payment\Plugin\payment\context\PaymentContextInterface $context
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setPaymentContext(PaymentContextInterface $context);

  /**
   * Gets the machine name of the context that created this Payment, such as a
   * payment form, or a module.
   *
   * @return string
   */
  public function getPaymentContext();

  /**
   * Sets the ISO 4217 currency code of the payment amount.
   *
   * @param string $currencyCode
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setCurrencyCode($currencyCode);

  /**
   * Gets the ISO 4217 currency code of the payment amount.
   *
   * @return string
   */
  public function getCurrencyCode();

  /**
   * Sets line items.
   *
   * @param array $line_items
   *   Values are \Drupal\payment\Plugin\payment\line_item\PaymentLineItemInterface
   *   objects.
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setLineItems(array $line_items);

  /**
   * Sets a line item.
   *
   * @param \Drupal\payment\Plugin\payment\line_item\PaymentLineItemInterface $line_item
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setLineItem(PaymentLineItemInterface $line_item);

  /**
   * Gets all line items.
   *
   * @return array
   */
  public function getLineItems();

  /**
   * Gets a line item.
   *
   * @param string $name
   *   The line item's machine name.
   *
   * @return PaymentLineItem
   */
  public function getLineItem($name);

  /**
   * Gets line items by plugin type.
   *
   * @param string $plugin_id
   *   The line item plugin's ID.
   *
   * @return array
   *   Values are \Drupal\payment\Plugin\payment\line_item\PaymentLineItemInterface
   *   objects.
   */
  public function getLineItemsByType($type);

  /**
   * Sets all statuses.
   *
   * @param array $statuses
   *   \Drupal\payment\Plugin\payment\status\PaymentStatusInterface objects.
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setStatuses(array $statuses);

  /**
   * Sets a status.
   *
   * @param \Drupal\payment\Plugin\payment\status\PaymentStatusInterface $status
   * @param bool $notify
   *   Whether or not to trigger a notification event.
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setStatus(PaymentStatusInterface $status, $notify = TRUE);

  /**
   * Gets all statuses.
   *
   * @return array
   */
  public function getStatuses();

  /**
   * Gets the status.
   *
   * @return \Drupal\payment\Plugin\payment\status\PaymentStatusInterface
   */
  public function getStatus();

  /**
   * Sets the ID of the payment method entity.
   *
   * @param string $name
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setPaymentMethodId($id);

  /**
   * Gets the ID of the payment method entity.
   *
   * @return string|null
   */
  public function getPaymentMethodId();

  /**
   * Gets the payment method entity.
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentMethodInterface
   */
  public function getPaymentMethod();

  /**
   * Sets the ID of the user who owns this payment.
   *
   * @param int $uid
   *
   * @return \Drupal\payment\Plugin\Core\entity\PaymentInterface
   */
  public function setOwnerId($id);

  /**
   * Gets the ID of the user who owns this payment.
   *
   * @return int
   */
  public function getOwnerId();

  /**
   * Gets the owner.
   *
   * @return \Drupal\user\UserInterface
   */
  public function getOwner();

  /**
   * Gets the payment amount.
   *
   * @return float
   */
  public function getAmount();

  /**
   * Gets the available payment methods.
   *
   * @param array $paymentMethods
   *   An array with \Drupal\payment\Plugin\Core\entity\PaymentMethodInterface
   *   objects to optionally limit the check to.
   *
   * @return array
   *   An array with \Drupal\payment\Plugin\Core\entity\PaymentMethodInterface
   *   objects.
   */
  public function getAvailablePaymentMethods(array $paymentMethods = array());
}