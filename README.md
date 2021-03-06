# Laravel Memory Cache
 
The main goal of this package is to prevent useless calls to the database which could be remembered easily in memory.

The trait `SimpleMemoryCached` is watching for new instances of the model from the database and remembers them in a cache store in memory. If you ask for a model using `find` and his primary key, it will check in the store before requesting it to database.

I know it is useless if good pratices are made accessing your models instances, but it can help the optimization of smal projects.

```php
\App\CachedModel::where('id', '<=', 50)->get();
for ($i=0; $i < 200; $i++) {
    \App\CachedModel::find(1);
}
// Only one request will be made :
// SELECT * FROM `cached_models` WHERE `id` <= 50
```

## Installation

Require this package with composer. Use it with caution, it is not yet production ready.

```bash
composer require vinks/laravel-memory-cache
```

## Usage

You just need to add a trait to your models.

```php
class VeryOftenLoadItem extends Model
{
    use \Vinks\MemoryCaching\SimpleMemoryCached;

    // Additionnally, you can change the maximum number of items will be kept for a model. Default: 50.
    const MEMORY_CACHE_LIMIT = 50;
}
```

## Contributing
 
1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D
 
## History
 
Version 1.0 (2018-09-03) : First implementation of a memory based caching using primary keys.
 
## Credits
 
Lead Developer - Vincent Demonchy (@vinksyunit)
 
## License
 
The MIT License (MIT)

Copyright (c) 2018 Vincent Demonchy

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
