#!/bin/bash 

ROOT_PATH='./'

sudo chmod 775 -R "${ROOT_PATH}storage/${*}"
sudo chmod 775 -R "${ROOT_PATH}bootstrap/cache"

sudo chown $USER:www-data -R "${ROOT_PATH}storage/${*}"
sudo chown $USER:www-data -R "${ROOT_PATH}bootstrap/cache"

#sudo chmod u+x "${ROOT_PATH}scripts/${*}.sh"

exit $?
