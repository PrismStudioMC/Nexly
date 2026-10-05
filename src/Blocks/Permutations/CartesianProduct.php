<?php

namespace Nexly\Blocks\Permutations;

class CartesianProduct
{
    /**
     * Returns an 2-dimensional array containing all possible combinations of the provided arrays using the cartesian
     * product (https://en.wikipedia.org/wiki/Cartesian_product).
     */
    public static function get(array $arrays): array
    {
        $result = [];
        $count = count($arrays) - 1;
        $combinations = array_product(array_map(static fn (array $array) => count($array), $arrays));
        for ($i = 0; $i < $combinations; $i++) {
            $result[] = array_map(static fn (array $array) => current($array), $arrays);
            for ($j = $count; $j >= 0; $j--) {
                if (next($arrays[$j])) {
                    break;
                }
                reset($arrays[$j]);
            }
        }
        return $result;
    }
}
