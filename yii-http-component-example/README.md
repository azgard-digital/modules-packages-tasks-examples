
Http component is simple Yii wrapper for guzzle sync queries
===============================

Install Package
===============================
Add to composer.json
---------
```bash
"repositories": [
    ...
    {
        "type": "git",
        "url":  "..."
    }
    ...
]

"require": {
    "yii-http-component": "dev-master"
}

in project directory
composer update
```

Register component in Yii
===============================
```bash
'components' => [
    ...
    'httpClient' => [
        'class' => 'HttpClient\HttpClientComponent',
    ],
    ...
]

put in WebApplication class next method 

public function getHttpClientComponent()
{
    return $this->getComponent('httpClient');
}
```

Examples:
===============================

http|https queries
---------
```bash
Yii::app()->getHttpClientComponent()
            ->make()
            ->setMethod(POST|PUT...) default is GET
            ->setUrl(url)
            ->addHeaders([...])
            ->setBody("")
            ->execute();
            
Yii::app()->getHttpClientComponent()
            ->make([
                'exceptionHandler' => function($e) {
                    /**
                       your handler 
                    */
                }
            ])
            ->setUrl(url)
            ->execute();
```
            
Get file content
---------
```bash
Yii::app()->getHttpClientComponent()
            ->makeStream()
            ->setUrl(filename)
            ->execute();
```
