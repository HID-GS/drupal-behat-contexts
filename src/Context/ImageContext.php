<?php

namespace Hid\DrupalBehatContexts\Context;

use Behat\Behat\Context\Context;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines image features from the specific context.
 */
class ImageContext extends RawDrupalContext implements Context {

  private $baseUrl;

  /**
   * Gets base URL from behat.yml.
   *
   * @BeforeScenario
   */
  public function getBaseUrl() {
    $this->baseUrl = $this->getMinkParameter('base_url');
  }

  /**
   * Checks if a URL is relative.
   *
   * @param string $url
   *   URL of image being checked.
   *
   * @return bool
   *   Returns true if URL is relative.
   */
  public function isRelative($url) {
    return (empty(parse_url($url)['scheme']));
  }

  /**
   * Attempts to load an image.
   *
   * @param string $url
   *   URL of image being checked.
   *
   * @throws \Exception
   *   If image cannot be loaded.
   */
  public function loadImage($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code === 404) {
      throw new \Exception(sprintf('%s could not be loaded', $url));
    }
  }

  /**
   * Checks that images are received.
   *
   * Example: Then I should receive all images in the ".selector" element
   * Example: And I should receive all images in the "#selector" element
   *
   * @param string $element
   *   Passed from feature scenario.
   *
   * @Then /^(?:|I )should receive all images in the "(?P<element>[^"]*)" element$/
   *
   * @throws \Exception
   */
  public function iShouldReceiveAllImages($element) {
    $images = $this->getSession()
      ->getPage()
      ->find('css', $element)
      ->findAll('css', 'img');
    foreach ($images as $image) {
      $img_src = $image->getAttribute('src');
      if ($this->isRelative($img_src)) {
        $img_src = $this->baseUrl . '/' . $img_src;
      }
      $this->loadImage($img_src);
    }
  }

}
