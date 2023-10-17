# php-share

Share a link or a text snippet.

The links and snippets are stored in a `.json` file and will be deleted after been read once.

## Usage

- Go to `https://example.com/share`, pick an id and add your text.
- Going to `https://example.com/share/the-id-you-picked` will show the text (and delete it from the server). 


## Installation

- Create an `.htaccess`:

    ```
    RewriteEngine on

    # Usually it RewriteBase is just '/', but
    # replace it with your subdirectory path
    RewriteBase /share/

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule /?(.+)/?$ index.php?id=$1 [QSA,L]
    ```

- Create a `config.php` file based on `config-demo.php`.
- Create a `style.css` file based on `style.css`.
- `mkdir path/to/your/data`  
  (if  you don't want the list of the shared text to be world readable)
