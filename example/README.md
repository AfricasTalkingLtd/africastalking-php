# Instructions for running this example

First, create a `.env` file in this example directory with the following content:

```bash
API_KEY="your-app-api-key"
USERNAME="your-app-username"
```

Next, install the dependencies using [Composer](https://getcomposer.org):

```bash
$ composer install
```

Finally, run the [PHP built-in server](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
$ php -S localhost:9090
```

Open your browser and navigate to [`http://localhost:9090`](http://localhost:9090) to see the example in action.

## Folder/File tree

```bash
.
├── README.md # The example's README file.
├── composer.json # The Composer file for the example.
├── index.php # The entry point for the example.
├── public # Contains the frontend files for the example.
│   └── js
│       └── main.js
│   └── css
│       └── style.css
├── views # Contains the views for the example.
│   └── index.php
└── .env # The environment file where you add your credentials.
```
