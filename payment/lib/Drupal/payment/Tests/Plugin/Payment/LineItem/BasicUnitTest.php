<?php

/**
 * @file Contains \Drupal\payment\Tests\Plugin\Payment\LineItem\BasicUnitTest.
 */

namespace Drupal\payment\Tests\Plugin\Payment\LineItem;

use Drupal\Tests\UnitTestCase;

/**
 * Tests \Drupal\payment\Plugin\Payment\LineItem\Basic.
 */
class BasicUnitTest extends UnitTestCase {

  /**
   * The database connection used for testing.
   *
   * @var \Drupal\Core\Database\Connection|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $database;

  /**
   * The form builder used for testing.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $formBuilder;

  /**
   * The line item under test.
   *
   * @var \Drupal\payment\Plugin\Payment\LineItem\Basic|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $lineItem;

  /**
   * The translation manager.
   *
   * @var \Drupal\Core\StringTranslation\TranslationManager|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $translationManager;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'description' => '',
      'name' => '\Drupal\payment\Plugin\Payment\LineItem\Basic unit test',
      'group' => 'Payment',
    );
  }

  /**
   * {@inheritdoc
   */
  public function setUp() {
    $this->database = $this->getMockBuilder('\Drupal\Core\Database\Connection')
      ->disableOriginalConstructor()
      ->getMock();

    $this->formBuilder = $this->getMock('\Drupal\Core\Form\FormBuilderInterface');

    $this->translationManager = $this->getMockBuilder('\Drupal\Core\StringTranslation\TranslationManager')
      ->disableOriginalConstructor()
      ->setMethods(array('translate'))
      ->getMock();

    $configuration = array();
    $plugin_id = $this->randomName();
    $plugin_definition = array();
    $this->lineItem = $this->getMockBuilder('\Drupal\payment\Plugin\Payment\LineItem\Basic')
      ->setConstructorArgs(array($configuration, $plugin_id, $plugin_definition, $this->translationManager, $this->database, $this->formBuilder))
      ->setMethods(array('drupalGetPath', 't'))
      ->getMock();
  }

  /**
   * Tests setDescription() and getDescription().
   */
  public function testGetDescription() {
    $description = $this->randomName();
    $this->assertSame(spl_object_hash($this->lineItem), spl_object_hash($this->lineItem->setDescription($description)));
    $this->assertSame($description, $this->lineItem->getDescription());
  }

  /**
   * Tests formElements().
   */
  public function testFormElements() {
    $this->lineItem->expects($this->once())
      ->method('drupalGetPath')
      ->will($this->returnValue($this->randomName()));
    $this->translationManager->expects($this->any())
      ->method('translate')
      ->will($this->returnArgument(0));
    $form = array();
    $form_state = array();
    $form_elements = $this->lineItem->formElements($form, $form_state);
    $this->assertInternalType('array', $form_elements);
  }
}
