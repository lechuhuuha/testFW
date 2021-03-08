<?php

namespace Lchh;

interface Routes
{
    public function getRoutes() : array ;
    public function getAuthentication() : \Lchh\Authentication ;
}
