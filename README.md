# laravel-vue-make
[![Build Status](https://travis-ci.com/fsac/laravel-vue-make.svg?branch=main)](https://travis-ci.com/fsac/laravel-vue-make)

Artisan generator for Vue component files. Supports Laravel 8, 7 &amp; 6.

## Why?

I scaffold stuff up using Artisan a lot and having a command
which all developers who work on a particular codebase have
access to helps keep things consistent.


## Quick start

```
% cd /path/to/laravel/project
% composer require --dev fsac/laravel-vue-make
```

### Customise Stubs

I use Tailwind for styles so the default stubs don't
contain a `<style>` tag. You might though so...

Publishing stubs in Laravel is a [great feature](https://laravel.com/docs/8.x/artisan#stub-customization).
Yes please.

```
% php artisan vendor:publish --tag vue-stub
```

## Usage

Generate a Vue component file:

```
% php artisan make:vue MyComponent
# -> resources/js/components/MyComponent.vue
```

Generate under a subdirectory

```
% php artisan make:vue foo/bar/MyComponent
# -> resources/js/components/foo/bar/MyComponent.vue
```
