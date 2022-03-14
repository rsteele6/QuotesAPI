<?php

function authorIdValid($model)
{
    return $model->getSingleAuthor();
}

function authorValid($model)
{
    if(empty($model->author))
    {
        return false;
    }
    else
    {
        return true;
    }
}
?>