# FileSpace

Flexible storage solution for end-user data.

<img src="http://www.linkorb.com/d/online/linkorb/upload/engineering-blog/filespace_diagram.png" />

Check out our blogpost to see how we're using it in production:   [http://engineering.linkorb.com/blog/scaling-user-data-with-objectstorage-in-php](http://engineering.linkorb.com/blog/scaling-user-data-with-objectstorage-in-php)

## Installing

Check out [composer](http://www.getcomposer.org) for details about installing and running composer.

Then, add `linkorb/filespace` to your project's `composer.json`:

```json
{
    "require": {
        "linkorb/filespace": "~1.0"
    }
}
```
## Usage:

```php
use Aws\S3\S3Client;
use FileSpace\Service\PdoService;
use ObjectStorage\Adapter\S3Adapter;

// Instantiate an S3 connection
$s3client = S3Client::factory(array(
    'key' => 'my_s3_key',
    'secret' => 'my_s3_secret'
));

// Get a storage adapter based on the s3 client
$storage = new S3Adapter($s3client, 'my-bucket-name', 'my/key/prefix/');

// Get a PDO connection
$pdo = ... // TODO;

// Instantiate the File Space Service:
$service = new PdoFileSpaceService($pdo, $storage);

// test if a 'space' has been created:
$space_key = 'account-x.contact.c001';
if (!$service->hasSpace($space_key)) {
    $service->createSpace($space_key, "Customer 1: John Johnson");
}
$space = $service->getSpace($space_key);

// List all files in the space
$files = $service->getFiles($space);
foreach ($files as $file) {
    echo $file->getKey() . ': ' . $file->getSizeOriginalDisplay() . "\n";
}

// Upload a file into this space
$service->upload($space, 'contract-final.pdf', '/home/john/Downloads/contract.pdf');

// Download a file from this space:
$service->download($space, 'invoice-1234.pdf', '/home/john/invoices/invoice-1234.pdf');

// Delete a file from the space:
$service->deleteFile($space, 'remove-me.txt');


```
## Initializing the database schema

    vendor/bin/database-manager database:loadschema --apply filespace example/schema.xml

## Contributing

Ready to build and improve on this repo? Excellent!
Go ahead and fork/clone this repo and we're looking forward to your pull requests!

If you are unable to implement changes you like yourself, don't hesitate to
open a new issue report so that we or others may take care of it.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!

## License

Please check LICENSE.md for full license information
