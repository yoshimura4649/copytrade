<?php

namespace App\Enums;

/**
 * 明細種別.
 */
enum RecType: int
{
  case Reception = 1;       // 受付
  case CarryForward = 2;    // 繰越
  case Expired = 3;         // 期限切れ
  case Order = 4;           // 発注
  case Correction = 5;      // 訂正
  case Cancel = 6;          // 取消
  case Invalid = 7;         // 失効
  case Contract = 8;        // 約定

  /**
   * Get the label for the enum value.
   */
  public function label(): string
  {
    return match ($this) {
      self::Reception => '受付',          // Reception
      self::CarryForward => '繰越',       // Carry Forward
      self::Expired => '期限切れ',         // Expired
      self::Order => '発注',              // Order
      self::Correction => '訂正',         // Correction
      self::Cancel => '取消',             // Cancel
      self::Invalid => '失効',            // Invalid
      self::Contract => '約定',           // Contract
    };
  }
}
