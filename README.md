# QC Test

## Endpoints

|Name  |Path                    |Sample Response                                                                |
|------|------------------------|-------------------------------------------------------------------------------|
|Random|/v1/gifs/random         |{"data":[{"title":"My Random GIF", "url": "http://www.mygifs.com/random.gif"}]}
|Search|/v1/gifs/search?q=banana|{"data":{"title":"My Banana GIF", "url": "http://www.mygifs.com/banana.gif"}}  |

## Usage

Start by copying `.env.example` to `.env` and entering your Giphy API key.

Run `composer install`.

You can start the local PHP development server with the following command:

```
cd public
php -S localhost:8000
```

You can then make requests to the API, such as:

```
curl -X GET \
  'http://localhost:8000/v1/gifs/search?q=banana' \
  -H 'API_KEY: f8eeee38-5772-4eb1-8d86-d3d3247f5b52'
```

## Tests

To run unit tests execute the following command:

```
./vendor/bin/phpunit
```
