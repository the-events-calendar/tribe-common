files=("vendor/composer/autoload_files.php" "vendor/composer/autoload_static.php")
search="symfony/deprecation-contracts/function.php"
for file in "${files[@]}"; do
    grep -v "$search" "$file" > temp && mv temp "$file"
done
