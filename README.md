# yii2-rbac-tutorial

## Overview

This is document and sample code used on my seminar about using RBAC on yii2.

The presentation document is [Yii2RbacTutorial.pdf](./docs/tutorial/Yii2RbacTutorial.pdf)

## Setup for sample code

* You should set write permission to appropriate directory (src/web/assets, src/runtime, src/data).

* Goto `src` directory and run
  ```shell
  composer install
  
  ./yii migrate
  
  ./yii initial-data
  
  ./yii rbac/init
  ```