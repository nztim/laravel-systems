<?php

return [
    'map' => [
        // Command => HandlerWithHandleMethod::class,
        // Command => [Handler::class, 'methodName'],
    ],
    
    'middleware' => [
        // NZTim\CommandBus\Laravel\DbTransactionMiddleware::class,
        // CommandHandlerMiddleware is automatically added to the end of the list
    ],
];
