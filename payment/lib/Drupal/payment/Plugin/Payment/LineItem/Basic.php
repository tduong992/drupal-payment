<?php

/**
 * Contains \Drupal\payment\Plugin\Payment\LineItem\Basic.
 */

namespace Drupal\payment\Plugin\Payment\LineItem;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslationManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A basic line item.
 *
 * @PaymentLineItem(
 *   id = "payment_basic",
 *   label = @Translation("Basic")
 * )
 */
class Basic extends Base implements ContainerFactoryPluginInterface {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The translation manager.
   *
   * @var \Drupal\Core\StringTranslation\TranslationManager
   */
  protected $translationManager;

  /**
   * Constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\StringTranslation\TranslationManager $translation_manager
   *   The translation manager.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, TranslationManager $translation_manager, Connection $database, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->translationManager = $translation_manager;
    $this->database = $database;
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, array $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('string_translation'), $container->get('database'), $container->get('form_builder'));
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + array(
      'description' => NULL,
    );
  }

  /**
   * {@inheritdoc}
   */
  function getDescription() {
    return $this->configuration['description'];
  }

  /**
   * Sets the line item description.
   *
   * @param string $description
   *
   * @return \Drupal\payment\Plugin\Payment\LineItem\Basic
   */
  function setDescription($description) {
    $this->configuration['description'] = $description;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function formElements(array $form, array &$form_state) {
    $elements = array(
      '#attached' => array(
        'css' => array($this->drupalGetPath('module', 'payment') . '/css/payment.css'),
      ),
      '#input' => TRUE,
      '#tree' => TRUE,
      '#type' => 'container',
    );
    $elements['name'] = array(
      '#type' => 'value',
      '#value' => $this->getName(),
    );
    $elements['payment_id'] = array(
      '#type' => 'value',
      '#value' => $this->getPaymentId(),
    );
    $elements['amount'] = array(
      '#type' => 'currency_amount',
      '#title' => $this->t('Amount'),
      '#default_value' => array(
        'amount' => $this->getAmount(),
        'currency_code' => $this->getCurrencyCode(),
      ),
      '#required' => TRUE,
    );
    $elements['quantity'] = array(
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#default_value' => $this->getQuantity(),
      '#min' => 1,
      '#size' => 3,
      '#required' => TRUE,
    );
    $elements['description'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $this->getDescription(),
      '#required' => TRUE,
      '#maxlength' => 255,
    );
    $elements['clear'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="clear"></div>',
    );

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function getConfigurationFromFormValues(array $form, array &$form_state) {
    $values = NestedArray::getValue($form_state['values'], $form['#parents']);

    return array(
      'amount' => $values['amount']['amount'],
      'currency_code' => $values['amount']['currency_code'],
      'description' => $values['description'],
      'name' => $values['name'],
      'payment_id' => $values['payment_id'],
      'quantity' => $values['quantity'],
    );
  }

  /**
   * Wraps drupal_get_path().
   */
  protected function drupalGetPath($type, $name) {
    return drupal_get_path($type, $name);
  }
}
