# Aquabox

> Gestion aquarium 

# Install

```bash
composer install && npm install
php artisan passport:install 
php artisan key:generate
php artisan migrate --seed
```

Activer le scheduler de laravel [src](https://laravel.com/docs/7.x/scheduling#introduction)
```bash 
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Installer les scripts de gestion des composants externes
```bash
git clone https://github.com/dendevs/aquabox_scripts.git pi_scripts
sudo chown www-data -R pi_scripts/*
sudo chmod 774 -R pi_scripts/*
```

Permetre à apache de gérer les GPIO
```bash
sudo adduser www-data gpio
```

