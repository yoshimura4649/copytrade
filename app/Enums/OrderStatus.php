<?php

namespace App\Enums;

/**
 * 保険種類.
 */
enum OrderStatus: int
{
  case Completed = 1; // 完了
  case Failed = 0;    // 失敗

  public function label(): string
  {
    return match ($this) {
      self::Completed => '完了',
      self::Failed => '失敗',
    };
  }
}
