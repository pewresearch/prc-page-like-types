# PRC Page-Like Types

> Canonical docs: [docs/plugins/prc-page-like-types/](../../docs/plugins/prc-page-like-types/)

Registers "page-like" custom post types for PRC Platform — content types that share a simple `/{slug}/{post-name}` permalink structure and a common feature set, but are editorially and functionally independent of each other.

## What it does

-   Registers three custom post types: `mini-course` (URL: `/course/{post-name}`), `events` (URL: `/event/{post-name}`), and `fact-sheet` (URL: `/fact-sheet/{post-name}`)
-   Provides a `Registry` class that standardizes post type registration across all page-like types — labels, rewrite rules, taxonomy assignments, and default feature support
-   Opts all registered types into the PRC Platform post-publish pipeline via `prc_platform_post_publish_pipeline_post_types`
-   `fact-sheet` declares `pub_listing`, so it gets `prc-publication-listing` support and is included in the main RSS feed by `prc-publication-listing`
-   Enforces a matching `formats` taxonomy term on every page-like post at incremental save (auto-creating the term if it doesn't exist)
-   Enables `custom-fields` and `comments` support on the built-in `page` post type
-   Adds `notes` editor support to every registered page-like type (merging into existing editor supports without overwriting them)
-   Includes `pub_listing` flag on `fact-sheet`, which opts it into the `_post_visibility` taxonomy and enables `prc-publication-listing` post type support

## Registered post types

| Post type     | URL slug      | `pub_listing` | Notes                                                                              |
| ------------- | ------------- | ------------- | ---------------------------------------------------------------------------------- |
| `mini-course` | `/course`     | No            | Additional taxonomies: `datasets`, `collection`, `bylines`                        |
| `events`      | `/event`      | No            | Default taxonomy set only                                                          |
| `fact-sheet`  | `/fact-sheet` | Yes           | Additional taxonomies: `datasets`, `collection`, `bylines`; included in main feed |

### Default supports (all types)

`title`, `editor`, `excerpt`, `author`, `thumbnail`, `revisions`, `prc-revisions`, `custom-fields`, `comments`, `prc-schema-seo`, `prc-social`, `prc-bylines`, `prc-art-direction`, `prc-datasets`, `prc-collections`, `prc-sitemap`, `prc-markdown-for-agents`

Types with `pub_listing: true` also get `prc-publication-listing`.

### Default taxonomies (all types)

`category`, `collection`, `formats`, `languages`, `research-teams`

Types with additional taxonomy arrays get those merged in.

## Key files

| File                                                 | Purpose                                                                                                              |
| ---------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `prc-page-like-types.php`                            | Plugin entry point; defines constants, registers activation/deactivation hooks, boots `Plugin`                       |
| `includes/class-plugin.php`                          | Wires up dependencies and registers the three concrete post types via `Registry::register()`                         |
| `includes/class-registry.php`                        | Core abstraction — handles label construction, `register_post_type()` calls, format enforcement, and pipeline opt-in |
| `includes/class-loader.php`                          | Standard hook loader (add_action/add_filter queue)                                                                   |
| `includes/class-prc-page-like-types-activator.php`   | Activation hook handler                                                                                              |
| `includes/class-prc-page-like-types-deactivator.php` | Deactivation hook handler                                                                                            |

## Filters / hooks

### Consumed (listens to)

| Hook                               | Type   | Description                                                                         |
| ---------------------------------- | ------ | ----------------------------------------------------------------------------------- |
| `init`                             | Action | Calls `register_page_like_types()` to register all post types and add notes support |
| `init`                             | Action | Adds `custom-fields` and `comments` support to the `page` post type                 |
| `prc_platform_on_incremental_save` | Action | Enforces matching `formats` term on post save for any registered page-like type     |

### Produced (exposes)

| Hook                                            | Type   | Description                                                                                |
| ----------------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| `prc_platform_post_publish_pipeline_post_types` | Filter | Appends all registered page-like type slugs so they flow through the post-publish pipeline |

## Adding a new page-like type

Call `Registry::register()` from `Plugin::init_dependencies()`:

```php
$this->registry->register(
    array(
        'slug'        => 'press-release',
        'singular'    => 'Press Release',
        'plural'      => 'Press Releases',
        'description' => 'Official press releases from Pew Research Center.',
        'pub_listing' => false,
    ),
    array(
        'rewrite'   => array( 'slug' => 'press-release' ),
        'menu_icon' => 'dashicons-megaphone',
    ),
    array( 'bylines' ) // extra taxonomies beyond the default set
);
```

The type is automatically:

-   Registered with `register_post_type()` on `init`
-   Opted into the post-publish pipeline
-   Given `notes` editor support
-   Assigned a matching `formats` term on every save

Set `pub_listing: true` to also opt into `_post_visibility` taxonomy and `prc-publication-listing` support.

## Dependencies

-   `prc-platform-core` (required) — provides `prc_platform_post_publish_pipeline_post_types` and `prc_platform_on_incremental_save` hooks

## Notes

-   The `set_fact_sheet_schema_type` method (sets schema type to `Report` for `fact-sheet`) is currently commented out pending schema SEO integration.
-   `capability_type` is set to `page` for all registered types, meaning editorial permissions mirror the `page` post type.
-   Flushing rewrite rules after activating or adding a new type is required; the activator handles this on plugin activation.
