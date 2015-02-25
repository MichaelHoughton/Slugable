# Slugable
Slug Generator Plugin for CakePHP 2.x

# Installing

1) Ensure that the Slugable Plugin is installed in app/Plugin/Slugable

   The easiest way to do this, is in your terminal / command line, go to your app/Plugin directory and run the following command:
   git submodule add https://github.com/MichaelHoughton/Slugable.git

2) In your app/Config/bootstrap.php file, load the Plugin:
   CakePlugin::load('Slugable');

3) You should ensure your database table has a display field, e.g. "title", and a slug field, e.g. "slug".

4) When you want to use the plugin, just set the following in your model file:

```php
public $actsAs = array(
	'Slugable.Slugable' => array('your_title_field' => 'your_slug_field')
);
```

For example, if your display field was "title" and your slug field as "slug"

```php
public $actsAs = array(
	'Slugable.Slugable' => array('title' => 'slug')
);
```

Finally, in the spirit of simplification, if the title field is your $this->displayField, you can pass the $actsAs like follows:

```php
public $actsAs = array(
	'Slugable.Slugable' => 'slug'
);
```