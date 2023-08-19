<?php

namespace Maris\Geo\Haversine;

use Maris\Interfaces\Geo\Aggregate\LocationAggregateInterface;
use Maris\Interfaces\Geo\Calculator\DistanceCalculatorInterface;
use Maris\Interfaces\Geo\Model\EllipsoidInterface;
use Maris\Interfaces\Geo\Model\LocationInterface;

/**
 * Калькулятор Хаверсайна.
 */
class HaversineCalculator implements DistanceCalculatorInterface
{

    /**
     * Эллипсоид для расчетов.
     * @var EllipsoidInterface
     */
    protected EllipsoidInterface $ellipsoid;

    /**
     * @param EllipsoidInterface $ellipsoid
     */
    public function __construct( EllipsoidInterface $ellipsoid )
    {
        $this->ellipsoid = $ellipsoid;
    }


    /**
     * @inheritDoc
     */
    public function calculateDistance(LocationInterface|LocationAggregateInterface $start, LocationInterface|LocationAggregateInterface $end): float
    {
        $start = self::deg2radLocationToArray( $start );
        $end = self::deg2radLocationToArray( $end );

        return $this->ellipsoid->getArithmeticMeanRadius() * 2 * asin(
                sqrt(
                    (sin(($end["lat"] - $start["lat"]) / 2) ** 2) +
                    cos($start["lat"]) * cos($end["lat"]) * (sin(($end["long"] - $start["long"]) / 2) ** 2)
                )
            );
    }

    /**
     * Приводит точка-подобный объект к точке.
     * @param LocationInterface|LocationAggregateInterface $location
     * @return LocationInterface
     */
    protected static function convertLocationAggregate( LocationInterface|LocationAggregateInterface $location ):LocationInterface
    {
        if(is_a($location,LocationAggregateInterface::class))
            return $location->getLocation();
        return $location;
    }

    /**
     * Приводит объект LocationInterface|LocationAggregateInterface
     * к массиву вида [longitude, latitude], преобразованных в радианы.
     * @param LocationInterface|LocationAggregateInterface $location
     * @return array{lat:float,long:float}
     */
    protected static function deg2radLocationToArray( LocationInterface|LocationAggregateInterface $location):array
    {
        $location = self::convertLocationAggregate( $location );
        return [
            "lat" => deg2rad( $location->getLatitude() ),
            "long" => deg2rad( $location->getLongitude() )
        ];
    }
}