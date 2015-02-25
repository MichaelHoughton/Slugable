<?php
/**
 * Slugable Behavior
 *
 * @author Michael Houghton <michael@cakecoded.com>
 * @license MIT
 */

class SlugableBehavior extends ModelBehavior {

/**
 * Sets the slug and displayField variables from the model $actsAs
 * @param  Model  $model
 * @param  array  $settings
 * @return void
 */
    public function setup(Model $model, $settings = array()){
        $fields = array();

        if ($model->actsAs['Slugable.Slugable']) {
            $fields = $model->actsAs['Slugable.Slugable'];

            if (!is_array($fields)) {
                $fields = array($fields);
            }
        }

        $slug = 'slug';
        if ($fields) {
            $shift = $fields;
            $slug = array_shift($shift);
        }
        $this->slug = $slug;

        // lets set the displayField
        reset($fields);
        $displayField = key($fields);

        if (empty($displayField)) {
            $displayField = $model->displayField;
        }
        $this->displayField = $displayField;
    }

/**
 * Generates the slug
 * @param  Model  $model
 * @param  array  $options
 * @return boolean
 */
    public function beforeSave(Model $model, $options = array()) {
        if (!empty($model->data[$model->alias][$this->displayField])) {
            $id = null;

            if ($model->id) {
                $id = $model->id;
            }

            $model->data[$model->alias][$this->slug] = $this->generateSlug($model, $model->data[$model->alias][$this->displayField], $id);
        }

        return true;
    }

/**
* This method generates a slug from a title
*
* @param  Model  $model
* @param  string $title The title or name
* @param  string $id The ID of the model
* @return string Slug
*/
    public function generateSlug(Model $model, $title = null, $id = null, $separator = '-') {
        if (!$title) {
            return null;
        }

        $title = strtolower($title);
        $slug  = Inflector::slug($title, $separator);

        $conditions = array();
        $conditions[$model->alias . '.slug'] = $slug;

        if ($id) {
            $conditions[$model->primaryKey. ' NOT'] = $id;
        }

        $total = $model->find('count', array('conditions' => $conditions, 'recursive' => -1));
        if ($total > 0) {
            for ($number = 2; $number > 0; $number ++) {
                $conditions[$model->alias . '.slug'] = $slug . $separator . $number;

                $total = $model->find('count', array('conditions' => $conditions, 'recursive' => -1));
                if ($total == 0) {
                    return $slug . $separator . $number;
                }
            }
        }

        return $slug;
    }
}