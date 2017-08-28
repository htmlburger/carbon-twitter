# Carbon Twitter

Helper library for retrieving tweets from the Twitter API.


## Installation

The library is available as Composer package. You can include it in your Project with:

`composer require htmlburger/carbon-twitter`


## Usage

```php
use Carbon_Twitter\Carbon_Twitter;

Carbon_Twitter::config( array(
  'api' => array(
    'access_token'        => '',
    'access_token_secret' => '',
    'consumer_key'        => '',
    'consumer_secret'     => '',
  ),
  'cache_lifetime'   => 300,
  'verbose'          => false,
  'cache_candidates' => [ 'WordPress', 'File' ],
) );

$tweets = Carbon_Twitter::get_tweets( 'wordpress', 5 );

foreach ( $tweets as $tweet ) {
  echo $tweet->text;
}
```

or by using the helper functions:

```php
carbon_twitter_set_config( array(
  'api' => array(
    'access_token'        => '',
    'access_token_secret' => '',
    'consumer_key'        => '',
    'consumer_secret'     => '',
  ),
  'cache_lifetime'   => 300,
  'verbose'          => false,
  'cache_drivers'    => [ 'WordPress', 'File' ],
) );

$tweets = carbon_twitter_get_tweets( 'wordpress', 5 );

foreach ( $tweets as $tweet ) {
  echo $tweet->text;
}
```


## Configuration Parameters

`api (array) - required`

The `api` parameter holds an array of 4 elements:

 * `access_token`
 * `access_token_secret`
 * `consumer_key`
 * `consumer_secret`

`cache_lifetime (int) - optional`

The cache duration defined in seconds. Defaults to `300` seconds (5 minutes)

`verbose (boolean) - optional`

Whether to enable Verbose mode. Defaults to `false`.

`cache_drivers (array) - optional`

An array of Cache Drivers to use. Uses the first Driver which is supported in the Project environment. Defaults to `array( 'WordPress', 'File' )`


## In WordPress environment

Once the library is installed in your WordPress project, the receive the following features out of the box:

 * Carbon_Twitter_Feed widget is being registered
 * A new Carbon Container is registered - Twitter Settings

There are several hooks that you can use in order to customize the functionality

### Carbon_Twitter_Feed_Widget

__carbon_twitter_widget_id__

Allows you to modify the Widget ID.

```php
apply_filters( 'carbon_twitter_widget_id', 'carbon_twitter_feed_widget' );
```

__carbon_twitter_widget_title__

Allows you to modify the default Widget title.

```php
apply_filters( 'carbon_twitter_widget_title', __( 'Carbon Twitter Feed', 'carbon-twitter' ) )
```

__carbon_twitter_widget_description__

Allows you to modify the default Widget description.

```php
apply_filters( 'carbon_twitter_widget_description', __( 'Displays a Twitter Feed.', 'carbon-twitter' ) )
```

__carbon_twitter_widget_fields__

Allows you to modify the array of the default Widget fields.

```php
apply_filters( 'carbon_twitter_widget_fields', array(
  Field::make( 'text', 'title', __( 'Title', 'carbon-twitter' ) ),
  Field::make( 'text', 'twitter_username', __( 'Twitter Username', 'carbon-twitter' ) ),
  Field::make( 'text', 'count_tweets', __( 'Number of Tweets to Display', 'carbon-twitter' ) )
    ->set_default_value( 5 ),
) )
```

__carbon_twitter_widget_classes__

Allows you to modify the CSS classes that will be added to the Widget.

```php
apply_filters( 'carbon_twitter_widget_classes', 'carbon-twitter-feed' )
```

### Twitter Settings Carbon Container

__carbon_twitter_settings_title__

Allows you to change the title of the Twitter Settings Carbon Container.

```php
apply_filters( 'carbon_twitter_settings_title', __( 'Twitter Settings', 'carbon-twitter' ) )
```

__carbon_twitter_settings_page_parent__

Allows you to change the Page Parent of the Twitter Settings Carbon Container.

```php
apply_filters( 'carbon_twitter_settings_page_parent', 'crbn-theme-options.php' )
```

__carbon_twitter_settings_custom_help_text__

Allows you to modify the help text of the Twitter Settings Carbon Container.

__carbon_twitter_settings_fields__

Allows you to modify the default fields in the Twitter Settings Carbon Container.

```php
apply_filters( 'carbon_twitter_settings_fields', array(
    Field::make( 'html', 'carbon_twitter_settings_html' )
      ->set_html( carbon_twitter_get_options_help_text() ),
    Field::make( 'text', 'carbon_twitter_consumer_key', __( 'Consumer Key', 'carbon-twitter' ) ),
    Field::make( 'text', 'carbon_twitter_consumer_secret', __( 'Consumer Secret', 'carbon-twitter' ) ),
    Field::make( 'text', 'carbon_twitter_access_token', __( 'Access Token', 'carbon-twitter' ) ),
    Field::make( 'text', 'carbon_twitter_access_token_secret', __( 'Access Token Secret', 'carbon-twitter' ) ),
  ) )
```
