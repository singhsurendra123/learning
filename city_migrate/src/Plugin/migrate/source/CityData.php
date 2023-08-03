<?php

namespace Drupal\city_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;

/**
 * Source plugin to import data from JSON files .
 *
 * @MigrateSource(
 *   id = "city_data"
 * )
 */
class CityData extends SourcePluginBase {

  /**
   * Prepare row for migration.
   */
  public function prepareRow(Row $row) {
    $location = $row->getSourceProperty('loc');
    // Make sure the title isn't too long for Drupal.
    if (!empty($location)) {
      $row->setSourceProperty('loc', implode(',', $location));
    }
    return parent::prepareRow($row);
  }

  /**
   * Get id of migration entity.
   */
  public function getIds() {
    $ids = [
      '_id' => [
        'type' => 'string',
      ],
    ];
    return $ids;
  }

  /**
   * Define fields of source files.
   */
  public function fields() {
    return [
      'city' => $this->t('City'),
      '_id' => $this->t('ID'),
      'loc' => $this->t('Location'),
      'pop' => $this->t('Population'),
      'state' => $this - t("State"),
    ];
  }

  /**
   * Convert json data into string.
   */
  public function __toString() {
    return "json data";
  }

  /**
   * Initializes the iterator with the source data.
   *
   * @return \Iterator
   *   An iterator containing the data for this source.
   */
  protected function initializeIterator() {

    // Loop through the source files and find anything with a .json extension.
    $filename = dirname(DRUPAL_ROOT) . "/web/modules/custom/city_migrate/cities.json";
    $rows = [];
    // Using second argument of TRUE here because migrate needs the data to be
    // associative arrays and not stdClass objects.
    // Sets the title, body, etc.
    $rows = json_decode(file_get_contents($filename), TRUE);
    $row['json_filename'] = $filename;
    // Migrate needs an Iterator class, not just an array.
    return new \ArrayIterator($rows);
  }

}
