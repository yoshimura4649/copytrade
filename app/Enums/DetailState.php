<?php

namespace App\Enums;

/**
 * 状態.
 */
enum DetailState: int
{
  case Waiting = 1;        // 待機（発注待機）
  case Processing = 2;     // 処理中（発注送信中・訂正送信中・取消送信中）
  case Processed = 3;      // 処理済（発注済・訂正済・取消済・全約定・期限切れ）
  case Error = 4;          // エラー
  case Deleted = 5;        // 削除済み

  /**
   * Get the label for the enum value.
   */
  public function label(): string
  {
    return match ($this) {
      self::Waiting => '待機',                                          // Waiting
      self::Processing => '処理中',                                     // Processing
      self::Processed => '処理済',                                      // Processed
      self::Error => 'エラー',                                          // Error
      self::Deleted => '削除済み',                                      // Deleted
    };
  }
}
