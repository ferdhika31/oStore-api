<?php
/**
 * SearchableTrait.php
 *
 * @package App\Traits
 * @author  Ferdhika Yudira
 * @email   fer@dika.web.id
 */

namespace App\Traits;

trait SearchableTrait
{
    /**
     * @param $currentQuery
     * @param $request
     * @param $condition
     * @return mixed
     */
    public static function appendSearchQuery($currentQuery, $request, $condition)
    {
        if ($condition) {
            foreach ($condition as $key => $value) {

                $requestKey = "q_" . $key;
                if (isset($request[$requestKey]) && $request[$requestKey] != "") {

                    $qRequest = $request[$requestKey];
                    if ($value == "LIKE_FIRST") {
                        $qRequest = "%" . $qRequest;
                    } else if ($value == "LIKE_LAST") {
                        $qRequest = $qRequest . "%";
                    } else if ($value == "LIKE") {
                        $qRequest = "%" . $qRequest . "%";
                    }
                    $currentQuery = $currentQuery->where($key, $value, $qRequest);
                }
            }
        }

        if($request->sort) {
            $currentQuery->orderBy($request->sort, $request->order);
        }else{
            // default
            $currentQuery->orderBy('created_at', 'desc');
        }

        return $currentQuery;
    }
}
