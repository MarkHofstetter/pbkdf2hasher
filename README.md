# pbkdf2hasher


# install package 


in laravels "config/hashing.php"

set driver to 

'driver' => 'pbkdf2',

in app/config.php add


'providers' => [
...
MarkHofstetter\Pbkdf2Hasher\Pbkdf2HasherServiceProvider::class,

];
