## Installation

1. Clone the repository.
2. Run `composer install`
3. Create a *.env* file
       
       cp .env.sample .env
4. Add your SpineOMatic Alma API key to the *.env* file.
5. Set the caching engine, currently either `redis` or  `none`.
    
Make sure the *view-cache* and *logs* directories are writable by PHP.

## Dependencies

* PHP 7.3.0 or greater
* Redis (optional)

## Styling

Styling is built on top of Bootstrap in SCSS.

Install the SCSS binary and Bootstrap SCSS

    npm install

Compile the SCSS

    npx sass src/scss/local.scss ./public/css/local.css -s compressed

## Thanks

Built using [Slim Skeleton](https://github.com/slimphp/Slim-Skeleton).

## License

This tool is made available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).