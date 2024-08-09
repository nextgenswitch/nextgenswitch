#!/bin/bash

# Function to convert singular to plural
pluralize() {
  local singular="$1"

  # Add more rules here as needed for other cases
  case "$singular" in
    *s | *sh | *ch | *x | *z)
      echo "${singular}es"
      ;;
    *y)
      echo "${singular%y}ies"
      ;;
    *)
      echo "${singular}s"
      ;;
  esac
}

pascal_to_snake() {
  local input="$1"
  echo "$input" | sed 's/\([a-z0-9]\)\([A-Z]\)/\1_\2/g' | tr '[:upper:]' '[:lower:]'
}


pwd=$(pwd)

read -p 'Enter Model Name ' model

cd /usr/share/nginx/html/laravel/myapp/

php artisan create:resources $model --table-exists --force

mv -f app/Models/$model."php" $pwd/app/Models
echo 'The model was moved successfully'

models=$(pluralize "$model")

mv -f app/Http/Controllers/"${models}Controller.php" $pwd/app/Http/Controllers
echo 'The controller was moved successfully'

mv -f resources/views/$(pascal_to_snake "$models")  $pwd/resources/views
echo 'The resources were moved successfully'


