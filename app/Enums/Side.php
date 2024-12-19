<?php

namespace App\Enums;

/**
 * 保険種類.
 */
enum Side: int
{
  case Sell = 1;  // 売
  case Buy = 2;   // 買

  public function label(): string
  {
    return match ($this) {
      self::Sell => '売',   // 売
      self::Buy => '買',    // 買
    };
  }
}
