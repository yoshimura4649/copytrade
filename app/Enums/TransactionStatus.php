<?php

namespace App\Enums;

/*
 * 決済ステータス.
 */
enum TransactionStatus: int
{
  case Active = 1;    // アクティブ
  case Inactive = 0;  // 非アクティブ

  /**
   * Get the label for the enum value.
   */
  public function label(): string
  {
    return match ($this) {
      self::Active => 'アクティブ',        // Active
      self::Inactive => '非アクティブ',    // Inactive
    };
  }
}
