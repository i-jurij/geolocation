# A part of oswc framework for geolocation

## Install
It contain two parts: php and js, also it need upload into vendor and node_modules directory   
for ease of autoloading.   
PHP that can be install by composer (composer.json) and   
JS that can be install by npm (package.json).   

**composer.json**:   
```
{
    ...
    "repositories": [
        {
            "url": "https://github.com/i-jurij/geolocation.git",
            "type": "git"
        }
    ],
    "require": {
        "i-jurij/geolocation": "~1.0"
    }
}
```
**package.json**:   
```
{
    ...
    "dependencies": {
        "geolocation": "github:i-jurij/geolocation"
  }
}
```