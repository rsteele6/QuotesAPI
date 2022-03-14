<?php

function categoryIdValid($model)
{

    return $model->getSingleCategory();
}

function categoryValid($model)
{
    if(empty($model->category))
    {
        return false;
    }
    else
    {
        return true;
    }
}
?>