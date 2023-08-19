### Калькулятор Хаверсайна для расчета расстояний.

```php

    $ellipsoid = new class () implements \Maris\Interfaces\Geo\Model\EllipsoidInterface
    {
        //// Реализовать эллипсоид.
       
       public function getArithmeticMeanRadius() : float
       {
         return 6371008.8;
       }
    };
    
    $calculator = new \Maris\Geo\Haversine\HaversineCalculator( $ellipsoid );

    $point1 = new class implements \Maris\Interfaces\Geo\Aggregate\LocationAggregateInterface{};
    $point2 = new class implements \Maris\Interfaces\Geo\Aggregate\LocationAggregateInterface{};

    $distance = $calculator->calculateDistance( $point1, $point2 );
    
    var_dump($distance); // float растояние между точками.
```