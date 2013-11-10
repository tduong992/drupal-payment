<?php

/**
 * @file
 * Contains class \Drupal\payment\Tests\Entity\PaymentMethodAccessControllerUnitTest.
 */

namespace Drupal\payment\Tests\Entity;

use Drupal\payment\AccessibleInterfaceUnitTestBase;
use Drupal\payment\Generate;
use Drupal\payment\Payment;

/**
 * Tests \Drupal\payment\PaymentMethodAccessController.
 */
class PaymentMethodAccessControllerUnitTest extends AccessibleInterfaceUnitTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = array('field', 'payment', 'system', 'user');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'description' => '',
      'name' => '\Drupal\payment\PaymentMethodAccessController unit test',
      'group' => 'Payment',
    );
  }

  /**
   * Tests access control.
   */
  protected function testAccessControl() {
    $entity_manager = \Drupal::entityManager();
    $payment_method_manager = Payment::methodManager();
    $user_storage_controller = $entity_manager->getStorageController('user');
    $authenticated = $user_storage_controller->create(array());

    // Create a new payment method.
    $plugin = $payment_method_manager->createInstance('payment_basic');
    $this->assertDataAccess(Generate::createPaymentMethod(0, $plugin), 'a payment method', 'create', $authenticated, array('payment.payment_method.create.payment_basic'));

    // Update a payment method that belongs to user 1.
    $this->assertDataAccess(Generate::createPaymentMethod(1), 'a payment method', 'update', $authenticated, array('payment.payment_method.update.any'));

    // Update a payment method that belongs to user 2.
    $this->assertDataAccess(Generate::createPaymentMethod($authenticated->id()), 'a payment method', 'update', $authenticated, array('payment.payment_method.update.own'));

    // Delete a payment method that belongs to user 1.
    $this->assertDataAccess(Generate::createPaymentMethod(1), 'a payment method', 'delete', $authenticated, array('payment.payment_method.delete.any'));

    // Delete a payment method that belongs to user 2.
    $this->assertDataAccess(Generate::createPaymentMethod($authenticated->id()), 'a payment method', 'delete', $authenticated, array('payment.payment_method.delete.own'));

    // Enable an enabled payment method that belongs to user 1.
    $payment_method = Generate::createPaymentMethod(1);
    $this->assertDataAccess($payment_method, 'an enabled payment method', 'enable', $authenticated, array('payment.payment_method.update.any'), array(
      'root' => FALSE,
      'authenticated_with_permissions' => FALSE,
    ));

    // Enable an enabled payment method that belongs to user 2.
    $payment_method = Generate::createPaymentMethod($authenticated->id());
    $this->assertDataAccess($payment_method, 'an enabled payment method', 'enable', $authenticated, array('payment.payment_method.update.own'), array(
      'root' => FALSE,
      'authenticated_with_permissions' => FALSE,
    ));

    // Enable a disabled payment method that belongs to user 1.
    $payment_method = Generate::createPaymentMethod(1);
    $payment_method->disable();
    $this->assertDataAccess($payment_method, 'a disabled payment method', 'enable', $authenticated, array('payment.payment_method.update.any'));

    // Enable a disabled payment method that belongs to user 2.
    $payment_method = Generate::createPaymentMethod($authenticated->id());
    $payment_method->disable();
    $this->assertDataAccess($payment_method, 'a disabled payment method', 'enable', $authenticated, array('payment.payment_method.update.own'));

    // Disable a disabled payment method that belongs to user 1.
    $payment_method = Generate::createPaymentMethod(1);
    $payment_method->disable();
    $this->assertDataAccess($payment_method, 'a disabled payment method', 'disable', $authenticated, array('payment.payment_method.update.any'), array(
      'root' => FALSE,
      'authenticated_with_permissions' => FALSE,
    ));

    // Disable a disabled payment method that belongs to user 2.
    $payment_method = Generate::createPaymentMethod($authenticated->id());
    $payment_method->disable();
    $this->assertDataAccess($payment_method, 'a disabled payment method', 'disable', $authenticated, array('payment.payment_method.update.own'), array(
      'root' => FALSE,
      'authenticated_with_permissions' => FALSE,
    ));

    // Disable an enabled payment method that belongs to user 1.
    $payment_method = Generate::createPaymentMethod(1);
    $this->assertDataAccess($payment_method, 'a disabled payment method', 'disable', $authenticated, array('payment.payment_method.update.any'));

    // Enable am enabled payment method that belongs to user 2.
    $payment_method = Generate::createPaymentMethod($authenticated->id());
    $this->assertDataAccess($payment_method, 'a disabled payment method', 'disable', $authenticated, array('payment.payment_method.update.own'));

    // Clone a payment method that belongs to user 1.
    $this->assertDataAccess(Generate::createPaymentMethod(1, $payment_method_manager->createInstance('payment_basic')), 'a payment method', 'duplicate', $authenticated, array('payment.payment_method.view.any', 'payment.payment_method.create.payment_basic'));

    // Clone a payment method that belongs to user 2.
    $this->assertDataAccess(Generate::createPaymentMethod($authenticated->id(), $payment_method_manager->createInstance('payment_basic')), 'a payment method', 'duplicate', $authenticated, array('payment.payment_method.view.own', 'payment.payment_method.create.payment_basic'));

    // View a payment method that belongs to user 1.
    $this->assertDataAccess(Generate::createPaymentMethod(1), 'a payment method', 'view', $authenticated, array('payment.payment_method.view.any'));

    // View a payment method that belongs to user 2.
    $this->assertDataAccess(Generate::createPaymentMethod($authenticated->id()), 'a payment method', 'view', $authenticated, array('payment.payment_method.view.own'));
  }
}