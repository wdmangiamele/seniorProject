<?php

class Functions {
    function __construct() {
    }//end Functions constructor

    /* function to add seven days to specific date
     * @param $date - the desired date
     * @return $newDate - the new date with an added seven days
     * */
    function addSevenDays($date) {
        $newDate = date("Y-m-d", strtotime("+7 days", strtotime($date)));
        return $newDate;
    }//end addSevenDays

    /* function to sift through values of one array and see if they're missing in another array
     * normally used with MySQL data
     * @param $arr1 - the array that has all the values you want to search for in the other array
     * @param $arr2 - the array you want to search in
     * @param $valueName - the name of the value you want to search for
     * @return $missingValues - an array that has all the values missing from the second array (arr2)
     * */
    function createMissingValuesArray($arr1, $arr2, $valueName) {
        $missingValues = array();
        for ($e = 0; $e < sizeof($arr1); $e++) {
            if (!in_array($arr1[$e][$valueName], $arr2)) {
                array_push($missingValues, $arr1[$e][$valueName]);
            }
        }
        return $missingValues;
    }//end createMissingValuesArray

    /* function to move one index to another
     * @param $array - the array that has indexes you want to move
     * @param $a - the index you want to move from
     * @param $b - the index you want to move to
     * @return $array - the array with newly moved indexes
     * */
    function moveElement(&$array, $a, $b) {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
        return $array;
    }//end moveElement

    /* function to set the second param for the executeQuery function as an empty array
     * @return $params - returns an empty array
     */
    function paramsIsZero() {
        $params = array();
        return $params;
    }//end paramsIsZero

    /* function that sorts an associative array from greatest to least value
     * @param $array - the chosen array to be sorted
     * @param $key - the name of the key that will be used to help compare two values in the associative array
     * @param $value - the name of the value that be used to help compare two values in the associative array
     * @return $array - the chosen array but sorted from greatest to least
     * */
    function sortArray($array, $key, $value) {
        for($i = 0; $i < sizeof($array); $i++) {
            for($h = 0; $h < sizeof($array) - $i - 1; $h++) {
                if ($array[$h][$value] < $array[$h + 1][$value]) {
                    $tempID = $array[$h][$key];
                    $tempCount = $array[$h][$value];
                    $array[$h][$key] = $array[$h + 1][$key];
                    $array[$h][$value] = $array[$h + 1][$value];
                    $array[$h + 1][$key] = $tempID;
                    $array[$h + 1][$value] = $tempCount;
                }
            }
        }
        return $array;
    }//end sortArray

    /* function that sorts an associative array from least to greatest value
     * @param $array - the chosen array to be sorted
     * @param $key - the name of the key that will be used to help compare two values in the associative array
     * @return $array - the chosen array but sorted from greatest to least
     * */
    function sort2DArray($array, $key) {
        for($i = 0; $i < sizeof($array); $i++) {
            for($h = 0; $h < sizeof($array) - $i - 1; $h++) {
                if ($array[$h][$key] > $array[$h + 1][$key]) {
                    $tempArr = $array[$h];
                    $array[$h] = $array[$h + 1];
                    $array[$h + 1] = $tempArr;
                }
            }
        }
        return $array;
    }//end sort2DArray


    /* function to test if the MySQL values that was fetch is null
     * @param $sqlData - data that was fetched from MySQL
     * @param String - a string that spells out "None" if the value is null
     * @return $sqlData - the data that was fetched if it was found to be not null
     */
    function testSQLNullValue($sqlData) {
        if($sqlData === "NULL") {
            return "None";
        }else {
            return $sqlData;
        }
    }//end testSQLNullValue

    /* function to help turn MySQL column data into a normal array
     * @param $data - data that came from MySQL as an associative array
     * @param $columnName - the name of the MySQL column you would like as a normal array
     * @return $normalArray - the data as a normal indexed array
     * */
    function turnIntoNormalArray($data, $columnName) {
        $normalArray = array();

        for ($e = 0; $e < sizeof($data); $e++) {
            array_push($normalArray, $data[$e][$columnName]);
        }
        return $normalArray;
    }//end turnIntoNormalArray

}//end Functions