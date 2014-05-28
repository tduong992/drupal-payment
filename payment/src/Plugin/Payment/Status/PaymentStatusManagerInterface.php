<?php

/**
 * @file
 * Contains \Drupal\payment\Plugin\Payment\Status\PaymentStatusManagerInterface.
 */

namespace Drupal\payment\Plugin\Payment\Status;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines a payment status manager.
 */
interface PaymentStatusManagerInterface extends PluginManagerInterface {

  /**
   * Creates a payment status.
   *
   * @param string $plugin_id
   *   The id of the plugin being instantiated.
   * @param array $configuration
   *   An array of configuration relevant to the plugin instance.
   *
   * @return \Drupal\payment\Plugin\Payment\Status\PaymentStatusInterface
   */
  public function createInstance($plugin_id, array $configuration = array());

  /**
   * Returns payment status options.
   *
   * @return array
   *   Keys are plugin IDs. Values are plugin labels.
   */
  public function options();

  /**
   * Returns a hierarchical representation of payment statuses.
   *
   * @return array
   *   A possibly infinitely nested associative array. Keys are plugin IDs and
   *   values are arrays of similar structure as this method's return value.
   */
  public function hierarchy();

  /**
   * Gets a payment status's ancestors.
   *
   * @param string $plugin_id
   *
   * @return array
   *   The plugin IDs of this status's ancestors.
   */
  public function getAncestors($plugin_id);

  /**
   * Gets a payment status's children.
   *
   * @param string $plugin_id
   *
   * @return array
   *   The plugin IDs of this status's children.
   */
  public function getChildren($plugin_id);

  /**
   * Get a payment status's descendants.
   *
   * @param string $plugin_id
   *
   * @return array
   *   The machine names of this status's descendants.
   */
  public function getDescendants($plugin_id);

  /**
   * Checks if a status has a given other status as one of its ancestors.
   *
   * @param string $plugin_id
   * @param string $ancestor_plugin_id
   *   The payment status plugin ID to check against.
   *
   * @return boolean
   */
  public function hasAncestor($plugin_id, $ancestor_plugin_id);

  /**
   * Checks if the status is equal to a given other status or has it one of
   * its ancestors.
   *
   * @param string $plugin_id
   * @param string $ancestor_plugin_id
   *   The payment status plugin ID to check against.
   *
   * @return boolean
   */
  public function isOrHasAncestor($plugin_id, $ancestor_plugin_id);

  /**
   * Gets the payment status' operations provider.
   *
   * @param string $plugin_id
   *
   * @return \Drupal\payment\Plugin\Payment\OperationsProviderInterface|null
   *   The operations provider or NULL if none is available..
   */
  public function getOperationsProvider($plugin_id);

}
