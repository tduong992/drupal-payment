<?php

/**
 * Contains \Drupal\payment\Plugin\Payment\Type\PaymentTypeInterface.
 */

namespace Drupal\payment\Plugin\Payment\Type;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\payment\PaymentAwareInterface;

/**
 * A payment type plugin.
 */
interface PaymentTypeInterface extends PluginInspectionInterface, ConfigurablePluginInterface, PaymentAwareInterface {

  /**
   * Returns the description of the payment this plugin is of.
   *
   * @param string $language_code
   *   The code of the language to return the description in.
   *
   * @param string
   */
  public function paymentDescription($language_code = NULL);

  /**
   * Checks if the payment type context can be resumed.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return bool
   *
   * @see self::getResumeContextResponse
   */
  public function resumeContextAccess(AccountInterface $account);

  /**
   * Resumes the payer's original workflow.
   *
   * @return \Drupal\payment\Response\ResponseInterface
   *
   * @see self::resumeContextAccess
   */
  public function getResumeContextResponse();

}