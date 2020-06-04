<?php

namespace HidGlobal\DrupalBehatContexts\Context;

use Behat\Behat\Context\Context;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Provides pre-built step definitions for interacting with images.
 */
class ImageContext extends RawDrupalContext implements Context {

  /**
   * Base URL from behat.yml.
   *
   * @var string
   */
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
   * Gets the path to an image.
   *
   * @param object $image
   *   Image being checked.
   *
   * @return string
   *   The absolute URL of image.
   */
  public function getImageSrc($image) {
    $src = $image->getAttribute('src');
    if ($this->isRelative($src)) {
      $src = $this->baseUrl . '/' . $src;
    }

    return $src;
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
      $path = $this->getImageSrc($image);
      $this->loadImage($path);
    }
  }

  /**
   * Checks that an image is on the page.
   *
   * Example: Then I should see the image "test.jpg"
   * Example: And I should see the image "test.png"
   *
   * @param string $image_name
   *   Passed from feature scenario.
   *
   * @throws \Exception
   *   If image cannot be found.
   *
   * @Then I should see the image :image_name
   */
  public function iShouldSeeTheImage($image_name) {
    $image = $this->getSession()
      ->getPage()
      ->find('css', "img[src*='$image_name']");

    if (is_null($image)) {
      throw new \Exception(sprintf('The image %s could not be found', $image_name));
    }

    $path = $this->getImageSrc($image);
    $this->loadImage($path);
  }

}
