# Storage

Defines contracts for storing information.
```php
    interface StorageInterface extends \Countable
```

## Contract Methods

### Get

Must return a value from storage or $default:
```php
    public function get($offset, $default = NULL);
```

## Runtime Storage

The RuntimeStorage class is a storage class that uses a property to store values
in an array. This can be used as a cache for database operations as well.
