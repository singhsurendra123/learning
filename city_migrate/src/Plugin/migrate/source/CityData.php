<?php 
namespace Drupal\city_migrate\Plugin\migrate\source; 
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase; 
use Drupal\migrate\Row; 
 
/** 
* Source plugin to import data from JSON files 
* @MigrateSource( 
*   id = "city_data" 
* ) 
*/ 
class CityData extends SourcePluginBase { 
 
  public function prepareRow(Row $row) { 
    $location = $row->getSourceProperty('location');  
    // make sure the title isn't too long for Drupal 
    if (!empty($location)) { 
      $row->setSourceProperty('location', impolde(',', $location)); 
    }  
    return parent::prepareRow($row); 
  } 
 
  public function getIds() { 
    $ids = [ 
      'json_filename' => [ 
        'type' => 'string' 
      ] 
    ]; 
    return $ids; 
  } 
 
  public function fields() { 
    return array( 
      'city' => $this->t('City'), 
      '_id' => $this->t('ID'), 
      'loc' => $this->t('Location'), 
      'pop' => $this->t('Population'), 
      'state' => $this-t("State"),
    ); 
  } 
 
  public function __toString() { 
    return "json data"; 
  } 
 
  /** 
   * Initializes the iterator with the source data. 
   * @return \Iterator 
   *   An iterator containing the data for this source. 
   */ 
  protected function initializeIterator() { 
 
    // loop through the source files and find anything with a .json extension 
    $filename = dirname(DRUPAL_ROOT) . "/web/modules/custom/city_migrate/cities.json";  
    $rows = [];  
    // using second argument of TRUE here because migrate needs the data to be 
    // associative arrays and not stdClass objects. 
    $rows = json_decode(file_get_contents($filename), true); // sets the title, body, etc.   
    // Migrate needs an Iterator class, not just an array
    return new \ArrayIterator($rows); 
  } 
} 