<?php

/**
 * @file
 * Contains class \Drupal\payment\Tests\Plugin\payment\line_item\BasicWebTest.
 */

namespace Drupal\payment\Tests\Plugin\payment\line_item;

use Drupal\payment\Payment;
use Drupal\payment\Plugin\payment\line_item\PaymentLineItemInterface;
use Drupal\simpletest\WebTestBase;

/**
 * Tests \Drupal\payment\Plugin\payment\status\Basic.
 */
class BasicWebTest extends WebTestBase {

  /**
   * The line item to test.
   *
   * @var \Drupal\payment\Plugin\payment\line_item\Basic
   */
  protected $lineItem;

  /**
   * {@inheritdoc}
   */
  public static $modules = array('payment', 'payment_test');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'description' => '',
      'name' => '\Drupal\payment\Plugin\payment\line_item\Basic web test',
      'group' => 'Payment',
    );
  }

  /**
   * {@inheritdoc
   */
 protected function setUp() {
    parent::setUp();
    $this->lineItem = Payment::lineItemManager()->createInstance('payment_basic');
  }

  /**
   * Tests formElements().
   */
  protected function testFormElements() {
    $line_item_data = array(
      'line_item[amount][amount]' => '123.45',
      'line_item[quantity]' => '3',
      'line_item[description]' => 'Foo & Bar',
    );

    // Tests the presence of child form elements and their default values.
    $this->drupalGet('payment_test-plugin-payment-line_item-payment_basic');
    foreach (array_keys($line_item_data) as $name) {
      $this->assertFieldByName($name);
    }

    // Test valid values.
    $data = $line_item_data;
    $data['line_item[description]'] = 'FooBar';
    $this->drupalPostForm('payment_test-plugin-payment-line_item-payment_basic', $data, t('Submit'));
    $this->assertUrl('user', array(), 'Valid values trigger form submission.');

    // Test a non-integer quantity.
    $values =  array(
      'line_item[quantity]' => $this->randomName(2),
    );
    $this->drupalPostForm('payment_test-plugin-payment-line_item-payment_basic', $values, t('Submit'));
    $this->assertFieldByXPath('//input[@name="line_item[quantity]" and contains(@class, "error")]');
  }

  /**
   * Tests saveData(), loadData(), and deleteData().
   */
  protected function testData() {
    $name = $this->randomName();
    $payment_id = mt_rand();
    $description = $this->randomString();
    $this->lineItem
      ->setDescription($description)
      ->setName($name)
      ->setPaymentId($payment_id)
      ->saveData()
      ->setDescription($this->randomString())
      ->loadData();
    $this->assertEqual($this->lineItem->getDescription(), $description);
    $this->lineItem
      ->setDescription($this->randomString())
      ->deleteData()
      ->loadData();
    $this->assertNotEqual($this->lineItem->getDescription(), $description);
  }

  /**
   * Tests integration with payment entities.
   */
  protected function testPaymentEntityIntegration() {
    $description = $this->randomString();
    $name = $this->randomName();
    $payment = entity_create('payment', array(
      'bundle' => 'payment_unavailable',
    ));
    $this->lineItem
      ->setName($name)
      ->setDescription($description);
    $payment->setLineItem($this->lineItem)
      ->save();
    $this->assertNotNull($this->lineItem->getPaymentId());
    $payment_loaded = entity_load_unchanged('payment', $payment->id());
    $this->assertEqual($payment_loaded->getLineItem($name)->getDescription(), $description);
    $payment->delete();
    $this->lineItem
      ->setDescription($this->randomString())
      ->loadData();
    $this->assertFalse($this->lineItem->getDescription());
  }
}