PHP one thread (9)
---------------
```php
Counts: runs 410141 succeess 40320 fails 369821(90)
Array
(
    [jumps] => Array
        (
            [0] => 0
            [1] => 2
            [2] => 7
            [3] => 4
            [4] => 5
            [5] => 1
            [6] => 8
            [7] => 6
            [8] => 3
        )

    [wayCost] => 110
)

real    1m39,659s

```

php multiThread (9)
-----------------


real    0m55.871s---
```php
Volatile Object
(
    [0] => Array
        (
            [jumps] => Array
                (
                    [0] => 0
                    [1] => 5
                    [2] => 2
                    [3] => 3
                    [4] => 7
                    [5] => 6
                    [6] => 4
                    [7] => 8
                    [8] => 1
                )

            [wayCost] => 67
        )

)

real    1m11.519s
```

php multiThread2 (9)
---------------------
```php
Array
(
    [jumps] => Array
        (
            [0] => 0
            [1] => 2
            [2] => 7
            [3] => 4
            [4] => 5
            [5] => 1
            [6] => 8
            [7] => 6
            [8] => 3
        )

    [wayCost] => 110
)
i wait collection: 5.097413
i look for ways: 43.916861
i wait collection: 4.859807
i look for ways: 44.234544
i wait collection: 4.577617
i look for ways: 45.104813
i wait collection: 4.850751
i look for ways: 45.762079
i wait collection: 4.556419
i look for ways: 47.207826
i wait collection: 3.626614
i look for ways: 49.428580
i wait collection: 3.401775
i look for ways: 50.307246
i wait collection: 3.150361
i look for ways: 51.373514
i wait collection: 4.882061
i look for ways: 50.562643
i wait collection: 5.200090
i look for ways: 50.251524
Volatile Object
(
)

real    0m37.915s

```