<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 26/06/17
 * Time: 15:38
 */

namespace GescomBundle\Tools;


class FilterTransform
{
    public function transform($data){
        $dataArray = [];
        if (is_string($data)){
            $data = explode("&", $data);
            foreach ($data as $row){
                $field = explode("=", $row);
                $dataArray[$field[0]] = $field[1];
            }
        }
        return $dataArray;
    }
}