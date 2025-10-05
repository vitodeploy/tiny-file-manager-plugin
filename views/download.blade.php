curl -L -o tinyfilemanager.php \
    $(curl -s https://api.github.com/repos/prasathmani/tinyfilemanager/releases/latest \
    | grep '"tag_name"' \
    | cut -d '"' -f 4 \
    | xargs -I {} echo https://raw.githubusercontent.com/prasathmani/tinyfilemanager/{}/tinyfilemanager.php)

mv tinyfilemanager.php {{ $path }}/index.php
