<?php

namespace App\Enums;

/*
 * 決済ステータス.
 */
enum TransactionStatus: int
{
  case active = 1;
  case inactive = 0;
}
